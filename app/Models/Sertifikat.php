<?php

namespace App\Models;

use App\Models\Pegawai;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sertifikat extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'kode_sertifikat',
        'bidang',
        'jenjang',
        'nama_penerbit',
        'keterangan',
    ];

    public function pegawais()
    {
        return $this->belongsToMany(
            Pegawai::class,
            'sertifikat_pegawai',
            'sertifikat_kode',
            'pegawai_nopeg',
            'kode_sertifikat',
            'nopeg'
        )->withPivot([
            'no_reg_sertifikat',
            'nomor_sertifikat',
            'tanggal_terbit',
            'tanggal_expire',
            'penyelenggara',
        ])->withTimestamps()->withTrashed();
    }
}
