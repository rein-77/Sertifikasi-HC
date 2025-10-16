<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SertifikatPegawaiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
    $sertifikatPegawaiId = $this->route('sertifikat_pegawai')?->id;

        $nomorRule = Rule::unique('sertifikat_pegawai', 'nomor_sertifikat')
            ->where(fn ($query) => $query->where('pegawai_nopeg', $this->input('pegawai_nopeg')));

        if ($sertifikatPegawaiId) {
            $nomorRule->ignore($sertifikatPegawaiId);
        }

        return [
            'pegawai_nopeg' => ['required', 'string', 'max:5', 'exists:pegawais,nopeg'],
            'sertifikat_kode' => ['required', 'string', 'max:50', 'exists:sertifikats,kode_sertifikat'],
            'no_reg_sertifikat' => ['nullable', 'string', 'max:100'],
            'nomor_sertifikat' => ['nullable', 'string', 'max:100', $nomorRule],
            'tanggal_terbit' => ['required', 'date'],
            'tanggal_expire' => ['nullable', 'date', 'after_or_equal:tanggal_terbit'],
            'penyelenggara' => ['nullable', 'string', 'max:150'],
        ];
    }
}
