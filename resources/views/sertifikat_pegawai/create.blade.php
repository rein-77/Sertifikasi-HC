<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Tambah Sertifikat Pegawai') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <form method="POST" action="{{ route('sertifikat-pegawai.store') }}" class="space-y-6">
                        @csrf

                        @include('sertifikat_pegawai.partials.form-fields', [
                            'sertifikatPegawai' => null,
                            'pegawais' => $pegawais,
                            'sertifikats' => $sertifikats,
                        ])

                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('sertifikat-pegawai.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                {{ __('Batal') }}
                            </a>
                            <x-primary-button>
                                {{ __('Simpan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
