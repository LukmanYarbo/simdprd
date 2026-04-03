<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('anggarans', function (Blueprint $table) {
            $table->dropColumn([
                'gaji_pokok',
                'tunjangan_keluarga',
                'tunjangan_jabatan',
                'tunjangan_beras',
                'tunjangan_pph',
                'pembulatan',
                'uang_paket',
                'tunjangan_alat_kelengkapan',
                'tunjangan_alat_kelengkapan_lainnya',
                'tunjangan_perumahan',
                'uang_jasa_pengabdian',
                'tunjangan_reses',
                'tunjangan_transportasi',
                'jkk',
                'jkm',
                'tunjangan_komunikasi_insentif'
            ]);
            $table->bigInteger('total_pagu')->default(0)->after('tahun_anggaran');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('anggarans', function (Blueprint $table) {
            $table->dropColumn('total_pagu');
            $table->bigInteger('gaji_pokok')->default(0);
            $table->bigInteger('tunjangan_keluarga')->default(0);
            $table->bigInteger('tunjangan_jabatan')->default(0);
            $table->bigInteger('tunjangan_beras')->default(0);
            $table->bigInteger('tunjangan_pph')->default(0);
            $table->bigInteger('pembulatan')->default(0);
            $table->bigInteger('uang_paket')->default(0);
            $table->bigInteger('tunjangan_alat_kelengkapan')->default(0);
            $table->bigInteger('tunjangan_alat_kelengkapan_lainnya')->default(0);
            $table->bigInteger('tunjangan_perumahan')->default(0);
            $table->bigInteger('uang_jasa_pengabdian')->default(0);
            $table->bigInteger('tunjangan_reses')->default(0);
            $table->bigInteger('tunjangan_transportasi')->default(0);
            $table->bigInteger('jkk')->default(0);
            $table->bigInteger('jkm')->default(0);
            $table->bigInteger('tunjangan_komunikasi_insentif')->default(0);
        });
    }
};
