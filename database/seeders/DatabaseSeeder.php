<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            RoleAndUserSeeder::class ,
            SupportingTablesSeeder::class ,
            ParameterGajiSeeder::class,
            TunjanganPerumahanSeeder::class,
            TunjanganTransportasiSeeder::class,
            TunjanganKomunikasiIntensifSeeder::class,
            TunjanganUmumSeeder::class,
            PotonganSeeder::class,
            TarifPajakSeeder::class,
            IkatanKeluargaSeeder::class ,
            JenisPendidikanSeeder::class ,
            EsselonSeeder::class ,
            PangkatGolonganSeeder::class ,
            SkpdSeeder::class ,
            JabatanAsnSeeder::class ,
            StatusPegawaiSeeder::class ,
            AlatKelengkapanSeeder::class ,
            SuratKeputusanSeeder::class,
            AnggotaSeeder::class ,
            JabatanAnggotaSeeder::class ,
            KeluargaSeeder::class ,
        ]);
    }
}
