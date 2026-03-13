<?php

namespace Database\Factories;

use App\Models\TunjanganUmum;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<TunjanganUmum>
 */
class TunjanganUmumFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tunjangan_beras' => 7242.00,
            'jumlah_beras' => 10,
            'tunjangan_anak_persen' => 2,
            'tunjangan_istri_persen' => 10,
            'status' => 'Y',
        ];
    }
}
