<?php
// Bootstrap Laravel
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SuratKeputusan;
use App\Models\Anggota;
use App\Models\JabatanAnggota;

echo "--- SURAT KEPUTUSAN ---\n";
$sks = SuratKeputusan::with('alatKelengkapan')->get();
foreach ($sks as $sk) {
    echo "ID: {$sk->id}, No SK: {$sk->no_sk}, Alat: " . ($sk->alatKelengkapan->nama ?? 'N/A') . ", Status: {$sk->status}\n";
}

echo "\n--- JABATAN ANGGOTA ---\n";
$jaCount = JabatanAnggota::count();
echo "Total Jabatan Anggota: {$jaCount}\n";
$jaBySk = JabatanAnggota::select('id_surat_keputusan', \DB::raw('count(*) as total'))
    ->groupBy('id_surat_keputusan')
    ->get();
foreach ($jaBySk as $ja) {
    echo "SK ID: {$ja->id_surat_keputusan}, Total: {$ja->total}\n";
}

echo "\n--- ANGGOTA WITH ID_KOMISI OR NAMA_KOMISI ---\n";
$anggotaWithKomisi = Anggota::whereNotNull('id_komisi')
    ->orWhereNotNull('nama_komisi')
    ->get();
echo "Total Anggota with id_komisi/nama_komisi in table: " . $anggotaWithKomisi->count() . "\n";
foreach ($anggotaWithKomisi as $a) {
    echo "ID: {$a->id}, Nama: {$a->nama_anggota}, id_komisi: {$a->id_komisi}, nama_komisi: {$a->nama_komisi}\n";
}
