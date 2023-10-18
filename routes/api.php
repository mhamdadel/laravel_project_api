<?php

use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\RedirectIfAuthenticated;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('web')->get('/token', function () {
    return csrf_token();
});

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function () {
    Route::post('register/with_email', [AuthController::class, 'register_with_email']);
    Route::post('register/with_phone', [AuthController::class, 'register_with_phone']);
    Route::post('login', [AuthController::class, 'login']);
    Route::get('profile', [AuthController::class, 'profile']);
    Route::post('logout', [AuthController::class, 'logout']);
    Route::post('active', [AuthController::class, 'active']);
});

Route::middleware(RedirectIfAuthenticated::class)->post('/user/reset_password/{token}', [UserController::class, 'resetPassword'])->name('users.reset_password');
Route::middleware(RedirectIfAuthenticated::class)->post('/user/forget_password', [UserController::class, 'forgetPassword'])->name('users.forget_password');


Route::middleware('auth:api')->group(function () {
    // User routes
    Route::get('/user/profile', [UserController::class, 'profile']);
    Route::put('/user/profile/update', [UserController::class, 'updateProfile']);
    Route::post('/user/profile/password', [UserController::class, 'updatePassword']);
    Route::get('/user/products', [UserController::class, 'getUserProducts'])->name('users.products');


    Route::prefix('dashboard')->middleware(IsAdmin::class)->group(function () {
        Route::prefix('users')->group(function () {
            Route::get('/', [UserController::class, 'index']);
            Route::post('/', [UserController::class, 'createUserByAdmin']);
            Route::get('/{user}', [UserController::class, 'show']);
            Route::get('/{user}/products', [UserController::class, 'showProducts']);
            Route::delete('/{user}', [UserController::class, 'destory']);
            Route::put('/{user}', [UserController::class, 'updateUser']);
            Route::put('/{user}/password', [UserController::class, 'updatePasswordByAdmin']);
        });
    });

    // Product routes
    Route::prefix('products')->group(function () {
        Route::get('/', [ProductController::class, 'index'])->name('products.index');
        Route::post('/', [ProductController::class, 'store'])->name('products.store');
        Route::post('/{product}/edit', [ProductController::class, 'updateProduct'])->name('products.update');
        Route::delete('/{product}', [ProductController::class, 'destroy'])->name('products.destroy');
        Route::patch('/assign', [ProductController::class, 'assignProductToUser'])->name('products.assign');
    });

    // Route::resource('products', ProductController::class)->except('show');
    // Route::post('/products/{product}/assign', [ProductController::class, 'assignProductToUser']);
});