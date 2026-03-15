<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$jas = \App\Models\JabatanAnggota::with('alatKelengkapan')->limit(10)->get();
foreach ($jas as $ja) {
    echo "ID: {$ja->id} | Anggota ID: {$ja->id_anggota} | Nama Komisi: {$ja->nama_komisi} | AK Nama: {$ja->alatKelengkapan->nama}\n";
}
