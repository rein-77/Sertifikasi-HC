<?php

namespace Database\Seeders;

use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $pegawai = Pegawai::factory()->create([
            'nopeg' => '00001',
            'nama' => 'Administrator',
            'jabatan' => 'Administrator',
            'unit_kerja' => 'Sertifikasi',
        ]);

        User::query()->create([
            'pegawai_nopeg' => $pegawai->nopeg,
            'password' => Hash::make('password'),
        ]);
        $this->call(SertifikatsTableSeeder::class);
        $this->call(PegawaisTableSeeder::class);
        $this->call(SertifikatPegawaiTableSeeder::class);
    }
}
