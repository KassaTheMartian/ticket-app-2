<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\TicketTypeController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\Auth\UserAuthController;
use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\Auth\CustomerVerificationController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\CustomerProfileController;
use App\Http\Controllers\CustomerTicketController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\AdminDashboardController;
use App\Http\Controllers\CustomerHomeController;
use App\Http\Controllers\CustomerMessageController;
use App\Http\Controllers\EmailSettingController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('error', function () {
    return view('error');
})->name('error');

Route::prefix('admin')->group(function () {
    Route::get('/', function () {
        return redirect()->route('admin.auth.login');
    });
    Route::post('auth/logout', [UserAuthController::class, 'logout'])->name('admin.auth.logout');

    Route::prefix('auth')->middleware('guest')->group(function () {
        Route::get('login', [UserAuthController::class, 'showLoginForm'])->name('admin.auth.login');
        Route::post('login', [UserAuthController::class, 'login'])->name('admin.auth.login.post');
        
        Route::get('password/email', [UserAuthController::class, 'showForgotPasswordForm'])->name('admin.auth.password.email');
        Route::post('password/email', [UserAuthController::class, 'sendPasswordResetEmail'])->name('admin.auth.password.email.post');
        Route::get('password/reset/{token}/{email}', [UserAuthController::class, 'showResetPasswordForm'])->name('admin.auth.password.reset');
        Route::post('password/reset', [UserAuthController::class, 'resetPassword'])->name('admin.auth.password.update');
    });

    Route::middleware('auth')->group(function () {
        Route::get('dashboard', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
        
        Route::prefix('users')->group(function () {
            Route::get('/', [UserController::class, 'index'])->name('admin.users.index');
            Route::get('/create', [UserController::class, 'create'])->name('admin.users.create');
            Route::post('/', [UserController::class, 'store'])->name('admin.users.store');
            Route::get('/{user}/edit', [UserController::class, 'edit'])->name('admin.users.edit');
            Route::put('/{user}', [UserController::class, 'update'])->name('admin.users.update');
            Route::delete('/{user}', [UserController::class, 'destroy'])->name('admin.users.destroy');
        });
        Route::prefix('customers')->group(function () {
            Route::get('/', [CustomerController::class, 'index'])->name('admin.customers.index');
            Route::get('/create', [CustomerController::class, 'create'])->name('admin.customers.create');
            Route::post('/', [CustomerController::class, 'store'])->name('admin.customers.store');
            Route::get('/{customer}/edit', [CustomerController::class, 'edit'])->name('admin.customers.edit');
            Route::put('/{customer}', [CustomerController::class, 'update'])->name('admin.customers.update');
            Route::delete('/{customer}', [CustomerController::class, 'destroy'])->name('admin.customers.destroy');
        });

        Route::prefix('departments')->group(function () {
            Route::get('/', [DepartmentController::class, 'index'])->name('admin.departments.index');
            Route::get('/create', [DepartmentController::class, 'create'])->name('admin.departments.create');
            Route::post('/', [DepartmentController::class, 'store'])->name('admin.departments.store');
            Route::get('/{department}/edit', [DepartmentController::class, 'edit'])->name('admin.departments.edit');
            Route::put('/{department}', [DepartmentController::class, 'update'])->name('admin.departments.update');
            Route::delete('/{department}', [DepartmentController::class, 'destroy'])->name('admin.departments.destroy');
        });

        Route::prefix('ticket_types')->group(function () {
            Route::get('/', [TicketTypeController::class, 'index'])->name('admin.ticket_types.index');
            Route::get('/create', [TicketTypeController::class, 'create'])->name('admin.ticket_types.create');
            Route::post('/', [TicketTypeController::class, 'store'])->name('admin.ticket_types.store');
            Route::get('/{ticket_type}/edit', [TicketTypeController::class, 'edit'])->name('admin.ticket_types.edit');
            Route::put('/{ticket_type}', [TicketTypeController::class, 'update'])->name('admin.ticket_types.update');
            Route::delete('/{ticket_type}', [TicketTypeController::class, 'destroy'])->name('admin.ticket_types.destroy');
        });

        Route::prefix('tickets')->group(function () {
            Route::get('/', [TicketController::class, 'index'])->name('admin.tickets.index');
                Route::get('/create', [TicketController::class, 'create'])->name('admin.tickets.create');
                Route::post('/', [TicketController::class, 'store'])->name('admin.tickets.store');
                Route::middleware('check.department')->group(function () {
                Route::get('/{ticket}', [TicketController::class, 'edit'])->name('admin.tickets.edit');
                Route::put('/{ticket}', [TicketController::class, 'update'])->name('admin.tickets.update');
                Route::delete('/{ticket}', [TicketController::class, 'destroy'])->name('admin.tickets.destroy');
            });
        });

        Route::prefix('articles')->group(function () {
            Route::get('/', [ArticleController::class, 'index'])->name('admin.articles.index');
            Route::get('/create', [ArticleController::class, 'create'])->name('admin.articles.create');
            Route::post('/', [ArticleController::class, 'store'])->name('admin.articles.store');
            Route::get('/{article}/edit', [ArticleController::class, 'edit'])->name('admin.articles.edit');
            Route::put('/{article}', [ArticleController::class, 'update'])->name('admin.articles.update');
            Route::delete('/{article}', [ArticleController::class, 'destroy'])->name('admin.articles.destroy');
        });

        Route::prefix('roles')->group(function () {
            Route::get('/', [RoleController::class, 'index'])->name('admin.roles.index');
            Route::get('/create', [RoleController::class, 'create'])->name('admin.roles.create');
            Route::post('/', [RoleController::class, 'store'])->name('admin.roles.store');
            Route::get('/{role}/edit', [RoleController::class, 'edit'])->name('admin.roles.edit');
            Route::put('/{role}', [RoleController::class, 'update'])->name('admin.roles.update');
            Route::delete('/{role}', [RoleController::class, 'destroy'])->name('admin.roles.destroy');
        });

        Route::prefix('messages')->group(function () {
            Route::post('/create', [MessageController::class, 'create'])->name('admin.messages.create');
        });

        Route::prefix('profile')->group(function () {
            Route::get('/', [UserProfileController::class, 'show'])->name('admin.profile');
            Route::put('/', [UserProfileController::class, 'update'])->name('admin.profile.update');
        });

        Route::prefix('email-settings')->group(function () {
            Route::get('/', [EmailSettingController::class, 'edit'])->name('email.settings.edit');
            Route::post('/', [EmailSettingController::class, 'update'])->name('email.settings.update');
            Route::post('/send-test-email', [EmailSettingController::class, 'sendTestEmail'])->name('email.settings.sendTestEmail');
        });

    });
});


Route::get('/', [CustomerHomeController::class, 'dashboard'])->name('customer.dashboard');

Route::prefix('customer')->group(function () {
    Route::prefix('auth')->middleware('guest:customer')->group(function () {
        Route::get('login', [CustomerAuthController::class, 'showLoginForm'])->name('customer.auth.login');
        Route::post('login', [CustomerAuthController::class, 'login'])->name('customer.auth.login.post');
        Route::get('register', [CustomerAuthController::class, 'showRegistrationForm'])->name('customer.auth.register');
        Route::post('register', [CustomerAuthController::class, 'register'])->name('customer.auth.register.post');
        Route::get('verify/{id}/{verification_code}/{expire_at}', [CustomerVerificationController::class, 'verify'])->name('customer.auth.verify');
        Route::get('verify', [CustomerVerificationController::class, 'showVerificationForm'])->name('customer.auth.verify_customer');

        Route::get('password/email', [CustomerAuthController::class, 'showForgotPasswordForm'])->name('customer.auth.password.email');
        Route::post('password/email', [CustomerAuthController::class, 'sendPasswordResetEmail'])->name('customer.auth.password.email.post');
        Route::get('password/reset/{token}/{email}', [CustomerAuthController::class, 'showResetPasswordForm'])->name('customer.auth.password.reset');
        Route::post('password/reset', [CustomerAuthController::class, 'resetPassword'])->name('customer.auth.password.update');
    });


    Route::middleware('auth:customer')->group(function () {
        Route::post('logout', [CustomerAuthController::class, 'logout'])->name('customer.auth.logout');
        
        Route::prefix('profile')->group(function () {
            Route::get('/', [CustomerProfileController::class, 'show'])->name('customer.profile');
            Route::put('/', [CustomerProfileController::class, 'update'])->name('customer.profile.update');
        });
        Route::prefix('tickets')->group(function () {
            Route::get('/create', [CustomerTicketController::class, 'create'])->name('customer.tickets.create');
            Route::get('/', [CustomerTicketController::class, 'index'])->name('customer.tickets.index');
            Route::post('/', [CustomerTicketController::class, 'store'])->name('customer.tickets.store');
            Route::get('/{id}', [CustomerTicketController::class, 'show'])->name('customer.tickets.show')->middleware('check.ticket.ownership');
        });
        
        Route::post('/messages', [CustomerMessageController::class, 'create'])->name('customer.messages.send');

        Route::get('attachments/{id}', [AttachmentController::class, 'show'])->name('attachments.show');
    });
});

Route::prefix('attachments')->middleware(['auth.multi'])->group(function () {
    Route::delete('/{attachment}', [AttachmentController::class, 'destroy'])->name('admin.attachments.destroy');
    Route::get('/{id}', [AttachmentController::class, 'show'])->name(name: 'admin.attachments.show');
});

Route::post('attachments/upload', [AttachmentController::class, 'upload'])->name('attachments.upload')->middleware('auth.multi');

Route::get('articles/{id}', [ArticleController::class, 'show'])->name('articles.show');