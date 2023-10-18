<?php

use Illuminate\Support\Facades\Route;

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

Route::get('/', function () {
    return redirect()->route("dashboard");
});

Route::prefix('/dashboard')->middleware([])->group(function () {

    Route::get('/', function () {
        return redirect()->route("dashboard.products");
    })->name('dashboard');

    Route::get('/products', function () {
        return view('Dashboard.products');
    })->name('dashboard.products');

    Route::get('/users', function () {
        return view('Dashboard.users');
    })->name('dashboard.users');
});

Route::get('/login', function () {
    return view('login');
})->name('login');

Route::get('/register', function () {
    return view('register');
})->name('register');

Route::get('/logout', function () {
    return view('logout');
})->name('logout');