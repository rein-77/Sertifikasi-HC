<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PegawaiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $pegawaiId = $this->route('pegawai')?->id;

        return [
            'nopeg' => [
                'required',
                'string',
                'max:5',
                Rule::unique('pegawais', 'nopeg')->ignore($pegawaiId),
            ],
            'nama' => ['required', 'string', 'max:255'],
            'nip' => [
                'nullable',
                'string',
                'max:50',
                Rule::unique('pegawais', 'nip')->ignore($pegawaiId),
            ],
            'tgl_lahir' => ['nullable', 'date'],
            'jabatan' => ['nullable', 'string', 'max:100'],
            'tanggal_menjabat' => ['nullable', 'date'],
            'unit_kerja' => ['nullable', 'string', 'max:100'],
        ];
    }
}
