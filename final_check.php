<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DsbGaji;

$d = DsbGaji::orderBy('id', 'desc')->first();
if ($d) {
    echo "ID: {$d->id}\n";
    echo "Month: {$d->bln_thn}\n";
    echo "PA: {$d->nama_pa} ({$d->nip_pa})\n";
    echo "Bendahara: {$d->nama_bendahara} ({$d->nip_bendahara})\n";
    echo "Status: {$d->status}\n";
    echo "Jumlah Pegawai: {$d->jumlah_pegawai}\n";
} else {
    echo "Table dsb_gaji is empty.";
}
