<?php

namespace App\Http\Controllers\Auth;;
use App\Http\Controllers\Controller;

use App\Models\Customer;

class CustomerVerificationController extends Controller
{
    public function verify($id, $verification_code, $expire_at)
    {
        $user = Customer::where('id', $id)->first();
        $hashSecretKey = env('HASH_SECRET_KEY', 'default_secret_key');
        $hash = hash_hmac('sha256', $user->email . $expire_at, $hashSecretKey);

        if ($user->email_verified_at) {
            return redirect()->route('customer.auth.verify_customer')->with('success', 'Your email is already verified.');
        }

        if ($verification_code === $hash && now()->timestamp < $expire_at) {
            $user->email_verified_at = now();
            $user->save();

            return redirect()->route('customer.auth.verify_customer')->with('success', 'Your email has been verified! You can now log in.');
        }

        return redirect()->route('customer.auth.verify_customer')->with('message', 'Invalid verification token.');
    }

    public function showVerificationForm()
    {
        return view('customer.auth.verify_customer');
    }
}
