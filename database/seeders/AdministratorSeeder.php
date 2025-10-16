<?php

namespace Database\Seeders;

use App\Models\Pegawai;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdministratorSeeder extends Seeder
{
    use WithoutModelEvents;

    public function run(): void
    {
        // Use environment-configurable credentials so it's easy to change in different environments
        $adminNopeg = env('ADMIN_NOPEG', '00001');
        $adminPassword = env('ADMIN_PASSWORD', 'password');

        // Create or fetch existing administrator pegawai (idempotent)
        $pegawai = Pegawai::firstOrCreate(
            ['nopeg' => $adminNopeg],
            [
                'nama' => 'Administrator',
                'nip' => null,
                'tgl_lahir' => null,
                'jabatan' => 'Administrator',
                'tanggal_menjabat' => null,
                'unit_kerja' => 'Sertifikasi',
            ]
        );

        echo "✓ Pegawai created/found: {$pegawai->nopeg} - {$pegawai->nama}\n";

        // Create or update user for administrator
        $user = User::firstOrCreate(
            ['pegawai_nopeg' => $pegawai->nopeg],
            ['password' => Hash::make($adminPassword)]
        );

        echo "✓ User created/found: ID {$user->id}, Nopeg: {$user->pegawai_nopeg}\n";

        // If the password env changed and the user already existed, ensure the password matches the configured one
        if (!Hash::check($adminPassword, $user->password)) {
            $user->password = Hash::make($adminPassword);
            $user->save();
            echo "✓ Password updated for admin user\n";
        }

        echo "✓ Admin user ready - Login with nopeg: {$adminNopeg}, password: {$adminPassword}\n";
    }
}
