<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\PegawaiAsn;

echo "--- Searching for Sekretaris or Bendahara in PegawaiAsn ---\n";
$pegawai = PegawaiAsn::with(['jabatanAsn', 'pangkatGolongan'])->get();
foreach ($pegawai as $p) {
    if (str_contains(strtolower($p->nama), 'sekretaris') || 
        str_contains(strtolower($p->nama), 'bendahara') ||
        str_contains(strtolower($p->jabatanAsn->nama_jabatan ?? ''), 'sekretaris') ||
        str_contains(strtolower($p->jabatanAsn->nama_jabatan ?? ''), 'bendahara') ||
        str_contains(strtolower($p->ket_jabatan), 'sekretaris') ||
        str_contains(strtolower($p->ket_jabatan), 'bendahara')) {
        echo "ID: {$p->id} | Nama: {$p->nama} | Jab: " . ($p->jabatanAsn->nama_jabatan ?? 'N/A') . " | Ket: {$p->ket_jabatan} | Pangkat: " . ($p->pangkatGolongan->pangkat ?? 'N/A') . " | Gol: " . ($p->pangkatGolongan->golongan ?? 'N/A') . "\n";
    }
}
