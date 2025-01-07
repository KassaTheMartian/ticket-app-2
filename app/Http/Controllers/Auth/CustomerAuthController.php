<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Mail\CustomerVerifyEmail;
use App\Mail\ResetPasswordEmail;
use Biscolab\ReCaptcha\Facades\ReCaptcha;
use Illuminate\Support\Facades\Cookie;

class CustomerAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('customer.auth.login');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        $customer = $this->getCustomerByEmail($request->email);

        if (!$customer) {
            return back()->withErrors(['email' => 'No customer found with that email address.']);
        }

        if (!$customer->email_verified_at) {
            return back()->withErrors(['email' => 'Your email address is not verified.']);
        }

        if (Auth::guard('customer')->attempt($credentials, $remember)) {
            $request->session()->regenerate();
            //return redirect()->intended('/');
            return redirect(session()->pull('url.intended', '/'));
        }

        return back()->withErrors(['password' => 'The provided password is incorrect.']);
    }

    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        if ($request->hasCookie('remember_customer_' . sha1('customer'))) {
            $cookie = Cookie::forget('remember_customer_' . sha1('customer'));
            return redirect()->route('customer.auth.login')->withCookie($cookie);
        }
        
        return redirect()->route('customer.auth.login');
    }

    public function showRegistrationForm()
    {
        return view('customer.auth.register');
    }

    public function register(Request $request)
    {
        $this->validateRegistration($request);

        $customer = Customer::create($request->all());  

        $data = $this->prepareVerificationData($customer, $request->email);

        Mail::to($request->email)->queue(new CustomerVerifyEmail($data));

        return redirect()->route('customer.dashboard');
    }

    public function showForgotPasswordForm()
    {
        return view('customer.auth.password.email');
    }

    protected function sendPasswordResetEmail(Request $request)
    {
        $this->validateForgotPassword($request);

        $email = $request->input('email');
        $customer = $this->getCustomerByEmail($email);

        if (!$customer) {
            return back()->withErrors(['email' => 'No customer found with that email address.']);
        }

        $token = $this->createPasswordResetToken($customer);

        $data = [
            'token' => $token,
            'email' => $customer->email,
            'name' => $customer->name,
        ];
        Mail::to($customer->email)->queue(new ResetPasswordEmail($data));

        return back()->with('status', 'We have emailed your password reset link!');
    }

    public function showResetPasswordForm($token, $email)
    {
        return view('customer.auth.password.reset', ['token' => $token, 'email' => $email]);
    }

    public function resetPassword(Request $request)
    {
        $this->validateResetPassword($request);

        $customer = $this->getCustomerByEmail($request->email);

        if (!$customer) {
            return back()->withErrors(['email' => 'No customer found with that email address.']);
        }

        $passwordReset = $this->getPasswordResetToken($request);

        if (!$passwordReset) {
            return back()->withErrors(['email' => 'Invalid or expired password reset token.']);
        }

        $this->updateCustomerPassword($customer, $request->password);

        DB::table('password_reset_tokens')->where('email', $request->email)->delete();

        return redirect()->route('customer.auth.login')->with('status', 'Your password has been reset!');
    }

    private function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'g-recaptcha-response' => 'recaptcha',
        ]);
    }

    private function validateRegistration(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email|unique:users,email',
            'phone' => 'required|digits_between:10,11|unique:customers,phone|unique:users,phone',
            'password' => 'required|string|min:8|confirmed',
            'g-recaptcha-response' => 'recaptcha',
        ]);
    }

    private function validateForgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'g-recaptcha-response' => 'recaptcha',
        ]);
    }

    private function validateResetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
            'g-recaptcha-response' => 'recaptcha',
        ]);
    }

    private function getCustomerByEmail($email)
    {
        return Customer::where('email', $email)->first();
    }

    private function prepareVerificationData($customer, $email)
    {
        $data = [
            'id' => $customer->id,
            'email' => $email,
            'name' => $customer->name,
            'expire_at' => now()->addDays(7)->timestamp,
        ];

        $hashSecretKey = env('HASH_SECRET_KEY', 'default_secret_key');
        $data['verification_code'] = hash_hmac('sha256', $data['email'] . $data['expire_at'], $hashSecretKey);

        return $data;
    }

    private function createPasswordResetToken($customer)
    {
        $token = Str::random(60);

        DB::table('password_reset_tokens')->where('email', $customer->email)->delete();

        DB::table('password_reset_tokens')->insert([
            'email' => $customer->email,
            'token' => $token,
            'created_at' => now(),
        ]);

        return $token;
    }

    private function getPasswordResetToken(Request $request)
    {
        return DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->where('created_at', '>=', now()->subHours(2))
            ->first();
    }

    private function updateCustomerPassword($customer, $password)
    {
        $customer->password = $password;
        $customer->setRememberToken(Str::random(60));
        $customer->save();
    }
}