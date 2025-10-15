<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 leading-tight">
			{{ __('Data Pegawai') }}
		</h2>
	</x-slot>

	<div class="py-12">
		<div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
			@if (session('status'))
				<div class="rounded-md bg-emerald-50 p-4">
					<div class="flex">
						<div class="flex-shrink-0">
							<svg class="h-5 w-5 text-emerald-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
								<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.857-9.809a.75.75 0 00-1.214-.882l-3.483 4.788-1.88-1.88a.75.75 0 10-1.06 1.061l2.5 2.5a.75.75 0 001.137-.089l4-5.5z" clip-rule="evenodd" />
							</svg>
						</div>
						<div class="ml-3 text-sm text-emerald-700">
							{{ session('status') }}
						</div>
					</div>
				</div>
			@endif

			@if (session('error'))
				<div class="rounded-md bg-rose-50 p-4">
					<div class="flex">
						<div class="flex-shrink-0">
							<svg class="h-5 w-5 text-rose-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
								<path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9 5a1 1 0 012 0v5a1 1 0 01-2 0V5zm1 8a1.25 1.25 0 100 2.5A1.25 1.25 0 0010 13z" clip-rule="evenodd" />
							</svg>
						</div>
						<div class="ml-3 text-sm text-rose-700">
							{{ session('error') }}
						</div>
					</div>
				</div>
			@endif

			<div class="bg-white shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 space-y-6">
					<div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
						<form method="GET" action="{{ route('pegawais.index') }}" class="w-full md:max-w-md">
							<label for="search" class="block text-sm font-medium text-gray-700">{{ __('Cari Pegawai') }}</label>
							<div class="mt-1 relative">
								<input type="text" name="search" id="search" class="block w-full rounded-md border-gray-300 pl-9 pr-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="{{ __('Cari nopeg, nama, atau NIP') }}" value="{{ $search }}">
								<span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
									<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<circle cx="11" cy="11" r="7" />
										<path d="m20 20-3.5-3.5" />
									</svg>
								</span>
							</div>
						</form>
						<div class="flex flex-wrap gap-3">
							<a href="{{ route('pegawais.import') }}" class="inline-flex items-center justify-center rounded-md border border-indigo-600 bg-white px-4 py-2 text-sm font-medium text-indigo-600 shadow-sm hover:bg-indigo-50">
								{{ __('Impor Pegawai') }}
							</a>
							<a href="{{ route('pegawais.create') }}" class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-500">
								{{ __('Tambah Pegawai') }}
							</a>
						</div>
					</div>

					<div class="border-t border-gray-200 pt-4">
						@if ($pegawais->count() > 0)
							<div class="overflow-x-auto">
								<table class="min-w-full divide-y divide-gray-200">
									<thead class="bg-gray-50">
										<tr>
											<th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Nopeg') }}</th>
											<th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Nama') }}</th>
											<th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('NIP') }}</th>
											<th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Jabatan') }}</th>
											<th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Unit Kerja') }}</th>
											<th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Tanggal Menjabat') }}</th>
											<th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Aksi') }}</th>
										</tr>
									</thead>
									<tbody class="bg-white divide-y divide-gray-200 text-sm">
										@foreach ($pegawais as $pegawai)
											<tr>
												<td class="px-4 py-3 font-medium text-gray-900">{{ $pegawai->nopeg }}</td>
												<td class="px-4 py-3 text-gray-900">{{ $pegawai->nama }}</td>
												<td class="px-4 py-3 text-gray-600">{{ $pegawai->nip ?? '—' }}</td>
												<td class="px-4 py-3 text-gray-600">{{ $pegawai->jabatan ?? '—' }}</td>
												<td class="px-4 py-3 text-gray-600">{{ $pegawai->unit_kerja ?? '—' }}</td>
												<td class="px-4 py-3 text-gray-600">{{ $pegawai->tanggal_menjabat?->format('d M Y') ?? '—' }}</td>
												<td class="px-4 py-3 text-right">
													<div class="flex items-center justify-end gap-3">
														<a href="{{ route('pegawais.edit', $pegawai) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
															{{ __('Edit') }}
														</a>
														<form method="POST" action="{{ route('pegawais.destroy', $pegawai) }}" onsubmit="return confirm('{{ __('Hapus pegawai ini?') }}');">
															@csrf
															@method('DELETE')
															<button type="submit" class="text-sm font-medium text-rose-600 hover:text-rose-800">
																{{ __('Hapus') }}
															</button>
														</form>
													</div>
												</td>
											</tr>
										@endforeach
									</tbody>
								</table>
							</div>

							<div class="pt-4">
								{{ $pegawais->onEachSide(1)->links('vendor.pagination.pegawai') }}
							</div>
						@else
							<div class="flex flex-col items-center justify-center gap-2 py-12 text-center">
								<h3 class="text-lg font-medium text-gray-900">{{ __('Belum ada data pegawai') }}</h3>
								<p class="text-sm text-gray-500">{{ __('Mulai dengan menambahkan data baru atau gunakan impor CSV untuk banyak data sekaligus.') }}</p>
							</div>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
</x-app-layout>
