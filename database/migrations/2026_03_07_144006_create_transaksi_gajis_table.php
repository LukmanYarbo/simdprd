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
        Schema::create('transaksi_gaji', function (Blueprint $table) {
            $table->id();
            $table->string('bln_thn');
            $table->foreignId('id_anggota')
                ->constrained('anggota')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
            $table->integer('jumlah_is')->default(0);
            $table->integer('jumlah_anak')->default(0);
            $table->string('status_kawin')->nullable();
            $table->integer('jumlah_pegawai')->default(0);
            $table->integer('jumlah_jiwa')->default(0);
            $table->bigInteger('gaji_pokok')->default(0);
            $table->bigInteger('tunjangan_anak')->default(0);
            $table->bigInteger('tunjangan_istri')->default(0);
            $table->bigInteger('tunjangan_beras')->default(0);
            $table->bigInteger('tunjangan_paket')->default(0);
            $table->bigInteger('tunjangan_jabatan')->default(0);
            $table->bigInteger('tunjangan_komisi')->default(0);
            $table->bigInteger('tunjangan_banggar')->default(0);
            $table->bigInteger('tunjangan_banmus')->default(0);
            $table->bigInteger('tunjangan_balegda')->default(0);
            $table->bigInteger('tunjangan_bk')->default(0);
            $table->bigInteger('tunjangan_pansus')->default(0);
            $table->bigInteger('tunjangan_panja')->default(0);
            $table->bigInteger('pembulatan')->default(0);
            $table->bigInteger('brutto1')->default(0);
            $table->bigInteger('brutto2')->default(0);
            $table->bigInteger('tunjangan_pph21')->default(0);
            $table->bigInteger('tunjangan_bpjs')->default(0);
            $table->bigInteger('tunjangan_jkk')->default(0);
            $table->bigInteger('tunjangan_jkm')->default(0);
            $table->string('Kategori_TER')->nullable();
            $table->double('Nilai_TER', 8, 2)->nullable();
            $table->double('PPH21_Gaji')->nullable();
            $table->double('PPh21_Tunjangan')->nullable();
            $table->bigInteger('potongan_pph21')->default(0);
            $table->bigInteger('potongan_bpjs')->default(0);
            $table->bigInteger('potongan_bpjs2')->default(0);
            $table->bigInteger('potongan_jkk')->default(0);
            $table->bigInteger('potongan_jkm')->default(0);
            $table->bigInteger('nilai_netto')->default(0);
            $table->bigInteger('tunjangan_perumahan')->default(0);
            $table->bigInteger('tunjangan_transportasi')->default(0);
            $table->bigInteger('tunjangan_tki')->default(0);
            $table->bigInteger('tunjangan_reses')->default(0);
            $table->bigInteger('potonganpph_perumahan')->default(0);
            $table->bigInteger('potonganpph_transportasi')->default(0);
            $table->bigInteger('potonganpph_tki')->default(0);
            $table->bigInteger('potonganpph_reses')->default(0);
            $table->bigInteger('nilai_gajitunjangan')->default(0);
            $table->bigInteger('total_potongan1')->default(0);
            $table->bigInteger('total_potongan2')->default(0);
            $table->bigInteger('jumlah_bersih')->default(0);
            $table->json('detail_pajak')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transaksi_gaji');
    }
};
