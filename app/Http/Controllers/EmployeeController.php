<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Helpers\PhoneHelper;

class EmployeeController extends Controller
{
    public function index()
    {
        $users = User::orderBy('first_name')
            ->orderBy('last_name')
            ->get();

        return view('content.pages.employees.index', compact('users'));
    }

    public function store(Request $request)
    {
        $data = $request->validateWithBag('createEmployee', [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name'  => ['required', 'string', 'max:255'],
            'email'      => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone'      => ['nullable', 'string', 'max:50'],
            'role'       => ['required', 'string', 'max:100'],
            'password' => [
                'required',
                'string',
                'min:10',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'regex:/[^A-Za-z0-9]/',
            ],
            'must_change_password' => ['nullable', 'boolean'],
        ]);

        $data['first_name'] = trim($data['first_name']);
        $data['last_name'] = trim($data['last_name']);
        $data['email'] = strtolower(trim($data['email']));
        $data['phone'] = PhoneHelper::normalize($data['phone'] ?? null);
        $data['is_active'] = true;
        $data['must_change_password'] = $request->boolean('must_change_password', true);

        User::create($data);

        return back()->with('success', 'User created successfully.');
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validateWithBag('updateEmployee', [
            'first_name' => ['required', 'string', 'max:255'],
            'last_name'  => ['required', 'string', 'max:255'],
            'email'      => ['required', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'phone'      => ['nullable', 'string', 'max:50'],
            'role'       => ['required', 'string', 'max:100'],
            'is_active'  => ['nullable', 'boolean'],
            'password' => [
                'nullable',
                'string',
                'min:10',
                'regex:/[A-Z]/',
                'regex:/[a-z]/',
                'regex:/[0-9]/',
                'regex:/[^A-Za-z0-9]/',
            ],
            'must_change_password' => ['nullable', 'boolean'],
        ]);

        $data['first_name'] = trim($data['first_name']);
        $data['last_name'] = trim($data['last_name']);
        $data['email'] = strtolower(trim($data['email']));
        $data['phone'] = PhoneHelper::normalize($data['phone'] ?? null);
        $data['is_active'] = $request->boolean('is_active');
        $data['must_change_password'] = $request->boolean('must_change_password');

        if (empty($data['password'])) {
            unset($data['password']);
        }

        $user->update($data);

        return back()->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->update([
            'is_active' => false,
        ]);

        return back()->with('success', 'User disabled successfully.');
    }
}