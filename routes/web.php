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
    
    Route::get('/storage-link', function () {
        try {
            \Illuminate\Support\Facades\Artisan::call('storage:link');
            return back()->with('success', 'Storage link berhasil dibuat!');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat storage link: ' . $e->getMessage());
        }
    })->name('storage-link');

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
        Route::get('surat-keputusan/struktur', [\App\Http\Controllers\Admin\SuratKeputusanController::class , 'strukturAll'])->name('surat-keputusan.struktur-all');
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
        });

        // Harta Anggota Modal API
        Route::controller(\App\Http\Controllers\Admin\HartaAnggotaController::class)->group(function () {
            Route::get('anggota/{id}/harta', 'index')->name('harta.index');
            Route::post('anggota/{id}/harta', 'store')->name('harta.store');
            Route::get('harta/{id}/edit', 'edit')->name('harta.edit');
            Route::put('harta/{id}', 'update')->name('harta.update');
            Route::delete('harta/{id}', 'destroy')->name('harta.destroy');
        });

        Route::get('jabatan-asn/search-by-skpd', [\App\Http\Controllers\Admin\JabatanAsnController::class, 'searchBySkpd'])->name('jabatan-asn.search-by-skpd');
        Route::resource('jabatan-asn', \App\Http\Controllers\Admin\JabatanAsnController::class);
        Route::resource('pegawai-asn', \App\Http\Controllers\Admin\PegawaiAsnController::class);
        Route::resource('skpd', \App\Http\Controllers\Admin\SkpdController::class);
        Route::get('tunjangan', [\App\Http\Controllers\Admin\TunjanganController::class, 'index'])->name('tunjangan.index');
        
        // Pemda Module
        Route::get('pemda/pegawai-details/{id}', [\App\Http\Controllers\Admin\PemdaController::class, 'getPegawaiDetails'])->name('pemda.pegawai-details');
        Route::resource('pemda', \App\Http\Controllers\Admin\PemdaController::class);

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
        Route::get('transaksi-gaji/slip-gaji-bulk', [\App\Http\Controllers\Admin\Gaji\DsbGajiController::class, 'slipGajiBulk'])->name('transaksi-gaji.slip-gaji-bulk');
        Route::get('transaksi-gaji/slip-gaji/{id}', [\App\Http\Controllers\Admin\Gaji\DsbGajiController::class, 'slipGaji'])->name('transaksi-gaji.slip-gaji');
        Route::get('transaksi-gaji/dsb-report', [\App\Http\Controllers\Admin\Gaji\DsbGajiController::class, 'report'])->name('transaksi-gaji.dsb-report');
        Route::get('transaksi-gaji/daftar-gaji', [\App\Http\Controllers\Admin\Gaji\DsbGajiController::class, 'daftarGaji'])->name('transaksi-gaji.daftar-gaji');
        Route::get('transaksi-gaji/tunjangan-report', [\App\Http\Controllers\Admin\Gaji\DsbGajiController::class, 'tunjanganReport'])->name('transaksi-gaji.tunjangan-report');
        Route::get('transaksi-gaji/export-excel', [\App\Http\Controllers\Admin\Gaji\DsbGajiController::class, 'exportExcel'])->name('transaksi-gaji.export-excel');
        Route::get('transaksi-gaji', [\App\Http\Controllers\Admin\TransaksiGajiController::class, 'index'])->name('transaksi-gaji.index');

        // Form 1721-A2 PPh 21
        Route::get('pph21-a2', [\App\Http\Controllers\Admin\Pajak\Pph21A2Controller::class, 'index'])->name('pph21-a2.index');
        Route::get('pph21-a2/print/{id_anggota}', [\App\Http\Controllers\Admin\Pajak\Pph21A2Controller::class, 'print'])->name('pph21-a2.print');
        Route::get('pph21-a2/print-bulk', [\App\Http\Controllers\Admin\Pajak\Pph21A2Controller::class, 'printBulk'])->name('pph21-a2.print-bulk');

        // Anggaran & LRA
        Route::get('anggaran', [\App\Http\Controllers\Admin\AnggaranController::class, 'index'])->name('anggaran.index');
        Route::get('anggaran/form/{id?}', [\App\Http\Controllers\Admin\AnggaranController::class, 'form'])->name('anggaran.form');
        Route::get('jurnal-lra/print-realisasi', [\App\Http\Controllers\Admin\AnggaranController::class, 'printRealisasi'])->name('jurnal-lra.print-realisasi');
        Route::get('jurnal-lra/print-bku', [\App\Http\Controllers\Admin\AnggaranController::class, 'printBku'])->name('jurnal-lra.print-bku');
        Route::get('jurnal-lra', [\App\Http\Controllers\Admin\AnggaranController::class, 'jurnalIndex'])->name('jurnal-lra.index');

        // Kertas Kerja
        Route::get('kertas-kerja', [\App\Http\Controllers\Admin\KertasKerjaController::class, 'index'])->name('kertas-kerja.index');
        Route::get('kertas-kerja/form/{id?}', [\App\Http\Controllers\Admin\KertasKerjaController::class, 'form'])->name('kertas-kerja.form');
        Route::get('kertas-kerja/print/{id}', [\App\Http\Controllers\Admin\KertasKerjaController::class, 'print'])->name('kertas-kerja.print');

        // Database Management
        Route::get('database/backup', [\App\Http\Controllers\Admin\DatabaseController::class, 'backupIndex'])->name('database.backup');
        Route::post('database/backup', [\App\Http\Controllers\Admin\DatabaseController::class, 'createBackup'])->name('database.backup-create');
        Route::get('database/backup/download/{file}', [\App\Http\Controllers\Admin\DatabaseController::class, 'downloadBackup'])->name('database.backup-download');
        Route::delete('database/backup/{file}', [\App\Http\Controllers\Admin\DatabaseController::class, 'deleteBackup'])->name('database.backup-delete');
        Route::get('database/restore', [\App\Http\Controllers\Admin\DatabaseController::class, 'restoreIndex'])->name('database.restore');
        Route::post('database/restore', [\App\Http\Controllers\Admin\DatabaseController::class, 'restore'])->name('database.restore-execute');
        Route::get('database/kosongkan', [\App\Http\Controllers\Admin\DatabaseController::class, 'truncateIndex'])->name('database.truncate');
        Route::post('database/kosongkan', [\App\Http\Controllers\Admin\DatabaseController::class, 'truncate'])->name('database.truncate-execute');
        Route::post('database/seed', [\App\Http\Controllers\Admin\DatabaseController::class, 'seed'])->name('database.seed');
    });

Route::middleware(['auth', 'role:operator|user'])->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class , 'index'])->name('dashboard');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [\App\Http\Controllers\ProfileController::class , 'show'])->name('profile.show');
    Route::put('/profile', [\App\Http\Controllers\ProfileController::class , 'update'])->name('profile.update');
    Route::put('/profile/password', [\App\Http\Controllers\ProfileController::class , 'updatePassword'])->name('profile.password');
});
