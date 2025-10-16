@props([
    'sertifikatPegawai' => null,
    'pegawais' => [],
    'sertifikats' => [],
])

@php
    $selectedPegawai = old('pegawai_nopeg', $sertifikatPegawai->pegawai_nopeg ?? '');
    $selectedSertifikat = old('sertifikat_kode', $sertifikatPegawai->sertifikat_kode ?? '');
    $nomorSertifikat = old('nomor_sertifikat', $sertifikatPegawai->nomor_sertifikat ?? '');
    $noRegSertifikat = old('no_reg_sertifikat', $sertifikatPegawai->no_reg_sertifikat ?? '');
    $tanggalTerbit = old('tanggal_terbit', optional($sertifikatPegawai?->tanggal_terbit)->format('Y-m-d'));
    $tanggalExpire = old('tanggal_expire', optional($sertifikatPegawai?->tanggal_expire)->format('Y-m-d'));
    $penyelenggara = old('penyelenggara', $sertifikatPegawai->penyelenggara ?? '');
@endphp

<div class="space-y-6">
    <div>
        <x-input-label for="pegawai_nopeg" :value="__('Pegawai')" />
        <select id="pegawai_nopeg" name="pegawai_nopeg" class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
            <option value="">{{ __('Pilih pegawai') }}</option>
            @foreach ($pegawais as $pegawai)
                <option value="{{ $pegawai->nopeg }}" @selected($selectedPegawai === $pegawai->nopeg)>
                    {{ $pegawai->nopeg }} — {{ $pegawai->nama }}
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('pegawai_nopeg')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="sertifikat_kode" :value="__('Sertifikat')" />
        <select id="sertifikat_kode" name="sertifikat_kode" class="mt-1 block w-full rounded-md border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 text-sm" required>
            <option value="">{{ __('Pilih sertifikat') }}</option>
            @foreach ($sertifikats as $sertifikat)
                <option value="{{ $sertifikat->kode_sertifikat }}" @selected($selectedSertifikat === $sertifikat->kode_sertifikat)>
                    {{ $sertifikat->kode_sertifikat }} — {{ $sertifikat->bidang ?? __('Tanpa bidang') }} @if ($sertifikat->jenjang) ({{ $sertifikat->jenjang }}) @endif
                </option>
            @endforeach
        </select>
        <x-input-error :messages="$errors->get('sertifikat_kode')" class="mt-2" />
    </div>

    <div class="grid gap-6 sm:grid-cols-2">
        <div>
            <x-input-label for="nomor_sertifikat" :value="__('Nomor Sertifikat')" />
            <x-text-input id="nomor_sertifikat" name="nomor_sertifikat" type="text" class="mt-1 block w-full" value="{{ $nomorSertifikat }}" maxlength="100" />
            <x-input-error :messages="$errors->get('nomor_sertifikat')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="no_reg_sertifikat" :value="__('No. Registrasi')" />
            <x-text-input id="no_reg_sertifikat" name="no_reg_sertifikat" type="text" class="mt-1 block w-full" value="{{ $noRegSertifikat }}" maxlength="100" />
            <x-input-error :messages="$errors->get('no_reg_sertifikat')" class="mt-2" />
        </div>
    </div>

    <div class="grid gap-6 sm:grid-cols-2">
        <div>
            <x-input-label for="tanggal_terbit" :value="__('Tanggal Terbit')" />
            <x-text-input id="tanggal_terbit" name="tanggal_terbit" type="date" class="mt-1 block w-full" value="{{ $tanggalTerbit }}" required />
            <x-input-error :messages="$errors->get('tanggal_terbit')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="tanggal_expire" :value="__('Tanggal Expire')" />
            <x-text-input id="tanggal_expire" name="tanggal_expire" type="date" class="mt-1 block w-full" value="{{ $tanggalExpire }}" />
            <x-input-error :messages="$errors->get('tanggal_expire')" class="mt-2" />
        </div>
    </div>

    <div>
        <x-input-label for="penyelenggara" :value="__('Penyelenggara')" />
        <x-text-input id="penyelenggara" name="penyelenggara" type="text" class="mt-1 block w-full" value="{{ $penyelenggara }}" maxlength="150" />
        <x-input-error :messages="$errors->get('penyelenggara')" class="mt-2" />
    </div>
</div>
