<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\EmployeController;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function (){
        return view('dashboard');
    })->name('dashboard');
});
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/employes/creer', [EmployeController::class, 'create'])->name('admin.employes.create');
    Route::post('/admin/employes', [EmployeController::class, 'store'])->name('admin.employes.store');
});