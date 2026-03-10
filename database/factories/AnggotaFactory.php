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
            'jmlh_anak' => null,
            'no_telp' => $this->faker->phoneNumber,
            'email' => $this->faker->unique()->safeEmail,
            'no_rekening' => $this->faker->bankAccountNumber,
            'prov' => 'SULAWESI UTARA',
            'kab' => 'KABUPATEN BOLAANG MONGONDOW UTARA',
            'kec' => 'KAIDIPANG',
            'desa' => 'BOROKO',
            'alamat_lengkap' => $this->faker->address,
            'id_status_keanggotaan' => 1,
            'id_dprd' => 3, // Default to Anggota, will be overridden in seeder
            'tgl_mulai' => $this->faker->date('Y-m-d', '-5 years'),
            'tgl_berhenti' => null,
            'id_komisi' => null,
            'id_banggar' => null,
            'id_banmus' => null,
            'id_balegda' => null,
            'id_bk' => null,
            'id_pansus' => null,
            'id_panja' => null,
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
