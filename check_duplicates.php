<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$duplicates = DB::table('jabatan_anggota')
    ->select('id_anggota', DB::raw('count(*) as total'))
    ->whereIn('id_alat_kelengkapan', [1, 2, 3])
    ->groupBy('id_anggota')
    ->having('total', '>', 1)
    ->get();

foreach ($duplicates as $dup) {
    echo "Anggota ID: {$dup->id_anggota} has {$dup->total} commission entries.\n";
}
