<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Department;
use Yajra\DataTables\Facades\DataTables;

class DepartmentController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Department::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    return view('components.action-buttons', [
                        'row' => $row,
                        'editRoute' => 'admin.departments.edit',
                        'deleteRoute' => 'admin.departments.destroy'
                    ])->render();
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        
        return view('admin.departments.index');
    }

    public function create()
    {
        return view('admin.departments.create');
    }

    public function store(Request $request) 
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:departments,name',
            'description' => 'nullable|string',
        ]);
        Department::create($request->all());
        return redirect()->route('admin.departments.index')->with('success', 'Phòng ban đã được tạo.');
    }

    public function show($id)
    {
        $department = Department::findOrFail($id);
        return view('admin.departments.show', compact('department'));
    }

    public function edit($id)
    {
        $department = Department::findOrFail($id);
        $users = $department->users()->get();
        return view('admin.departments.edit', compact('department', 'users'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:departments,name,' . $id,
            'description' => 'nullable|string',
        ]);
        $department = Department::findOrFail($id);
        $department->update($request->all());
        return redirect()->back()->with('success', 'Phòng ban đã được cập nhật.');
    }

    public function destroy($id)
    {
        $department = Department::findOrFail($id);
        $department->delete();
        return redirect()->route('admin.departments.index')->with('success', 'Phòng ban đã được xóa.');
    }
}