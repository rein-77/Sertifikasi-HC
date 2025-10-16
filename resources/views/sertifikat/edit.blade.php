<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Edit Sertifikat') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 space-y-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-medium text-gray-900">{{ $sertifikat->kode_sertifikat }}</h3>
                            <p class="text-sm text-gray-500">{{ $sertifikat->bidang ?? __('Tanpa bidang') }}</p>
                        </div>
                        <form method="POST" action="{{ route('sertifikats.destroy', $sertifikat) }}" onsubmit="return confirm('{{ __('Hapus sertifikat ini?') }}');">
                            @csrf
                            @method('DELETE')
                            <x-danger-button>
                                {{ __('Hapus') }}
                            </x-danger-button>
                        </form>
                    </div>

                    <form method="POST" action="{{ route('sertifikats.update', $sertifikat) }}" class="space-y-6">
                        @csrf
                        @method('PUT')

                        @include('sertifikat.partials.form-fields', ['sertifikat' => $sertifikat])

                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('sertifikats.index') }}" class="text-sm text-gray-600 hover:text-gray-900">
                                {{ __('Batal') }}
                            </a>
                            <x-primary-button>
                                {{ __('Simpan Perubahan') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
