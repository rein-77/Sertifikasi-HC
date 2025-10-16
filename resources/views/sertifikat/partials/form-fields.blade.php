@props(['sertifikat' => null])

<div class="grid gap-6">
    <div>
        <x-input-label for="kode_sertifikat" :value="__('Kode Sertifikat')" />
        <x-text-input id="kode_sertifikat" name="kode_sertifikat" type="text" class="mt-1 block w-full" value="{{ old('kode_sertifikat', $sertifikat->kode_sertifikat ?? '') }}" maxlength="50" required />
        <p class="mt-1 text-sm text-gray-500">{{ __('Gunakan kode unik maksimal 50 karakter.') }}</p>
        <x-input-error :messages="$errors->get('kode_sertifikat')" class="mt-2" />
    </div>

    <div class="grid gap-6 sm:grid-cols-2">
        <div>
            <x-input-label for="bidang" :value="__('Bidang')" />
            <x-text-input id="bidang" name="bidang" type="text" class="mt-1 block w-full" value="{{ old('bidang', $sertifikat->bidang ?? '') }}" maxlength="100" />
            <x-input-error :messages="$errors->get('bidang')" class="mt-2" />
        </div>

        <div>
            <x-input-label for="jenjang" :value="__('Jenjang')" />
            <x-text-input id="jenjang" name="jenjang" type="text" class="mt-1 block w-full" value="{{ old('jenjang', $sertifikat->jenjang ?? '') }}" maxlength="50" />
            <x-input-error :messages="$errors->get('jenjang')" class="mt-2" />
        </div>
    </div>

    <div>
        <x-input-label for="nama_penerbit" :value="__('Nama Penerbit')" />
        <x-text-input id="nama_penerbit" name="nama_penerbit" type="text" class="mt-1 block w-full" value="{{ old('nama_penerbit', $sertifikat->nama_penerbit ?? '') }}" maxlength="150" />
        <x-input-error :messages="$errors->get('nama_penerbit')" class="mt-2" />
    </div>

    <div>
        <x-input-label for="keterangan" :value="__('Keterangan')" />
        <textarea id="keterangan" name="keterangan" rows="4" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">{{ old('keterangan', $sertifikat->keterangan ?? '') }}</textarea>
        <x-input-error :messages="$errors->get('keterangan')" class="mt-2" />
    </div>
</div>
