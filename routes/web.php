<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('login', [LoginController::class , 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class , 'login']);
Route::post('logout', [LoginController::class , 'logout'])->name('logout');
Route::post('register', [\App\Http\Controllers\Auth\RegisterController::class, 'register'])->name('register');

Route::middleware(['auth', 'role:admin|operator'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', function () {
            return view('admin.dashboard');
        }
        )->name('dashboard');

        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
        Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class);
        Route::resource('permissions', \App\Http\Controllers\Admin\PermissionController::class);
        Route::post('anggota/validate-step', [\App\Http\Controllers\Admin\AnggotaController::class , 'validateStep'])->name('anggota.validate-step');
        Route::resource('anggota', \App\Http\Controllers\Admin\AnggotaController::class)->parameters([
            'anggota' => 'anggota'
        ]);
        Route::resource('alat-kelengkapan', \App\Http\Controllers\Admin\AlatKelengkapanController::class)->parameters([
            'alat-kelengkapan' => 'alatKelengkapan'
        ]);
        Route::get('surat-keputusan/{suratKeputusan}/print', [\App\Http\Controllers\Admin\SuratKeputusanController::class , 'print'])->name('surat-keputusan.print');
        Route::resource('surat-keputusan', \App\Http\Controllers\Admin\SuratKeputusanController::class)->parameters([
            'surat-keputusan' => 'suratKeputusan'
        ]);

        // Member Management for SK
        Route::get('surat-keputusan/{id}/anggota', [\App\Http\Controllers\Admin\SuratKeputusanController::class , 'getAnggota'])->name('surat-keputusan.get-anggota');
        Route::post('surat-keputusan/anggota', [\App\Http\Controllers\Admin\SuratKeputusanController::class , 'storeAnggota'])->name('surat-keputusan.store-anggota');
        Route::delete('surat-keputusan/anggota/{id}', [\App\Http\Controllers\Admin\SuratKeputusanController::class , 'destroyAnggota'])->name('surat-keputusan.destroy-anggota');

        // Keluarga Management
        Route::get('anggota/{id}/keluarga', [\App\Http\Controllers\Admin\KeluargaController::class , 'index'])->name('keluarga.index');
        Route::post('keluarga', [\App\Http\Controllers\Admin\KeluargaController::class , 'store'])->name('keluarga.store');
        Route::get('keluarga/{id}/edit', [\App\Http\Controllers\Admin\KeluargaController::class , 'edit'])->name('keluarga.edit');
        Route::put('keluarga/{id}', [\App\Http\Controllers\Admin\KeluargaController::class , 'update'])->name('keluarga.update');
        Route::delete('keluarga/{id}', [\App\Http\Controllers\Admin\KeluargaController::class , 'destroy'])->name('keluarga.destroy');
        // Pendidikan Management
        Route::controller(\App\Http\Controllers\Admin\PendidikanAnggotaController::class)->group(function () {
            Route::get('anggota/{id}/pendidikan', 'index')->name('pendidikan.index');
            Route::get('anggota/{id}/pendidikan/create', 'create')->name('pendidikan.create');
            Route::post('anggota/{id}/pendidikan', 'store')->name('pendidikan.store');
            Route::get('pendidikan/{id}/edit', 'edit')->name('pendidikan.edit');
            Route::put('pendidikan/{id}', 'update')->name('pendidikan.update');
            Route::delete('pendidikan/{id}', 'destroy')->name('pendidikan.destroy');
        }
        );

        Route::resource('jabatan-asn', \App\Http\Controllers\Admin\JabatanAsnController::class);
        Route::resource('pegawai-asn', \App\Http\Controllers\Admin\PegawaiAsnController::class);
        Route::resource('skpd', \App\Http\Controllers\Admin\SkpdController::class);
        Route::get('tunjangan', [\App\Http\Controllers\Admin\TunjanganController::class, 'index'])->name('tunjangan.index');
        
        // Pemda Module
        Route::get('pemda/pegawai-details/{id}', [\App\Http\Controllers\Admin\PemdaController::class, 'getPegawaiDetails'])->name('pemda.pegawai-details');
        Route::resource('pemda', \App\Http\Controllers\Admin\PemdaController::class);
    });

Route::middleware(['auth', 'role:operator|user'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class , 'index'])->name('dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class , 'show'])->name('profile.show');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class , 'update'])->name('profile.update');
    Route::put('/profile/password', [\App\Http\Controllers\ProfileController::class , 'updatePassword'])->name('profile.password');
});
