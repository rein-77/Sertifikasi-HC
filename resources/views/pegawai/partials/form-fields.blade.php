@props(['pegawai' => null])

<div class="grid gap-6 sm:grid-cols-2">
    <div class="sm:col-span-1">
        <x-input-label for="nopeg" :value="__('Nopeg')" />
        <x-text-input id="nopeg" name="nopeg" type="text" class="mt-1 block w-full" value="{{ old('nopeg', $pegawai->nopeg ?? '') }}" maxlength="5" required />
        <p class="mt-1 text-sm text-gray-500">{{ __('Gunakan 5 karakter unik untuk nomor pegawai.') }}</p>
        <x-input-error :messages="$errors->get('nopeg')" class="mt-2" />
    </div>

    <div class="sm:col-span-1">
        <x-input-label for="nama" :value="__('Nama')" />
        <x-text-input id="nama" name="nama" type="text" class="mt-1 block w-full" value="{{ old('nama', $pegawai->nama ?? '') }}" required />
        <x-input-error :messages="$errors->get('nama')" class="mt-2" />
    </div>

    <div class="sm:col-span-1">
        <x-input-label for="nip" :value="__('NIP')" />
        <x-text-input id="nip" name="nip" type="text" class="mt-1 block w-full" value="{{ old('nip', $pegawai->nip ?? '') }}" maxlength="50" />
        <x-input-error :messages="$errors->get('nip')" class="mt-2" />
    </div>

    <div class="sm:col-span-1">
        <x-input-label for="tgl_lahir" :value="__('Tanggal Lahir')" />
        <x-text-input id="tgl_lahir" name="tgl_lahir" type="date" class="mt-1 block w-full" value="{{ old('tgl_lahir', optional($pegawai->tgl_lahir ?? null)->format('Y-m-d')) }}" />
        <x-input-error :messages="$errors->get('tgl_lahir')" class="mt-2" />
    </div>

    <div class="sm:col-span-1">
        <x-input-label for="jabatan" :value="__('Jabatan')" />
        <x-text-input id="jabatan" name="jabatan" type="text" class="mt-1 block w-full" value="{{ old('jabatan', $pegawai->jabatan ?? '') }}" maxlength="100" />
        <x-input-error :messages="$errors->get('jabatan')" class="mt-2" />
    </div>

    <div class="sm:col-span-1">
        <x-input-label for="tanggal_menjabat" :value="__('Tanggal Menjabat')" />
        <x-text-input id="tanggal_menjabat" name="tanggal_menjabat" type="date" class="mt-1 block w-full" value="{{ old('tanggal_menjabat', optional($pegawai->tanggal_menjabat ?? null)->format('Y-m-d')) }}" />
        <x-input-error :messages="$errors->get('tanggal_menjabat')" class="mt-2" />
    </div>

    <div class="sm:col-span-2">
        <x-input-label for="unit_kerja" :value="__('Unit Kerja')" />
        <x-text-input id="unit_kerja" name="unit_kerja" type="text" class="mt-1 block w-full" value="{{ old('unit_kerja', $pegawai->unit_kerja ?? '') }}" maxlength="100" />
        <x-input-error :messages="$errors->get('unit_kerja')" class="mt-2" />
    </div>
</div>
