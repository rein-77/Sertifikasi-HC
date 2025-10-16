<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SertifikatPegawaiTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('sertifikat_pegawai')->delete();
        
        \DB::table('sertifikat_pegawai')->insert(array (
            0 => 
            array (
                'id' => 1,
                'pegawai_nopeg' => '00084',
                'sertifikat_kode' => 'PBK-SP-4-PTP',
                'no_reg_sertifikat' => NULL,
                'nomor_sertifikat' => NULL,
                'tanggal_terbit' => '2025-10-16',
                'tanggal_expire' => '2025-10-16',
                'penyelenggara' => 'aw',
                'created_at' => '2025-10-16 02:35:20',
                'updated_at' => '2025-10-16 02:35:20',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}