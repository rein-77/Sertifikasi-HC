<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pegawai extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'nopeg',
        'nama',
        'nip',
        'tgl_lahir',
        'jabatan',
        'tanggal_menjabat',
        'unit_kerja',
    ];

    protected $casts = [
        'tgl_lahir' => 'date',
        'tanggal_menjabat' => 'date',
    ];

    public function sertifikats()
    {
        return $this->belongsToMany(
            Sertifikat::class,
            'sertifikat_pegawai',
            'pegawai_nopeg',
            'sertifikat_kode',
            'nopeg',
            'kode_sertifikat'
        )->withPivot([
            'no_reg_sertifikat',
            'nomor_sertifikat',
            'tanggal_terbit',
            'tanggal_expire',
            'penyelenggara',
        ])->withTimestamps();
    }

    public function user()
    {
        return $this->hasOne(User::class, 'pegawai_nopeg', 'nopeg');
    }
}
