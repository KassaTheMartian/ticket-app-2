<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserProfileController extends Controller
{

    public function show()
    {
        // Get the authenticated user
        $user = auth()->user();

        // Return the user's profile information
        return view('admin.profile.show', compact('user'));
    }
    public function update(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'required|string|max:255|unique:users,phone,' . $user->id,
            'address' => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
            'gender' => 'nullable|string|in:male,female,other',
            'profile_picture' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'current_password' => 'nullable|string',
            'new_password' => 'nullable|string|min:8|max:20|confirmed',
        ]);

        if ($request->filled('new_password')) {
            // Kiểm tra mật khẩu hiện tại nếu người dùng có nhập
            if (!$request->filled('current_password') || !Hash::check($request['current_password'], $user->password)) {
                return back()->withErrors(['current_password' => 'The current password is required and must be correct to change the password']);
            }

            // Kiểm tra new password và new password confirm có giống nhau không
            if ($request->new_password !== $request->new_password_confirmation) {
                return back()->withErrors(['new_password' => 'The new password and confirmation do not match']);
            }

            $user->password = $request['new_password'];
        }

        $user->fill(attributes: $request->except(['current_password', 'new_password', 'new_password_confirmation']));

        if ($request->hasFile('profile_picture')) {
            $user->profile_picture = $request->file('profile_picture')->store('profile_pictures', 'public');
        }

        // Đổi mật khẩu nếu được cung cấp
        if ($request->filled('new_password')) {
            $user->password = $request['new_password'];
        }

        $user->save();

        return redirect()->route('admin.profile')->with('success', 'Profile updated successfully');
    }
}
