<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\TransaksiGaji;
use App\Services\GajiCalculatorService;

$service = app(GajiCalculatorService::class);
$months = TransaksiGaji::select('bln_thn')->distinct()->pluck('bln_thn');

echo "Syncing " . count($months) . " months...\n";
foreach ($months as $month) {
    echo "Saving for $month... ";
    try {
        $service->saveDsbGaji($month);
        echo "Done.\n";
    } catch (\Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
}
echo "Sync complete.\n";
