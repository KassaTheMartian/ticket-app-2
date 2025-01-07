<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Customer;
use App\Mail\CustomerCreatedEmail;
use Illuminate\Support\Facades\Mail;
use Yajra\DataTables\Facades\DataTables;


class CustomerController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Customer::query();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return view('components.action-buttons', [
                        'row' => $row,
                        'editRoute' => 'admin.customers.edit',
                        'deleteRoute' => 'admin.customers.destroy'
                    ])->render();
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.customers.index');
    }


    public function create()
    {
        return view('admin.customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:customers,email|unique:users,email',
            'phone' => 'required|digits_between:10,11|unique:customers,phone|unique:users,phone',
            'profile_picture' => 'nullable|image',
            'name' => 'required',
            'password' => 'required|min:8',
            'date_of_birth' => 'nullable|date',
            'tax_number' => 'nullable|digits_between:10,13',
        ]);

        $customer = new Customer($request->except('profile_picture'));

        if ($request->hasFile('profile_picture')) {
            $customer->profile_picture = $request->file('profile_picture')->store('profile_pictures', 'public');
        }

        $customer->save();
        $customer->markEmailAsVerified();

        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => $request->password,
        ];

        Mail::to($customer->email)->queue(new CustomerCreatedEmail($data));

        return redirect()->route('admin.customers.index')->with('success', 'Customer has been created.');
    }
    public function show($id)
    {
        $customer = Customer::findOrFail($id);
        return view('admin.customers.show', compact('customer'));
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'email' => 'required|email|unique:customers,email,' . $id . '|unique:users,email',
            'phone' => 'required|digits_between:10,11|unique:customers,phone,' . $id . '|unique:users,phone',
            'profile_picture' => 'nullable|image',
            'name' => 'required',
            'date_of_birth' => 'nullable|date',
            'tax_number' => 'nullable|digits_between:10,13',
        ]);

        $customer = Customer::findOrFail($id);
        $customer->fill($request->except('profile_picture'));

        if ($request->hasFile('profile_picture')) {
            $customer->profile_picture = $request->file('profile_picture')->store('profile_pictures', 'public');
        }

        $customer->save();

        return redirect()->back()->with('success', 'Customer has been updated.');
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail($id);
        $customer->delete();
        return redirect()->route('admin.customers.index')->with('success', 'Customer has been deleted.');
    }
}
