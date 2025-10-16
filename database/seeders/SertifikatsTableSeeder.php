<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SertifikatsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('sertifikats')->delete();
        
        \DB::table('sertifikats')->insert(array (
            0 => 
            array (
                'id' => 1,
                'kode_sertifikat' => 'PBK-SP-4-PTD',
                'bidang' => 'PBK SISTEM PEMBAYARAN: PENGELOLAAN TRANSFER DANA',
                'jenjang' => '4',
                'nama_penerbit' => 'LPK',
                'keterangan' => 'MANDATORY',
                'created_at' => '2025-10-16 10:34:36',
                'updated_at' => '2025-10-16 10:34:36',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'kode_sertifikat' => 'PBK-SP-5-PTD',
                'bidang' => 'PBK SISTEM PEMBAYARAN: PENGELOLAAN TRANSFER DANA',
                'jenjang' => '5',
                'nama_penerbit' => 'LPK',
                'keterangan' => 'MANDATORY',
                'created_at' => '2025-10-16 10:34:36',
                'updated_at' => '2025-10-16 10:34:36',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'kode_sertifikat' => 'SP-6-PTD',
                'bidang' => 'SERTIFIKASI SISTEM PEMBAYARAN: PENGELOLAAN TRANSFER DANA',
                'jenjang' => '6',
                'nama_penerbit' => 'LSP',
                'keterangan' => 'MANDATORY',
                'created_at' => '2025-10-16 10:34:36',
                'updated_at' => '2025-10-16 10:34:36',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'kode_sertifikat' => 'PBK-SP-4-PSBN',
                'bidang' => 'PBK SISTEM PEMBAYARAN: PENATAUSAHAAN SURAT BERHARGA NASABAH',
                'jenjang' => '4',
                'nama_penerbit' => 'LPK',
                'keterangan' => 'MANDATORY',
                'created_at' => '2025-10-16 10:34:36',
                'updated_at' => '2025-10-16 10:34:36',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'kode_sertifikat' => 'PBK-SP-5-PSBN',
                'bidang' => 'PBK SISTEM PEMBAYARAN: PENATAUSAHAAN SURAT BERHARGA NASABAH',
                'jenjang' => '5',
                'nama_penerbit' => 'LPK',
                'keterangan' => 'MANDATORY',
                'created_at' => '2025-10-16 10:34:36',
                'updated_at' => '2025-10-16 10:34:36',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'kode_sertifikat' => 'SP-6-PSBN',
                'bidang' => 'SERTIFIKASI SISTEM PEMBAYARAN: PENATAUSAHAAN SURAT BERHARGA NASABAH',
                'jenjang' => '6',
                'nama_penerbit' => 'LSP',
                'keterangan' => 'MANDATORY',
                'created_at' => '2025-10-16 10:34:36',
                'updated_at' => '2025-10-16 10:34:36',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'kode_sertifikat' => 'PBK-SP-4-PUT',
                'bidang' => 'PBK SISTEM PEMBAYARAN: PENGELOLAAN UANG TUNAI',
                'jenjang' => '4',
                'nama_penerbit' => 'LPK',
                'keterangan' => 'MANDATORY',
                'created_at' => '2025-10-16 10:34:36',
                'updated_at' => '2025-10-16 10:34:36',
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'kode_sertifikat' => 'PBK-SP-5-PUT',
                'bidang' => 'PBK SISTEM PEMBAYARAN: PENGELOLAAN UANG TUNAI',
                'jenjang' => '5',
                'nama_penerbit' => 'LPK',
                'keterangan' => 'MANDATORY',
                'created_at' => '2025-10-16 10:34:36',
                'updated_at' => '2025-10-16 10:34:36',
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'kode_sertifikat' => 'SP-6-PUT',
                'bidang' => 'SERTIFIKASI SISTEM PEMBAYARAN: PENGELOLAAN UANG TUNAI',
                'jenjang' => '6',
                'nama_penerbit' => 'LSP',
                'keterangan' => 'MANDATORY',
                'created_at' => '2025-10-16 10:34:36',
                'updated_at' => '2025-10-16 10:34:36',
                'deleted_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'kode_sertifikat' => 'PBK-SP-4-PTP',
                'bidang' => 'PBK SISTEM PEMBAYARAN: PEMROSESAN TRANSAKSI PEMBAYARAN',
                'jenjang' => '4',
                'nama_penerbit' => 'LPK',
                'keterangan' => 'MANDATORY',
                'created_at' => '2025-10-16 10:34:36',
                'updated_at' => '2025-10-16 10:34:36',
                'deleted_at' => NULL,
            ),
            10 => 
            array (
                'id' => 11,
                'kode_sertifikat' => 'PBK-SP-5-PTP',
                'bidang' => 'PBK SISTEM PEMBAYARAN: PEMROSESAN TRANSAKSI PEMBAYARAN',
                'jenjang' => '5',
                'nama_penerbit' => 'LPK',
                'keterangan' => 'MANDATORY',
                'created_at' => '2025-10-16 10:34:36',
                'updated_at' => '2025-10-16 10:34:36',
                'deleted_at' => NULL,
            ),
            11 => 
            array (
                'id' => 12,
                'kode_sertifikat' => 'SP-6-PTP',
                'bidang' => 'SERTIFIKASI SISTEM PEMBAYARAN: PEMROSESAN TRANSAKSI PEMBAYARAN',
                'jenjang' => '6',
                'nama_penerbit' => 'LSP',
                'keterangan' => 'MANDATORY',
                'created_at' => '2025-10-16 10:34:36',
                'updated_at' => '2025-10-16 10:34:36',
                'deleted_at' => NULL,
            ),
            12 => 
            array (
                'id' => 13,
                'kode_sertifikat' => 'PBK-SP-4-VA-PUKA',
            'bidang' => 'PBK SISTEM PEMBAYARAN: PENUKARAN VALUTA ASING DAN PEMBAWAAN UANG KERTAS ASING (UKA)',
                'jenjang' => '4',
                'nama_penerbit' => 'LPK',
                'keterangan' => 'MANDATORY',
                'created_at' => '2025-10-16 10:34:36',
                'updated_at' => '2025-10-16 10:34:36',
                'deleted_at' => NULL,
            ),
            13 => 
            array (
                'id' => 14,
                'kode_sertifikat' => 'PBK-SP-5-VA-PUKA',
            'bidang' => 'PBK SISTEM PEMBAYARAN: PENUKARAN VALUTA ASING DAN PEMBAWAAN UANG KERTAS ASING (UKA)',
                'jenjang' => '5',
                'nama_penerbit' => 'LPK',
                'keterangan' => 'MANDATORY',
                'created_at' => '2025-10-16 10:34:36',
                'updated_at' => '2025-10-16 10:34:36',
                'deleted_at' => NULL,
            ),
            14 => 
            array (
                'id' => 15,
                'kode_sertifikat' => 'SP-6-VA-PUKA',
            'bidang' => 'SERTIFIKASI SISTEM PEMBAYARAN: PENUKARAN VALUTA ASING DAN PEMBAWAAN UANG KERTAS ASING (UKA)',
                'jenjang' => '6',
                'nama_penerbit' => 'LSP',
                'keterangan' => 'MANDATORY',
                'created_at' => '2025-10-16 10:34:36',
                'updated_at' => '2025-10-16 10:34:36',
                'deleted_at' => NULL,
            ),
            15 => 
            array (
                'id' => 16,
                'kode_sertifikat' => 'PBK-SP-4-STT',
                'bidang' => 'PBK SISTEM PEMBAYARAN: SETELMEN TRANSAKSI TREASURI',
                'jenjang' => '4',
                'nama_penerbit' => 'LPK',
                'keterangan' => 'MANDATORY',
                'created_at' => '2025-10-16 10:34:36',
                'updated_at' => '2025-10-16 10:34:36',
                'deleted_at' => NULL,
            ),
            16 => 
            array (
                'id' => 17,
                'kode_sertifikat' => 'SP-5-STT',
                'bidang' => 'SERTIFIKASI SISTEM PEMBAYARAN: SETELMEN TRANSAKSI TREASURI',
                'jenjang' => '5',
                'nama_penerbit' => 'LSP',
                'keterangan' => 'MANDATORY',
                'created_at' => '2025-10-16 10:34:36',
                'updated_at' => '2025-10-16 10:34:36',
                'deleted_at' => NULL,
            ),
            17 => 
            array (
                'id' => 18,
                'kode_sertifikat' => 'SP-6-STT',
                'bidang' => 'SERTIFIKASI SISTEM PEMBAYARAN: SETELMEN TRANSAKSI TREASURI',
                'jenjang' => '6',
                'nama_penerbit' => 'LSP',
                'keterangan' => 'MANDATORY',
                'created_at' => '2025-10-16 10:34:36',
                'updated_at' => '2025-10-16 10:34:36',
                'deleted_at' => NULL,
            ),
            18 => 
            array (
                'id' => 19,
                'kode_sertifikat' => 'PBK-SP-4-SPTTF',
                'bidang' => 'PBK SISTEM PEMBAYARAN: SETELMEN PEMBAYARAN TRANSAKSI TRADE FINANCE',
                'jenjang' => '4',
                'nama_penerbit' => 'LPK',
                'keterangan' => 'MANDATORY',
                'created_at' => '2025-10-16 10:34:36',
                'updated_at' => '2025-10-16 10:34:36',
                'deleted_at' => NULL,
            ),
            19 => 
            array (
                'id' => 20,
                'kode_sertifikat' => 'SP-5-SPTTF',
                'bidang' => 'SERTIFIKASI SISTEM PEMBAYARAN: SETELMEN PEMBAYARAN TRANSAKSI TRADE FINANCE',
                'jenjang' => '5',
                'nama_penerbit' => 'LSP',
                'keterangan' => 'MANDATORY',
                'created_at' => '2025-10-16 10:34:36',
                'updated_at' => '2025-10-16 10:34:36',
                'deleted_at' => NULL,
            ),
            20 => 
            array (
                'id' => 21,
                'kode_sertifikat' => 'SP-6-SPTTF',
                'bidang' => 'SERTIFIKASI SISTEM PEMBAYARAN: SETELMEN PEMBAYARAN TRANSAKSI TRADE FINANCE',
                'jenjang' => '6',
                'nama_penerbit' => 'LSP',
                'keterangan' => 'MANDATORY',
                'created_at' => '2025-10-16 10:34:36',
                'updated_at' => '2025-10-16 10:34:36',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}