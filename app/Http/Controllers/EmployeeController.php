<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class EmployeeController extends Controller
{
    public function index()
    {
        $employees = User::all();
        return view('employees.index', compact('employees'));
    }

    public function create()
    {
        $roles = Role::pluck('name','name')->all();
        return view('employees.create',compact('roles'));
    }

    public function store(Request $request)
    {
        // Validate the input data
        $validatedData = $request->validate([
            'name' => 'required',
            'phone' => 'required|unique:users',
            'hire_date' => 'nullable|date',
            'employee_status' => 'required',
            'salary' => 'required|numeric',
            'photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'documents' => 'file|mimes:pdf,doc,docx|max:2048',
            'termination_date' => 'nullable|date',
            'password' => 'required'
        ]);


        // Process file uploads if present
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store('employee_photos', 'public');
            $validatedData['photo'] = $photoPath;
        }
        if ($request->hasFile('documents')) {
            $documentPath = $request->file('documents')->store('employee_documents', 'public');
            $validatedData['documents'] = $documentPath;
        }

        $employee = User::create($validatedData);

        return response()->json(['message' => 'Employee added successfully.', 'employee_id' => $employee->id]);
    }

    public function show(User $employee)
    {
        return view('employees.show', compact('employee'));
    }

    public function edit(User $employee)
    {
        $roles = Role::pluck('name','id');
        return view('employees.edit', compact('employee','roles'));
    }

    public function update(Request $request, User $employee)
    {
        // Validate the input data
        $validatedData = $request->validate([
            'name' => 'required',
            'phone' => 'required|unique:users,phone,' . $employee->id,
            'hire_date' => 'nullable|date',
            'employee_status' => 'required',
            'salary' => 'required|numeric',
            'photo' => 'image|mimes:jpeg,png,jpg,gif|max:2048',
            'documents' => 'file|mimes:pdf,doc,docx|max:2048',
            'termination_date' => 'nullable|date',
            'password' => 'nullable|string|min:6',
        ]);

        // Process file uploads if present
        if ($request->hasFile('photo')) {
            // Delete previous photo if exists
            if ($employee->photo) {
                Storage::disk('public')->delete($employee->photo);
            }
            $photoPath = $request->file('photo')->store('employee_photos', 'public');
            $validatedData['photo'] = $photoPath;
        }
        if ($request->hasFile('documents')) {
            // Delete previous documents if exist
            if ($employee->documents) {
                Storage::disk('public')->delete($employee->documents);
            }
            $documentPath = $request->file('documents')->store('employee_documents', 'public');
            $validatedData['documents'] = $documentPath;
        }

        if ($request->has('password')) {
            $validatedData['password'] = bcrypt($request->input('password'));
        }

        $employee->update($validatedData);
        $role = Role::findOrFail($request->input('roles'));

        $employee->syncRoles([$role]);

        return response()->json(['message' => 'Employee updated successfully.', 'employee_id' => $employee->id]);
    }

    public function destroy(User $employee)
    {
        // Delete associated files
        if ($employee->photo) {
            Storage::disk('public')->delete($employee->photo);
        }
        if ($employee->documents) {
            foreach ($employee->documents as $document) {
                Storage::disk('public')->delete($document);
            }
        }

        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }

    // Add the delete method as needed
}
