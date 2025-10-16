<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SertifikatPegawai extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table = 'sertifikat_pegawai';

    protected $fillable = [
        'pegawai_nopeg',
        'sertifikat_kode',
        'no_reg_sertifikat',
        'nomor_sertifikat',
        'tanggal_terbit',
        'tanggal_expire',
        'penyelenggara',
    ];

    protected $casts = [
        'tanggal_terbit' => 'date',
        'tanggal_expire' => 'date',
    ];

    public function pegawai()
    {
        return $this->belongsTo(Pegawai::class, 'pegawai_nopeg', 'nopeg');
    }

    public function sertifikat()
    {
        return $this->belongsTo(Sertifikat::class, 'sertifikat_kode', 'kode_sertifikat');
    }
}
