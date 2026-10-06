<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', ['user' => auth()->user()]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . auth()->id(),
            'bio' => 'nullable|string|max:1000',
            'department' => 'nullable|string|max:100',
            'position' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = \Illuminate\Support\Facades\Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        auth()->user()->update($validated);

        ActivityLog::create([
            'user_id' => auth()->id(),
            'action' => 'update_profile',
            'details' => 'Updated profile information',
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        return back()->with('success', 'Profile updated successfully.');
    }

    public function updateAvatar(Request $request)
    {
        $request->validate([
            'avatar' => 'required|file|max:5120|mimes:jpg,jpeg,png,gif,webp',
        ]);

        $user = auth()->user();
        
        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $path = $request->file('avatar')->store('avatars', 'public');
        $user->update(['avatar' => $path]);

        // Synchronize with employee record if exists
        $employee = \App\Models\Employee::where('email', $user->email)->first();
        if ($employee) {
            $employee->update(['photo' => $path]);
        }

        return back()->with('success', 'Foto profil berhasil diperbarui.');
    }

    public function apiAccess()
    {
        $user = auth()->user();

        if (blank($user->api_token)) {
            $user->api_token = User::generateApiToken($user->email, $user->created_at ?? now());
            $user->save();
        }

        return view('profile.api', ['user' => $user]);
    }
}
