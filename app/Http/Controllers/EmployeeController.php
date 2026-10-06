<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $employees = Employee::when($request->filled('search'), function ($q) use ($request) {
            $term = $request->input('search');
            $q->where(function ($w) use ($term) {
                $w->where('name', 'LIKE', "%{$term}%")
                    ->orWhere('email', 'LIKE', "%{$term}%")
                    ->orWhere('department', 'LIKE', "%{$term}%")
                    ->orWhere('position', 'LIKE', "%{$term}%");
            });
        })->when($request->filled('department'), function ($q) use ($request) {
            $q->where('department', $request->input('department'));
        })->latest()->paginate(10)->withQueryString();

        $existingDepts = Employee::whereNotNull('department')->where('department', '!=', '')->distinct()->pluck('department')->toArray();
        $departments = array_unique(array_merge(Employee::DEPARTMENTS, $existingDepts));

        return view('employees.index', compact('employees', 'departments'));
    }

    public function create()
    {
        return view('employees.create', [
            'departments' => Employee::DEPARTMENTS,
            'positions' => Employee::POSITIONS,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email',
            'department' => 'nullable|string|max:100',
            'position' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
        ]);

        $validated['created_by'] = auth()->id();
        $employee = Employee::create($validated);

        return redirect()->route('employees.show', $employee)->with('success', 'Data karyawan berhasil ditambahkan.');
    }

    public function show(Employee $employee)
    {
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        abort_if($employee->created_by !== auth()->id() && ! auth()->user()->isAdmin(), 403);

        return view('employees.edit', [
            'employee' => $employee,
            'departments' => Employee::DEPARTMENTS,
            'positions' => Employee::POSITIONS,
        ]);
    }

    public function update(Request $request, Employee $employee)
    {
        abort_if($employee->created_by !== auth()->id() && ! auth()->user()->isAdmin(), 403);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:employees,email,' . $employee->id,
            'department' => 'nullable|string|max:100',
            'position' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20',
        ]);

        $updateData = $request->only(['name', 'email', 'department', 'position', 'phone']);

        if ($request->hasFile('photo')) {
            $request->validate([
                'photo' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,gif,webp',
            ]);
            $path = $request->file('photo')->store('avatars', 'public');
            $updateData['photo'] = $path;

            // Also sync corresponding user avatar if exists
            $user = \App\Models\User::where('email', $employee->email)->first();
            if ($user) {
                $user->update(['avatar' => $path]);
            }
        }

        $employee->update($updateData);

        return redirect()->route('employees.show', $employee)->with('success', 'Data karyawan berhasil diperbarui.');
    }

    public function destroy(Employee $employee)
    {
        abort_if($employee->created_by !== auth()->id() && ! auth()->user()->isAdmin(), 403);

        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Data karyawan berhasil dihapus.');
    }
}
