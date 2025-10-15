<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Profile') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl space-y-4">
                    <h3 class="text-lg font-medium text-gray-900">{{ __('Informasi Akun') }}</h3>
                    <p class="text-sm text-gray-600">
                        {{ __('Data akun diambil dari informasi pegawai dan tidak dapat diedit di sini.') }}
                    </p>

                    <dl class="divide-y divide-gray-100 rounded-lg border border-gray-200">
                        <div class="flex items-center justify-between px-4 py-3">
                            <dt class="text-sm font-medium text-gray-700">{{ __('Nopeg') }}</dt>
                            <dd class="text-sm text-gray-900 font-semibold tracking-widest">{{ $user->pegawai_nopeg }}</dd>
                        </div>

                        <div class="flex items-center justify-between px-4 py-3">
                            <dt class="text-sm font-medium text-gray-700">{{ __('Nama Pegawai') }}</dt>
                            <dd class="text-sm text-gray-900">{{ $user->pegawai->nama ?? __('Tidak tersedia') }}</dd>
                        </div>

                        <div class="flex items-center justify-between px-4 py-3">
                            <dt class="text-sm font-medium text-gray-700">{{ __('Jabatan') }}</dt>
                            <dd class="text-sm text-gray-900">{{ $user->pegawai->jabatan ?? __('Tidak tersedia') }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
