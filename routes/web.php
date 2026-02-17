<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth', 'role:admin|operator'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
        return view('admin.dashboard');
    })->name('dashboard');

    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class);
    Route::resource('permissions', \App\Http\Controllers\Admin\PermissionController::class);
    Route::post('anggota/validate-step', [\App\Http\Controllers\Admin\AnggotaController::class, 'validateStep'])->name('anggota.validate-step');
    Route::resource('anggota', \App\Http\Controllers\Admin\AnggotaController::class)->parameters([
        'anggota' => 'anggota'
    ]);
    Route::resource('alat-kelengkapan', \App\Http\Controllers\Admin\AlatKelengkapanController::class)->parameters([
        'alat-kelengkapan' => 'alatKelengkapan'
    ]);
    Route::resource('surat-keputusan', \App\Http\Controllers\Admin\SuratKeputusanController::class)->parameters([
        'surat-keputusan' => 'suratKeputusan'
    ]);
    
    // Member Management for SK
    Route::get('surat-keputusan/{id}/anggota', [\App\Http\Controllers\Admin\SuratKeputusanController::class, 'getAnggota'])->name('surat-keputusan.get-anggota');
    Route::post('surat-keputusan/anggota', [\App\Http\Controllers\Admin\SuratKeputusanController::class, 'storeAnggota'])->name('surat-keputusan.store-anggota');
    Route::delete('surat-keputusan/anggota/{id}', [\App\Http\Controllers\Admin\SuratKeputusanController::class, 'destroyAnggota'])->name('surat-keputusan.destroy-anggota');
});

Route::middleware(['auth', 'role:operator|user'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->name('dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class, 'show'])->name('profile.show');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [\App\Http\Controllers\ProfileController::class, 'updatePassword'])->name('profile.password');
});
