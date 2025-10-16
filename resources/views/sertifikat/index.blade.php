<x-app-layout>
	<x-slot name="header">
		<h2 class="font-semibold text-xl text-gray-800 leading-tight">
			{{ __('Data Sertifikat') }}
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

			<div class="bg-white shadow-sm sm:rounded-lg">
				<div class="p-6 text-gray-900 space-y-6">
					<div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
						<form method="GET" action="{{ route('sertifikats.index') }}" class="w-full md:max-w-md">
							<label for="search" class="block text-sm font-medium text-gray-700">{{ __('Cari Sertifikat') }}</label>
							<div class="mt-1 relative">
								<input type="text" name="search" id="search" class="block w-full rounded-md border-gray-300 pl-9 pr-3 py-2 text-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="{{ __('Cari kode, bidang, jenjang, atau penerbit') }}" value="{{ $search }}">
								<span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
									<svg class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
										<circle cx="11" cy="11" r="7" />
										<path d="m20 20-3.5-3.5" />
									</svg>
								</span>
							</div>
						</form>

						<div class="flex gap-3">
							<a href="{{ route('sertifikats.create') }}" class="inline-flex items-center justify-center rounded-md bg-indigo-600 px-4 py-2 text-sm font-medium text-white shadow-sm hover:bg-indigo-500">
								{{ __('Tambah Sertifikat') }}
							</a>
						</div>
					</div>

					<div class="border-t border-gray-200 pt-4">
						@if ($sertifikats->count() > 0)
							<div class="overflow-x-auto">
								<table class="min-w-full divide-y divide-gray-200">
									<thead class="bg-gray-50">
										<tr>
											<th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Kode') }}</th>
											<th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Bidang') }}</th>
											<th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Jenjang') }}</th>
											<th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Penerbit') }}</th>
											<th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Keterangan') }}</th>
											<th scope="col" class="px-4 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">{{ __('Aksi') }}</th>
										</tr>
									</thead>
									<tbody class="bg-white divide-y divide-gray-200 text-sm">
										@foreach ($sertifikats as $sertifikat)
											<tr>
												<td class="px-4 py-3 font-medium text-gray-900">{{ $sertifikat->kode_sertifikat }}</td>
												<td class="px-4 py-3 text-gray-600">{{ $sertifikat->bidang ?? '—' }}</td>
												<td class="px-4 py-3 text-gray-600">{{ $sertifikat->jenjang ?? '—' }}</td>
												<td class="px-4 py-3 text-gray-600">{{ $sertifikat->nama_penerbit ?? '—' }}</td>
												<td class="px-4 py-3 text-gray-600 max-w-xs">
													<span class="line-clamp-2">{{ $sertifikat->keterangan ? \Illuminate\Support\Str::limit($sertifikat->keterangan, 80) : '—' }}</span>
												</td>
												<td class="px-4 py-3 text-right">
													<div class="flex items-center justify-end gap-3">
														<a href="{{ route('sertifikats.edit', $sertifikat) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
															{{ __('Edit') }}
														</a>
														<form method="POST" action="{{ route('sertifikats.destroy', $sertifikat) }}" onsubmit="return confirm('{{ __('Hapus sertifikat ini?') }}');">
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
								{{ $sertifikats->onEachSide(1)->links('vendor.pagination.table') }}
							</div>
						@else
							<div class="flex flex-col items-center justify-center gap-2 py-12 text-center">
								<h3 class="text-lg font-medium text-gray-900">{{ __('Belum ada data sertifikat') }}</h3>
								<p class="text-sm text-gray-500">{{ __('Tambahkan sertifikat baru untuk mulai mengelola data.') }}</p>
							</div>
						@endif
					</div>
				</div>
			</div>
		</div>
	</div>
</x-app-layout>
