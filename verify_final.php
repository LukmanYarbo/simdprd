<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\GajiCalculatorService;
use App\Models\DsbGaji;

$service = app(GajiCalculatorService::class);
$service->saveDsbGaji('1-2026');

$d = DsbGaji::where('bln_thn', '1-2026')->first();
if ($d) {
    echo "PA: {$d->nama_pa} | Gol: {$d->golongan_pa} | Jab: {$d->jabatan_pa}\n";
    echo "Bendahara: {$d->nama_bendahara} | Gol: {$d->golongan_bendahara} | Jab: {$d->jabatan_bendahara}\n";
} else {
    echo "No data found for 1-2026\n";
}
