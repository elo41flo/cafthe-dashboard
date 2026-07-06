<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\EmployeController;
use App\Http\Controllers\Admin\ProduitController;

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
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/produits', [ProduitController::class, 'index'])->name('admin.produits.index');
    Route::get('/admin/produits/creer', [ProduitController::class, 'create'])->name('admin.produits.create');
    Route::post('/admin/produits', [ProduitController::class, 'store'])->name('admin.produits.store');
    Route::get('/admin/produits/{produit}/modifier', [ProduitController::class, 'edit'])->name('admin.produits.edit');
    Route::put('/admin/produits/{produit}', [ProduitController::class, 'update'])->name('admin.produits.update');
    Route::delete('/admin/produits/{produit}', [ProduitController::class, 'destroy'])->name('admin.produits.destroy');
});