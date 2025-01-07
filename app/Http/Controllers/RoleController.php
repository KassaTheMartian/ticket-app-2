<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Role::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function($row) {
                    return view('components.action-buttons', [
                        'row' => $row,
                        'editRoute' => 'admin.roles.edit',
                        'deleteRoute' => 'admin.roles.destroy'
                    ])->render();
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        
        $roles = Role::all();
        return view('admin.roles.index', compact('roles'));
    }

    public function create()
    {
        $routes = [];
        $all = Route::getRoutes();
        foreach($all as $r){
            $name = $r->getName();
            $pos = strpos($name,'admin');
            if($pos !== false && !in_array($name,$routes)){
                array_push($routes,$r->getName());
            }
        }
       
        return view('admin.roles.create',compact('routes'));
    }

    public function store(Request $request)
    {
        $request->validate(['name' => 'required'],['name.required'=>'Role name is required']);
        $routes = json_encode($request->route);
        $routesArray = json_decode($routes, true); // Decode JSON to array
        $routesArray[] = ['error','admin.auth.logout'];
        $routes = json_encode($routesArray);
        try {
            Role::create(['name' => $request->name, 'permissions' => $routes]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'This role already exists in the database.']);
        }
        return redirect()->route('admin.roles.index')->with(['success'=>"Role added successfully"]);
    }
    
    public function edit($id)
    {
        $role = Role::findOrFail($id);
        $permissions = json_decode($role->permissions, true);
        
        $routes = [];
        foreach (Route::getRoutes() as $route) {
            $name = $route->getName();
            if (str_contains($name, 'admin') && !in_array($name, $routes)) {
                $routes[] = $name;
            }
        }
       
        return view('admin.roles.edit', compact('routes', 'role', 'permissions'));
    }

    public function update(Request $request, $id)
    {
        $roles = Role::find($id);
        $request->validate(
            ['name' => 'required'],
            ['name.required' => 'Role name is required']
        );
    
        if ($request->has('route')) {
            $routes = json_encode(array_merge($request->route, ['error', 'admin.auth.logout']));
        } else {
            $routes = $roles->permissions;
        }
        
        try {
            $roles->update([
            'name' => $request->name,
            'permissions' => $routes,
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'An error occurred while updating the role.']);
        }
        
        return redirect()->back()->with(['success' => "Role updated successfully"]);
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        $role->delete();
        return redirect()->route('admin.roles.index')->with('message', 'Role deleted successfully');
    }
}