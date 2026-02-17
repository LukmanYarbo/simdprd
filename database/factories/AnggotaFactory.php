<?php

namespace Database\Factories;

use App\Models\Anggota;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Anggota>
 */
class AnggotaFactory extends Factory
{
    protected $model = Anggota::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $jk = $this->faker->randomElement(['L', 'P']);
        $firstName = ($jk === 'L') ? $this->faker->firstNameMale : $this->faker->firstNameFemale;
        $lastName = $this->faker->lastName;
        $fullName = "$firstName $lastName";

        return [
            'nik' => $this->faker->unique()->numerify('################'),
            'nokk' => $this->faker->numerify('################'),
            'nama_anggota' => $fullName,
            'tempat_lahir' => $this->faker->city,
            'tgl_lahir' => $this->faker->date('Y-m-d', '-20 years'),
            'id_agama' => rand(1, 6),
            'jk' => $jk,
            'id_status_kawin' => rand(1, 4),
            'jmlh_istri' => ($jk === 'L' && rand(0, 1) === 1) ? 1 : 0,
            'jmlh_anak' => rand(0, 5),
            'no_telp' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'no_rekening' => $this->faker->bankAccountNumber,
            'prov' => 'JAWA BARAT',
            'kab' => 'BANDUNG',
            'kec' => 'COBLONG',
            'desa' => 'DAGO',
            'alamat_lengkap' => $this->faker->address,
            'id_status_keanggotaan' => rand(1, 3),
            'id_jabatan' => 3, // Default to Anggota, will be overridden in seeder
            'tgl_mulai' => $this->faker->date('Y-m-d', '-5 years'),
            'tgl_berhenti' => null,
            'id_komisi' => rand(1, 4),
            'id_banggar' => rand(1, 4),
            'id_banmus' => rand(1, 4),
            'id_balegda' => rand(1, 4),
            'id_bk' => rand(1, 4),
            'id_pansus' => rand(1, 4),
            'id_panja' => rand(1, 4),
            'status_bpjs' => $this->faker->randomElement(['Y', 'T']),
            'no_bpjs' => $this->faker->numerify('#############'),
            'status_jkk' => $this->faker->randomElement(['Y', 'T']),
            'no_jkk' => $this->faker->numerify('#############'),
            'status_jkm' => $this->faker->randomElement(['Y', 'T']),
            'no_jkm' => $this->faker->numerify('#############'),
            'status_tjgn_perum' => $this->faker->randomElement(['Y', 'T']),
            'status_tjgn_transport' => $this->faker->randomElement(['Y', 'T']),
            'foto_anggota' => null,
        ];
    }
}
