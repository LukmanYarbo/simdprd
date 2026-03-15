<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$aks = \App\Models\AlatKelengkapan::all();
foreach ($aks as $ak) {
    echo "ID: {$ak->id} | Nama: {$ak->nama} | Nama Komisi: {$ak->nama_komisi}\n";
}
