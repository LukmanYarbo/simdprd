<?php

namespace Database\Factories;

use App\Models\TunjanganPerumahan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TunjanganPerumahan>
 */
class TunjanganPerumahanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tgl_berlaku' => '2021-01-01',
            'no_peraturan' => 'PERBUB NO 10 TAHUN 2021',
            'nilai_tunjangan_ketua' => 12000000.00,
            'nilai_tunjangan_wakil' => 10000000.00,
            'nilai_tunjangan_anggota' => 6056000.00,
            'status' => 'Y',
        ];
    }
}
