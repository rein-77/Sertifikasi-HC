<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SertifikatRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $sertifikatId = $this->route('sertifikat')?->id;

        return [
            'kode_sertifikat' => [
                'required',
                'string',
                'max:50',
                Rule::unique('sertifikats', 'kode_sertifikat')->ignore($sertifikatId),
            ],
            'bidang' => ['nullable', 'string', 'max:100'],
            'jenjang' => ['nullable', 'string', 'max:50'],
            'nama_penerbit' => ['nullable', 'string', 'max:150'],
            'keterangan' => ['nullable', 'string'],
        ];
    }
}
