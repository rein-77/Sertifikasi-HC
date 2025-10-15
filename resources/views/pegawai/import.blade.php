<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Impor Data Pegawai') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-6">
                    <div class="space-y-2">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('Langkah Impor') }}</h3>
                        <p class="text-sm text-gray-500">
                            {{ __('Unduh templat, lengkapi data sesuai kolom, lalu unggah file CSV untuk direview sebelum disimpan.') }}
                        </p>
                        <div class="flex flex-wrap gap-3">
                            <a href="{{ route('pegawais.template') }}" class="inline-flex items-center justify-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 shadow-sm hover:bg-gray-50">
                                {{ __('Unduh Templat CSV') }}
                            </a>
                            <a href="{{ route('pegawais.index') }}" class="inline-flex items-center justify-center rounded-md border border-transparent bg-gray-100 px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-200">
                                {{ __('Kembali ke Daftar') }}
                            </a>
                        </div>
                    </div>

                    <div class="rounded-md border border-gray-200 bg-gray-50 p-4 text-sm text-gray-600 space-y-2">
                        <p class="font-medium text-gray-900">{{ __('Panduan Templat') }}</p>
                        <ul class="list-disc pl-5 space-y-1">
                            <li>{{ __('Urutan kolom: :header', ['header' => implode(', ', $expectedHeaders)]) }}</li>
                            <li>{{ __('Gunakan format tanggal YYYY-MM-DD, contoh: 1990-05-12.') }}</li>
                            <li>{{ __('Nopeg wajib diisi, maksimal 5 karakter, dan unik.') }}</li>
                            <li>{{ __('NIP bersifat opsional tetapi harus unik jika ada.') }}</li>
                            <li>{{ __('Baris kosong akan diabaikan saat proses review.') }}</li>
                        </ul>
                    </div>

                    <form method="POST" action="{{ route('pegawais.bulk.preview') }}" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        <div>
                            <label for="file" class="block text-sm font-medium text-gray-700">{{ __('File CSV') }}</label>
                            <input type="file" name="file" id="file" accept=".csv" class="mt-1 block w-full text-sm text-gray-900 file:me-4 file:rounded-md file:border-0 file:bg-indigo-600 file:px-4 file:py-2 file:text-sm file:font-semibold file:text-white hover:file:bg-indigo-500" required>
                            <p class="mt-1 text-xs text-gray-500">{{ __('Maksimal 2 MB. Format file wajib CSV.') }}</p>
                            <x-input-error :messages="$errors->get('file')" class="mt-2" />
                        </div>
                        <x-primary-button>
                            {{ __('Review Data') }}
                        </x-primary-button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
