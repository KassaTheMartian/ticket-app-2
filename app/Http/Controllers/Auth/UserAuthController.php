<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\AdminResetPasswordEmail;
use Illuminate\Support\Facades\DB;

class UserAuthController extends Controller
{
    public function showLoginForm()
    {
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        if ($this->attemptLogin($request)) {
            $request->session()->regenerate();
            // return redirect()->route('admin.dashboard');
            return redirect(session()->pull('url.intended', 'admin/dashboard'));

        }

        return $this->sendFailedLoginResponse();
    }

    protected function validateLogin(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
            'g-recaptcha-response' => 'recaptcha',
        ]);
    }

    protected function attemptLogin(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        return Auth::attempt($credentials, $remember);
    }

    protected function sendFailedLoginResponse()
    {
        return redirect()->route('admin.auth.login')->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return $this->clearRememberMeCookie($request);
    }

    protected function clearRememberMeCookie(Request $request)
    {
        if ($request->hasCookie('remember_web')) {
            $cookie = \Cookie::forget('remember_web');
            return redirect()->route('admin.auth.login')->withCookie($cookie);
        }
        
        return redirect()->route('admin.auth.login');
    }

    public function showForgotPasswordForm()
    {
        return view('admin.auth.password.email');
    }

    public function sendPasswordResetEmail(Request $request)
    {
        $this->validateForgotPassword($request);

        $user = $this->getUserByEmail($request->input('email'));

        if (!$user) {
            return back()->withErrors(['email' => 'No user found with that email address.']);
        }

        $token = $this->createPasswordResetToken($user);

        $this->sendResetEmail($user, $token);

        return back()->with('status', 'We have emailed your password reset link!');
    }

    protected function validateForgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'g-recaptcha-response' => 'recaptcha',
        ]);
    }

    protected function getUserByEmail($email)
    {
        return User::where('email', $email)->first();
    }

    protected function createPasswordResetToken($user)
    {
        $token = Str::random(60);

        DB::table('password_reset_tokens')->where('email', $user->email)->delete();

        DB::table('password_reset_tokens')->insert([
            'email' => $user->email,
            'token' => $token,
            'created_at' => now(),
        ]);

        return $token;
    }

    protected function sendResetEmail($user, $token)
    {
        $data = [
            'token' => $token,
            'email' => $user->email,
            'name' => $user->name,
        ];
        Mail::to($user->email)->queue(new AdminResetPasswordEmail($data));
    }

    public function showResetPasswordForm($token, $email)
    {
        return view('admin.auth.password.reset', ['token' => $token, 'email' => $email]);
    }

    public function resetPassword(Request $request)
    {
        $this->validateResetPassword($request);

        $user = $this->getUserByEmail($request->email);

        if (!$user) {
            return back()->withErrors(['email' => 'No user found with that email address.']);
        }

        if (!$this->isValidPasswordResetToken($request)) {
            return back()->withErrors(['email' => 'Invalid or expired password reset token.']);
        }

        $this->resetUserPassword($user, $request->password);

        return redirect()->route('admin.auth.login')->with('status', 'Your password has been reset!');
    }

    protected function validateResetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
            'g-recaptcha-response' => 'recaptcha',
        ]);
    }

    protected function isValidPasswordResetToken(Request $request)
    {
        return DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->where('token', $request->token)
            ->where('created_at', '>=', now()->subHours(2))
            ->exists();
    }

    protected function resetUserPassword($user, $password)
    {
        $user->password = $password;
        $user->setRememberToken(Str::random(60));
        $user->save();

        DB::table('password_reset_tokens')->where('email', $user->email)->delete();
    }
}
