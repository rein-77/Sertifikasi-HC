<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Impor Sertifikat Pegawai') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div class="space-y-6">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">{{ __('Impor Data dari CSV') }}</h3>
                            <p class="mt-1 text-sm text-gray-600">
                                {{ __('Upload file CSV untuk menambahkan banyak data sertifikat pegawai sekaligus.') }}
                            </p>
                        </div>

                        <div class="border-t border-gray-200 pt-6">
                            <div class="rounded-md bg-blue-50 p-4 mb-6">
                                <div class="flex">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a.75.75 0 000 1.5h.253a.25.25 0 01.244.304l-.459 2.066A1.75 1.75 0 0010.747 15H11a.75.75 0 000-1.5h-.253a.25.25 0 01-.244-.304l.459-2.066A1.75 1.75 0 009.253 9H9z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3 flex-1">
                                        <h3 class="text-sm font-medium text-blue-800">{{ __('Petunjuk Impor CSV') }}</h3>
                                        <div class="mt-2 text-sm text-blue-700">
                                            <ul class="list-disc pl-5 space-y-1">
                                                <li>{{ __('File CSV harus memiliki header dengan kolom: pegawai_nopeg, sertifikat_kode, nomor_sertifikat, no_reg_sertifikat, tanggal_terbit, tanggal_expire, penyelenggara') }}</li>
                                                <li>{{ __('Format tanggal harus: YYYY-MM-DD (contoh: 2025-01-15)') }}</li>
                                                <li>{{ __('Pegawai (nopeg) dan Sertifikat (kode) harus sudah terdaftar di sistem') }}</li>
                                                <li>{{ __('Kolom yang wajib diisi: pegawai_nopeg, sertifikat_kode, tanggal_terbit') }}</li>
                                                <li>{{ __('Kolom opsional: nomor_sertifikat, no_reg_sertifikat, tanggal_expire, penyelenggara') }}</li>
                                                <li>{{ __('Tanggal terbit tidak boleh lebih dari tanggal expire') }}</li>
                                                <li>{{ __('Download template untuk melihat format yang benar') }}</li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-6">
                                <a href="{{ route('sertifikat-pegawai.template') }}" class="inline-flex items-center rounded-md bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm border border-gray-300 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                    <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                    {{ __('Download Template CSV') }}
                                </a>
                            </div>

                            <form method="POST" action="{{ route('sertifikat-pegawai.bulk.preview') }}" enctype="multipart/form-data" class="space-y-6">
                                @csrf

                                <div>
                                    <label for="csv_file" class="block text-sm font-medium text-gray-700">
                                        {{ __('File CSV') }}
                                    </label>
                                    <div class="mt-1">
                                        <input type="file" name="csv_file" id="csv_file" accept=".csv" required
                                            class="block w-full text-sm text-gray-900 border border-gray-300 rounded-md cursor-pointer bg-gray-50 focus:outline-none focus:border-indigo-500 focus:ring-indigo-500">
                                    </div>
                                    @error('csv_file')
                                        <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="flex items-center justify-between">
                                    <a href="{{ route('sertifikat-pegawai.index') }}" class="inline-flex items-center rounded-md bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm border border-gray-300 hover:bg-gray-50">
                                        {{ __('Batal') }}
                                    </a>
                                    <button type="submit" class="inline-flex items-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-500 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2">
                                        {{ __('Preview Data') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
