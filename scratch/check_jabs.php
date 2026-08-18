<?php
// Bootstrap Laravel
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\JabatanAlatKelengkapan;

$jabs = JabatanAlatKelengkapan::all();
foreach ($jabs as $j) {
    echo "ID: {$j->id}, Nama: {$j->nama}\n";
}
