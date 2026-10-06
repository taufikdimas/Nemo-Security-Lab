<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::where('role', '!=', 'service');

        // Deactivated accounts stay listed so they can be reactivated again;
        // ?status=inactive narrows the list and ?status=all shows everyone.
        if ($request->input('status') === 'inactive') {
            $query->where('is_active', false);
        } elseif ($request->input('status') !== 'all') {
            $query->where('is_active', true);
        }

        // Search
        if ($request->has('search') && !empty($request->search)) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'LIKE', "%{$request->search}%")
                  ->orWhere('email', 'LIKE', "%{$request->search}%")
                  ->orWhere('department', 'LIKE', "%{$request->search}%")
                  ->orWhere('position', 'LIKE', "%{$request->search}%");
            });
        }

        // Filter by role
        if ($request->has('role') && !empty($request->role)) {
            $query->where('role', $request->role);
        }

        $users = $query->orderBy('name')->paginate(15)->withQueryString();

        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $clients = Client::orderBy('name')->get(['id', 'name', 'company']);

        return view('admin.users.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:user,admin,client',
            'client_id' => ['nullable', 'integer', 'exists:clients,id', 'required_if:role,client'],
            'department' => 'nullable|string|max:100',
            'position' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['is_active'] = true;
        $validated['api_token'] = User::generateApiToken($validated['email'], now());

        // client_id hanya relevan untuk role client; role lain harus null.
        $validated['client_id'] = $validated['role'] === 'client'
            ? $validated['client_id']
            : null;

        User::create($validated);

        return redirect()->route('admin.users.index')
                        ->with('success', 'User created successfully.');
    }

    /**
     * Suspends or reinstates an account. Service accounts and the signed-in
     * operator are refused so an operator cannot lock everyone out.
     */
    public function toggleActive(Request $request, User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'Anda tidak dapat menonaktifkan akun sendiri.']);
        }

        if ($user->role === 'service') {
            return back()->withErrors(['error' => 'Akun service tidak dapat dinonaktifkan.']);
        }

        $user->update(['is_active' => ! $user->is_active]);

        return redirect()->route('admin.users.index')
            ->with('success', $user->is_active
                ? 'Akun ' . $user->name . ' berhasil diaktifkan kembali.'
                : 'Akun ' . $user->name . ' berhasil dinonaktifkan.');
    }

    /**
     * Issues a one-time temporary password for an account that can no longer be
     * unlocked through the usual channel.
     */
    public function resetPassword(Request $request, User $user)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user->update(['password' => Hash::make($request->input('password'))]);

        return redirect()->route('admin.users.index')
            ->with('success', 'Kata sandi sementara untuk ' . $user->name . ' berhasil diatur.');
    }

    /**
     * The users resource generates a detail route, but this module only ever
     * shipped create/edit screens, so detail resolves to the edit form.
     */
    public function show(User $user)
    {
        return redirect()->route('admin.users.edit', $user);
    }

    public function edit(User $user)
    {
        $clients = Client::orderBy('name')->get(['id', 'name', 'company']);

        return view('admin.users.edit', compact('user', 'clients'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'role' => 'required|in:user,admin,client',
            'client_id' => ['nullable', 'integer', 'exists:clients,id', 'required_if:role,client'],
            'department' => 'nullable|string|max:100',
            'position' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->filled('password')) {
            $validated['password'] = Hash::make($request->password);
        }

        $validated['is_active'] = $request->has('is_active') ? true : false;

        // Operator lockout guard. toggleActive() already refuses to suspend the
        // signed-in operator, but this form is a second way in: role is just a
        // field the admin can set to anything, and is_active is forced from a
        // checkbox. Either one can strand the last admin account with no way back
        // in, so refuse any self-edit that changes role or deactivates.
        if ($user->id === auth()->id()) {
            if ($request->input('role') !== $user->role) {
                return back()->withInput()->withErrors([
                    'role' => 'Anda tidak dapat mengubah role akun sendiri.',
                ]);
            }

            if ($validated['is_active'] === false) {
                return back()->withInput()->withErrors([
                    'is_active' => 'Anda tidak dapat menonaktifkan akun sendiri.',
                ]);
            }
        }

        // the issued token is derived from the account address, so it has to be
        // reissued whenever the address changes
        if ($validated['email'] !== $user->email) {
            $validated['api_token'] = User::generateApiToken($validated['email'], $user->created_at ?? now());
        }

        if ($request->hasFile('avatar')) {
            $request->validate([
                'avatar' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,gif,webp',
            ]);
            $path = $request->file('avatar')->store('avatars', 'public');
            $validated['avatar'] = $path;

            // Also sync corresponding employee if exists
            $employee = \App\Models\Employee::where('email', $user->email)->first();
            if ($employee) {
                $employee->update(['photo' => $path]);
            }
        }

        $user->update($validated);

        // Dipaksa terpisah dari $validated: kalau akun ini tadinya client lalu
        // diubah jadi admin/user, client_id wajib dilepas agar tidak lagi
        // memegang akses portal.
        $user->client_id = $validated['role'] === 'client'
            ? ($validated['client_id'] ?? null)
            : null;
        $user->save();

        return redirect()->route('admin.users.index')
                        ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->withErrors(['error' => 'You cannot delete your own account.']);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
                        ->with('success', 'User deleted successfully.');
    }
}