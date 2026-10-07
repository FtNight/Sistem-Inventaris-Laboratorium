<?php

use App\Http\Controllers\AssetController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DamageReportController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\IncomingGoodsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Sistem Inventaris Laboratorium
|--------------------------------------------------------------------------
*/

// ─── AUTH ROUTES ──────────────────────────────────────────────────────────
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.post');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// ─── PROTECTED ROUTES (require login) ─────────────────────────────────────
Route::middleware(['auth'])->group(function () {

    // Redirect root ke dashboard
    Route::get('/', function () {
        return redirect()->route('dashboard');
    });

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // ─── Master Aset ─────────────────────────────────────────────────────
    Route::resource('assets', AssetController::class);

    // ─── Barang Masuk ────────────────────────────────────────────────────
    Route::get('/incoming', [IncomingGoodsController::class, 'index'])->name('incoming.index');
    Route::get('/incoming/create', [IncomingGoodsController::class, 'create'])->name('incoming.create');
    Route::post('/incoming', [IncomingGoodsController::class, 'store'])->name('incoming.store');
    Route::delete('/incoming/{incoming}', [IncomingGoodsController::class, 'destroy'])->name('incoming.destroy');

    // ─── Laporan Barang Rusak / Hilang ───────────────────────────────────
    Route::get('/damage', [DamageReportController::class, 'index'])->name('damage.index');
    Route::get('/damage/create', [DamageReportController::class, 'create'])->name('damage.create');
    Route::post('/damage', [DamageReportController::class, 'store'])->name('damage.store');
    Route::delete('/damage/{damage}', [DamageReportController::class, 'destroy'])->name('damage.destroy');
});
