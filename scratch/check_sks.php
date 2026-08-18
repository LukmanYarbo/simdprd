<?php
// Bootstrap Laravel
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\SuratKeputusan;

$sks = SuratKeputusan::with('alatKelengkapan')->get();
foreach ($sks as $sk) {
    echo "ID: {$sk->id}, No SK: {$sk->no_sk}, Alat: {$sk->alatKelengkapan->nama}, Status: {$sk->status}\n";
}
