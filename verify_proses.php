<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DsbGaji;
use App\Models\TransaksiGaji;
use App\Services\GajiCalculatorService;

$service = app(GajiCalculatorService::class);
$blnThn = '1-2026';

echo "Deleting data for $blnThn...\n";
TransaksiGaji::where('bln_thn', $blnThn)->delete();
$service->deleteDsbGaji($blnThn);

$count = DsbGaji::where('bln_thn', $blnThn)->count();
echo "DsbGaji count after delete: $count\n";

echo "Simulating prosesGaji for $blnThn...\n";
// In real app, ProsesGaji Livewire calls hitungGaji multiple times
// Here we just call saveDsbGaji which is what ProsesGaji now calls at the end
$service->saveDsbGaji($blnThn);

$count = DsbGaji::where('bln_thn', $blnThn)->count();
$d = DsbGaji::where('bln_thn', $blnThn)->first();

echo "DsbGaji count after save: $count\n";
if ($d) {
    echo "PA Name: {$d->nama_pa}\n";
    echo "Status: {$d->status}\n";
}
