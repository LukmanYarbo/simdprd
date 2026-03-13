<?php

namespace Database\Factories;

use App\Models\TunjanganKomunikasiIntensif;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TunjanganKomunikasiIntensif>
 */
class TunjanganKomunikasiIntensifFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tgl_berlaku' => '2018-01-01',
            'no_peraturan' => 'PP NO 18 TAHUN 2017',
            'nilai_tunjangan_tki' => 6300000.00,
            'status' => 'Y',
        ];
    }
}
