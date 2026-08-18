<?php
// Bootstrap Laravel
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\JabatanAnggota;

$ja = JabatanAnggota::where('id_anggota', 8)->get();
foreach ($ja as $j) {
    echo "SK ID: {$j->id_surat_keputusan}, Jabatan: {$j->id_jabatan_alat_kelengkapan}, Komisi: {$j->nama_komisi}\n";
}
