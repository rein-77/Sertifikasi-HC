<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Review Impor Sertifikat Pegawai') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-4">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">{{ __('Ringkasan Data') }}</h3>
                            <p class="text-sm text-gray-500">
                                {{ __('Periksa kembali data sebelum dikonfirmasi. Token impor berlaku selama 10 menit.') }}
                            </p>
                        </div>
                        <div class="flex gap-4">
                            <div class="text-center">
                                <p class="text-sm text-gray-500">{{ __('Total Baris') }}</p>
                                <p class="text-xl font-semibold text-gray-900">{{ $stats['total'] }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-sm text-gray-500">{{ __('Valid') }}</p>
                                <p class="text-xl font-semibold text-emerald-600">{{ $stats['valid'] }}</p>
                            </div>
                            <div class="text-center">
                                <p class="text-sm text-gray-500">{{ __('Perlu Perbaikan') }}</p>
                                <p class="text-xl font-semibold text-amber-600">{{ $stats['invalid'] }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="rounded-md bg-blue-50 p-4">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-8.75-3.5a.75.75 0 000 1.5h1.5a.75.75 0 000-1.5h-1.5zM10 8.5a.75.75 0 01.75.75v4a.75.75 0 01-1.5 0v-4A.75.75 0 0110 8.5z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3 text-sm text-blue-700">
                                <p>{{ __('Pastikan urutan kolom sesuai templat: :header', ['header' => implode(', ', $expectedHeaders)]) }}</p>
                                <p class="mt-1">{{ __('Gunakan format tanggal YYYY-MM-DD. Baris yang kosong otomatis diabaikan.') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            @if (count($invalidRows) > 0)
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('Baris yang Perlu Diperbaiki') }}</h3>
                        <p class="text-sm text-gray-500">{{ __('Perbaiki baris berikut dan unggah ulang agar dapat diproses.') }}</p>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Baris') }}</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Nopeg') }}</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Kode Sertifikat') }}</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Nomor Sertifikat') }}</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Catatan') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 text-sm">
                                    @foreach ($invalidRows as $row)
                                        <tr class="align-top">
                                            <td class="px-4 py-3 text-gray-600">{{ $row['line'] }}</td>
                                            <td class="px-4 py-3 text-gray-900 font-medium">{{ $row['pegawai_nopeg'] }}</td>
                                            <td class="px-4 py-3 text-gray-900">{{ $row['sertifikat_kode'] }}</td>
                                            <td class="px-4 py-3 text-gray-900">{{ $row['nomor_sertifikat'] }}</td>
                                            <td class="px-4 py-3">
                                                <ul class="list-disc pl-5 text-gray-700 space-y-1">
                                                    @foreach ($row['errors'] as $error)
                                                        <li>{{ $error }}</li>
                                                    @endforeach
                                                </ul>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif

            <div class="flex items-center justify-between">
                <a href="{{ route('sertifikat-pegawai.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                    {{ __('Kembali ke daftar sertifikat pegawai') }}
                </a>

                @if ($token && count($validRows) > 0)
                    <form method="POST" action="{{ route('sertifikat-pegawai.bulk.confirm') }}" class="flex flex-col items-end gap-2 sm:flex-row sm:items-center">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <x-primary-button>
                            {{ __('Konfirmasi dan Impor (:jumlah)', ['jumlah' => count($validRows)]) }}
                        </x-primary-button>
                        <x-input-error :messages="$errors->get('token')" />
                    </form>
                @endif
            </div>

            @if (count($validRows) > 0)
                <div class="bg-white shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 space-y-4">
                        <h3 class="text-lg font-medium text-gray-900">{{ __('Data Siap Diimpor') }}</h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Baris') }}</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Nopeg') }}</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Nama Pegawai') }}</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Kode Sertifikat') }}</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Nomor Sertifikat') }}</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Tanggal Terbit') }}</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Tanggal Expire') }}</th>
                                        <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Penyelenggara') }}</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200 text-sm">
                                    @foreach ($validRows as $row)
                                        <tr>
                                            <td class="px-4 py-3 text-gray-600">{{ $row['line'] }}</td>
                                            <td class="px-4 py-3 font-medium text-gray-900">{{ $row['pegawai_nopeg'] }}</td>
                                            <td class="px-4 py-3 text-gray-900">{{ $row['pegawai_nama'] ?? '—' }}</td>
                                            <td class="px-4 py-3 text-gray-900">{{ $row['sertifikat_kode'] }}</td>
                                            <td class="px-4 py-3 text-gray-900">{{ $row['nomor_sertifikat'] ?? '—' }}</td>
                                            <td class="px-4 py-3 text-gray-900">{{ $row['tanggal_terbit'] }}</td>
                                            <td class="px-4 py-3 text-gray-900">{{ $row['tanggal_expire'] ?? '—' }}</td>
                                            <td class="px-4 py-3 text-gray-900 max-w-xs">
                                                <span class="line-clamp-2">{{ $row['penyelenggara'] ?? '—' }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
