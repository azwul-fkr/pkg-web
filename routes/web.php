<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ================= PUBLIC =================
Route::get('/', function () {
    return view('auth.login');
});

// ================= AUTH (BREEZE) =================
require __DIR__ . '/auth.php';


// ================= ADMIN =================
Route::middleware(['auth', 'role:admin'])->prefix('admin')->group(function () {

    Route::get('/dashboard', function () {
        return view('admin.dashboard'); // nanti bisa kamu buat blade-nya
    })->name('admin.dashboard');
});


// ================= GURU =================
Route::middleware(['auth', 'role:guru'])->prefix('guru')->group(function () {

    Route::get('/dashboard', function () {return view('guru.dashboard');})->name('guru.dashboard');

    Route::get('/evidence', [App\Http\Controllers\EvidenceController::class, 'index'])->name('guru.evidence.index');

    Route::get('/evidence/create', [App\Http\Controllers\EvidenceController::class, 'create'])->name('guru.evidence.create');

    Route::post('/evidence', [App\Http\Controllers\EvidenceController::class, 'store'])->name('guru.evidence.store');
});


// ================= PENILAI =================
Route::middleware(['auth', 'role:penilai'])->prefix('penilai')->group(function () {

    Route::get('/dashboard', function () {
        return view('penilai.dashboard');
    })->name('penilai.dashboard');
});


// ================= PROFILE (BREEZE DEFAULT) =================
Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});
