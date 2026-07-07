<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\EmployeController;
use App\Http\Controllers\Admin\ProduitController;
use App\Http\Controllers\Admin\CommandeController;
use App\Http\Controllers\Admin\VenteMagasinController;
use App\Http\Controllers\Admin\ClientController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Admin\ProfilController;

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
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
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/produits', [ProduitController::class, 'index'])->name('admin.produits.index');
    Route::get('/admin/produits/creer', [ProduitController::class, 'create'])->name('admin.produits.create');
    Route::post('/admin/produits', [ProduitController::class, 'store'])->name('admin.produits.store');
    Route::get('/admin/produits/{produit}/modifier', [ProduitController::class, 'edit'])->name('admin.produits.edit');
    Route::put('/admin/produits/{produit}', [ProduitController::class, 'update'])->name('admin.produits.update');
    Route::delete('/admin/produits/{produit}', [ProduitController::class, 'destroy'])->name('admin.produits.destroy');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/commandes', [CommandeController::class, 'index'])->name('admin.commandes.index');
    Route::get('/admin/commandes/{commande}', [CommandeController::class, 'show'])->name('admin.commandes.show');
    Route::put('/admin/commandes/{commande}/statut', [CommandeController::class, 'updateStatut'])->name('admin.commandes.statut');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/ventes/creer', [VenteMagasinController::class, 'create'])->name('admin.ventes.create');
    Route::post('/admin/ventes', [VenteMagasinController::class, 'store'])->name('admin.ventes.store');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/clients', [ClientController::class, 'index'])->name('admin.clients.index');
    Route::get('/admin/clients/{client}', [ClientController::class, 'show'])->name('admin.clients.show');
});
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/admin/profil', [ProfilController::class, 'edit'])->name('admin.profil.edit');
    Route::put('/admin/profil/infos', [ProfilController::class, 'updateInfos'])->name('admin.profil.infos');
    Route::put('/admin/profil/mot-de-passe', [ProfilController::class, 'updateMotDePasse'])->name('admin.profil.motdepasse');
});