<?php

namespace Database\Factories;

use App\Models\Potongan;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Potongan>
 */
class PotonganFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tunjangan_bpjs' => 0.00,
            'potongan_bpjs' => 1.00,
            'maksimal_potongan_bpjs' => 12000000.00,
            'jkk' => 0.24,
            'jkm' => 0.72,
            'maks_jkkjkm' => 300000.00,
            'pot_pph' => 15.00,
        ];
    }
}
