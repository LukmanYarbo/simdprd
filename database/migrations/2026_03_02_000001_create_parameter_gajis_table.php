<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parameter_gajis', function (Blueprint $table) {
            $table->id();
            $table->string('no_peraturan');
            $table->date('tgl_berlaku');
            $table->bigInteger('gajipokok_ketua');
            $table->decimal('persen_gapokwakil', 5, 2)->default(0);
            $table->decimal('persen_gapokanggota', 5, 2)->default(0);
            $table->decimal('persen_tunjabketua', 5, 2)->default(0);
            $table->decimal('persen_tunjabwakil', 5, 2)->default(0);
            $table->decimal('persen_tunjabanggota', 5, 2)->default(0);
            $table->decimal('persen_tunketua_aleg', 5, 2)->default(0);
            $table->decimal('persen_tunwakil_aleg', 5, 2)->default(0);
            $table->decimal('persen_tunsek_aleg', 5, 2)->default(0);
            $table->decimal('persen_tunanggota_aleg', 5, 2)->default(0);
            $table->decimal('persen_uangpaket', 5, 2)->default(0);
            $table->char('status', 1)->default('T'); // Y = Aktif, T = Tidak Aktif
            $table->string('file')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parameter_gajis');
    }
};
