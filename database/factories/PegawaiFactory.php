<?php

namespace Database\Factories;

use App\Models\Pegawai;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Pegawai>
 */
class PegawaiFactory extends Factory
{
    protected $model = Pegawai::class;

    public function definition(): array
    {
        return [
            'nopeg' => str_pad((string) fake()->unique()->numberBetween(1, 99999), 5, '0', STR_PAD_LEFT),
            'nama' => fake()->name(),
            'nip' => fake()->optional()->numerify('####################'),
            'tgl_lahir' => fake()->optional()->date(),
            'jabatan' => fake()->optional()->jobTitle(),
            'tanggal_menjabat' => fake()->optional()->date(),
            'unit_kerja' => fake()->optional()->company(),
        ];
    }
}
