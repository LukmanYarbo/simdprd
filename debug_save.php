<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Services\GajiCalculatorService;
use App\Models\DsbGaji;

$service = app(GajiCalculatorService::class);
try {
    echo "Attempting to save for 1-2026...\n";
    $service->saveDsbGaji('1-2026');
    echo "Success!\n";
} catch (\Exception $e) {
    echo "Caught exception: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
