<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCreatedEmail;
use App\Models\User;
use App\Models\Role;
use App\Models\UserRole;
use App\Models\Department;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            return $this->getDataTable();
        }
        
        return view('admin.users.index');
    }

    private function getDataTable()
    {
        $data = User::query()
            ->leftJoin('departments', 'users.department_id', '=', 'departments.id')
            ->select([
                'users.*',
                'departments.name as department_name'
            ]);
            
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function($row) {
                return view('components.action-buttons', [
                    'row' => $row,
                    'editRoute' => 'admin.users.edit',
                    'deleteRoute' => 'admin.users.destroy'
                ])->render();
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    public function create()
    {
        $roles = $this->getRoles();
        $departments = $this->getDepartments();
        return view('admin.users.create', compact('roles', 'departments'));
    }

    private function getRoles()
    {
        return Role::orderBy('name')->get();
    }

    private function getDepartments()
    {
        return Department::orderBy('name')->get();
    }


    public function store(Request $request) 
    {
        $this->validateRequest($request);

        $user = $this->createUser($request);
        $this->handleProfilePicture($request, $user);
        $this->assignRole($request, $user);
        $this->sendUserCreatedEmail($request, $user);

        return redirect()->route('admin.users.index')->with('success', 'Người dùng đã được tạo và xác minh.');
    }

    private function validateRequest(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255|unique:customers,email|unique:users,email',
            'phone' => 'required|digits_between:10,11|unique:customers,phone|unique:users,phone',
            'name' => 'required|string|max:255',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image',
            'department_id' => 'nullable|exists:departments,id',
            'role' => 'nullable|exists:roles,id',
            'password' => 'required|string|min:8',
        ]);
    }

    private function createUser(Request $request)
    {
        $user = User::create($request->all());
        $isAdminChecked = $request->has('admin');
        $user->admin = $isAdminChecked ? 1 : 0;
        $user->markEmailAsVerified();
        return $user;
    }

    private function handleProfilePicture(Request $request, User $user)
    {
        if ($request->hasFile('profile_picture')) {
            $user->update(['profile_picture' => $request->file('profile_picture')->store('profile_pictures', 'public')]);
        }
    }

    private function assignRole(Request $request, User $user)
    {
        if ($request->filled('role')) {
            UserRole::create(['user_id' => $user->id, 'role_id' => $request->role]);
        }
    }

    private function sendUserCreatedEmail(Request $request, User $user)
    {
        Mail::to($user->email)->queue(new UserCreatedEmail($request->only('name', 'email', 'phone', 'password')));
    }

    public function show($id)
    {
        $user = $this->findUserById($id);
        return view('admin.users.show', compact('user'));
    }

    private function findUserById($id)
    {
        return User::findOrFail($id);
    }

    public function edit($id)
    {
        $user = $this->findUserById($id);
        $roles = $this->getRoles();
        $departments = $this->getDepartments();
        $role_assign = $user->roles->pluck('name')->toArray();

        return view('admin.users.edit', compact('user', 'roles', 'role_assign', 'departments'));
    }

    public function update(Request $request, $id)
    {
        $this->validateUpdateRequest($request, $id);

        $user = $this->findUserById($id);
        $this->updateUser($request, $user);
        $this->handleProfilePicture($request, $user);
        $this->syncRoles($request, $user);

        return redirect()->back()->with('success', 'User has been updated.');
    }

    private function validateUpdateRequest(Request $request, $id)
    {
        $request->validate([
            'email' => 'required|email|max:255|unique:customers,email|unique:users,email,' . $id,
            'phone' => 'required|digits_between:10,11|unique:customers,phone|unique:users,phone,' . $id,
            'name' => 'required|string|max:255',
            'date_of_birth' => 'nullable|date',
            'address' => 'nullable|string|max:255',
            'profile_picture' => 'nullable|image',
            'department_id' => 'nullable|exists:departments,id',
            'role' => 'nullable|exists:roles,id',
            'password' => 'nullable|string|min:8',
        ]);
    }

    private function updateUser(Request $request, User $user)
    {
        $user->fill($request->except('profile_picture'));
        $isAdminChecked = $request->has('admin');
        $user->admin = $isAdminChecked ? 1 : 0;
        $user->save();
    }

    private function syncRoles(Request $request, User $user)
    {
        if ($request->filled('role')) {
            $user->roles()->sync([$request['role']]);
        }
    }

    public function destroy($id)
    {
        $user = $this->findUserById($id);
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User has been deleted.');
    }
}
