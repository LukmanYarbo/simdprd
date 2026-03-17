<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\LoginController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('login', [LoginController::class , 'showLoginForm'])->name('login');
Route::post('login', [LoginController::class , 'login']);
Route::post('logout', [LoginController::class , 'logout'])->name('logout');
Route::get('register', [\App\Http\Controllers\Auth\RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('register', [\App\Http\Controllers\Auth\RegisterController::class, 'register'])->name('register');

Route::middleware(['auth', 'role:admin|operator'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');

        Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
        Route::resource('roles', \App\Http\Controllers\Admin\RoleController::class);
        Route::resource('permissions', \App\Http\Controllers\Admin\PermissionController::class);
        Route::post('anggota/validate-step', [\App\Http\Controllers\Admin\AnggotaController::class , 'validateStep'])->name('anggota.validate-step');
        Route::get('anggota/{id}/print', [\App\Http\Controllers\Admin\AnggotaController::class , 'print'])->name('anggota.print');
        Route::resource('anggota', \App\Http\Controllers\Admin\AnggotaController::class)->parameters([
            'anggota' => 'anggota'
        ]);
        Route::get('anggota-status', [\App\Http\Controllers\Admin\AnggotaController::class, 'status'])->name('anggota-status.index');
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

        Route::get('jabatan-asn/search-by-skpd', [\App\Http\Controllers\Admin\JabatanAsnController::class, 'searchBySkpd'])->name('jabatan-asn.search-by-skpd');
        Route::resource('jabatan-asn', \App\Http\Controllers\Admin\JabatanAsnController::class);
        Route::resource('pegawai-asn', \App\Http\Controllers\Admin\PegawaiAsnController::class);
        Route::resource('skpd', \App\Http\Controllers\Admin\SkpdController::class);
        Route::get('tunjangan', [\App\Http\Controllers\Admin\TunjanganController::class, 'index'])->name('tunjangan.index');
        
        // Pemda Module
        Route::get('pemda/pegawai-details/{id}', [\App\Http\Controllers\Admin\PemdaController::class, 'getPegawaiDetails'])->name('pemda.pegawai-details');
        Route::resource('pemda', \App\Http\Controllers\Admin\PemdaController::class);
        Route::get('surat-tugas/{id}/print', [\App\Http\Controllers\Admin\SuratTugasController::class, 'print'])->name('surat-tugas.print');
        Route::resource('surat-tugas', \App\Http\Controllers\Admin\SuratTugasController::class);

        // Penanda Tangan
        Route::get('penanda-tangan/search-skpd', [\App\Http\Controllers\Admin\PenandaTanganController::class, 'searchSkpd'])->name('penanda-tangan.search-skpd');
        Route::get('penanda-tangan/search-asn', [\App\Http\Controllers\Admin\PenandaTanganController::class, 'searchAsn'])->name('penanda-tangan.search-asn');
        Route::resource('penanda-tangan', \App\Http\Controllers\Admin\PenandaTanganController::class)->parameters([
            'penanda-tangan' => 'penandaTangan'
        ]);

        // Parameter Gaji
        Route::resource('parameter-gaji', \App\Http\Controllers\Admin\ParameterGajiController::class)->parameters([
            'parameter-gaji' => 'parameterGaji'
        ]);

        // Tarif Pajak
        Route::resource('tarif-pajak', \App\Http\Controllers\Admin\TarifPajakController::class)->parameters([
            'tarif-pajak' => 'tarifPajak'
        ]);

        // Potongan
        Route::resource('potongan', \App\Http\Controllers\Admin\PotonganController::class);

        // Proses Gaji
        Route::get('transaksi-gaji/slip-gaji/{id}', [\App\Http\Controllers\Admin\Gaji\DsbGajiController::class, 'slipGaji'])->name('transaksi-gaji.slip-gaji');
        Route::get('transaksi-gaji/dsb-report', [\App\Http\Controllers\Admin\Gaji\DsbGajiController::class, 'report'])->name('transaksi-gaji.dsb-report');
        Route::get('transaksi-gaji/daftar-gaji', [\App\Http\Controllers\Admin\Gaji\DsbGajiController::class, 'daftarGaji'])->name('transaksi-gaji.daftar-gaji');
        Route::get('transaksi-gaji/tunjangan-report', [\App\Http\Controllers\Admin\Gaji\DsbGajiController::class, 'tunjanganReport'])->name('transaksi-gaji.tunjangan-report');
        Route::get('transaksi-gaji/export-excel', [\App\Http\Controllers\Admin\Gaji\DsbGajiController::class, 'exportExcel'])->name('transaksi-gaji.export-excel');
        Route::get('transaksi-gaji', [\App\Http\Controllers\Admin\TransaksiGajiController::class, 'index'])->name('transaksi-gaji.index');
    });

Route::middleware(['auth', 'role:operator|user'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class , 'index'])->name('dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class , 'show'])->name('profile.show');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class , 'update'])->name('profile.update');
    Route::put('/profile/password', [\App\Http\Controllers\ProfileController::class , 'updatePassword'])->name('profile.password');
});
