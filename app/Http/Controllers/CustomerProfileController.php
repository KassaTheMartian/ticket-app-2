<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerProfileController extends Controller
{
    public function show()
    {
        // Get the authenticated customer using the 'customer' guard
        $customer = auth()->guard('customer')->user();
        // Return the user's profile information
        return view('customer.profile.show', compact('customer'));
    }
    public function update(Request $request)
    {
        $customer = auth()->guard('customer')->user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email|unique:customers,email,' . $customer->id,
            'phone' => 'required|string|max:255|unique:users,phone|unique:customers,phone,' . $customer->id,
            'address' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|in:male,female,other',
            'software' => 'nullable|string|max:255',
            'website' => 'nullable|url|max:255',
            'tax_number' => 'nullable|digits_between:10,13',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'current_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:8|max:20|confirmed',
        ]);

        if ($request->filled('new_password')) {
            // Kiểm tra mật khẩu hiện tại nếu người dùng có nhập
            if (!$request->filled('current_password') || !Hash::check($request['current_password'], $customer->password)) {
                return back()->withErrors(['current_password' => 'The current password is required and must be correct to change the password']);
            }

            // Kiểm tra new password và new password confirm có giống nhau không
            if ($request->new_password !== $request->new_password_confirmation) {
                return back()->withErrors(['new_password' => 'The new password and confirmation do not match']);
            }

            $customer->password = $request['new_password'];
        }

        $customer->fill($request->except(['current_password', 'new_password', 'new_password_confirmation']));

        // Xử lý ảnh đại diện
        if ($request->hasFile('profile_picture')) {
            $customer->profile_picture = $request->file('profile_picture')->store('profile_pictures', 'public');
        }

        // Đổi mật khẩu nếu được cung cấp
        if ($request->filled('new_password')) {
            $customer->password = $request['new_password'];
        }

        $customer->save();

        return redirect()->route('customer.profile')->with('success', 'Profile updated successfully');
    }
}
