<?php

namespace App\Http\Controllers;

use App\Http\Requests\SertifikatPegawaiRequest;
use App\Models\Pegawai;
use App\Models\Sertifikat;
use App\Models\SertifikatPegawai;
use Carbon\Carbon;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class SertifikatPegawaiController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));

        $sertifikatPegawais = SertifikatPegawai::query()
            ->with(['pegawai', 'sertifikat'])
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('pegawai_nopeg', 'like', "%{$search}%")
                        ->orWhere('sertifikat_kode', 'like', "%{$search}%")
                        ->orWhere('nomor_sertifikat', 'like', "%{$search}%")
                        ->orWhere('penyelenggara', 'like', "%{$search}%")
                        ->orWhereHas('pegawai', function ($pegawaiQuery) use ($search) {
                            $pegawaiQuery->where('nama', 'like', "%{$search}%");
                        })
                        ->orWhereHas('sertifikat', function ($sertifikatQuery) use ($search) {
                            $sertifikatQuery->where('bidang', 'like', "%{$search}%")
                                ->orWhere('nama_penerbit', 'like', "%{$search}%")
                                ->orWhere('kode_sertifikat', 'like', "%{$search}%");
                        });
                });
            })
            ->orderByDesc('tanggal_terbit')
            ->orderBy('pegawai_nopeg')
            ->paginate(10)
            ->withQueryString();

        return view('sertifikat_pegawai.index', [
            'sertifikatPegawais' => $sertifikatPegawais,
            'search' => $search,
        ]);
    }

    public function create()
    {
        return view('sertifikat_pegawai.create', [
            'pegawais' => Pegawai::orderBy('nama')->get(['nopeg', 'nama']),
            'sertifikats' => Sertifikat::orderBy('kode_sertifikat')->get(['kode_sertifikat', 'bidang', 'jenjang']),
        ]);
    }

    public function store(SertifikatPegawaiRequest $request)
    {
        SertifikatPegawai::create($request->validated());

        return redirect()
            ->route('sertifikat-pegawai.index')
            ->with('status', __('Sertifikat pegawai berhasil ditambahkan.'));
    }

    public function edit(SertifikatPegawai $sertifikatPegawai)
    {
        return view('sertifikat_pegawai.edit', [
            'sertifikatPegawai' => $sertifikatPegawai->load(['pegawai', 'sertifikat']),
            'pegawais' => Pegawai::orderBy('nama')->get(['nopeg', 'nama']),
            'sertifikats' => Sertifikat::orderBy('kode_sertifikat')->get(['kode_sertifikat', 'bidang', 'jenjang']),
        ]);
    }

    public function update(SertifikatPegawaiRequest $request, SertifikatPegawai $sertifikatPegawai)
    {
        $sertifikatPegawai->update($request->validated());

        return redirect()
            ->route('sertifikat-pegawai.index')
            ->with('status', __('Data sertifikat pegawai berhasil diperbarui.'));
    }

    public function destroy(SertifikatPegawai $sertifikatPegawai)
    {
        $sertifikatPegawai->delete();

        return redirect()
            ->route('sertifikat-pegawai.index')
            ->with('status', __('Sertifikat pegawai berhasil dihapus.'));
    }

    public function import()
    {
        return view('sertifikat_pegawai.import', [
            'expectedHeaders' => $this->expectedHeaders(),
        ]);
    }

    public function downloadTemplate(): StreamedResponse
    {
        $headers = $this->expectedHeaders();
        $sampleRows = [
            ['A0001', 'STR-001', '2023/STR/001', 'REG-01', '2023-01-12', '2025-01-11', 'Kementerian Kesehatan'],
            ['A0002', 'HSE-101', '', '', '2022-09-01', '2024-09-01', 'PT. Sigma Safety'],
            ['A0003', 'ISO-9001', 'ISO/2024/123', '', '2024-01-01', '', '']
        ];

        $callback = static function () use ($headers, $sampleRows) {
            $handle = fopen('php://output', 'w');
            if ($handle === false) {
                throw new FileNotFoundException('Tidak dapat membuat file template.');
            }

            fputcsv($handle, $headers);
            foreach ($sampleRows as $row) {
                fputcsv($handle, $row);
            }

            fclose($handle);
        };

        return response()->streamDownload($callback, 'template_sertifikat_pegawai.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function previewBulk(Request $request)
    {
        $request->validate([
            'csv_file' => ['required', 'file', 'mimes:csv,txt', 'max:2048'],
        ]);

        $uploadedFile = $request->file('csv_file');
        $handle = fopen($uploadedFile->getRealPath(), 'r');

        if ($handle === false) {
            throw ValidationException::withMessages([
                'csv_file' => __('File tidak dapat dibaca. Silakan coba lagi.'),
            ]);
        }

        $expectedHeaders = $this->expectedHeaders();
        $header = null;
        $lineNumber = 0;
        $totalRows = 0;
        $validRows = [];
        $invalidRows = [];
        $cacheRows = [];
        $seenKeys = [];
        $pegawaiCache = [];
        $sertifikatCache = [];

        while (($row = fgetcsv($handle)) !== false) {
            $lineNumber++;

            if ($lineNumber === 1) {
                if (isset($row[0])) {
                    $row[0] = preg_replace('/^\xEF\xBB\xBF/', '', $row[0]);
                }

                $normalizedHeader = array_map(static fn ($value) => strtolower(trim((string) $value)), $row);

                if ($normalizedHeader !== $expectedHeaders) {
                    fclose($handle);

                    throw ValidationException::withMessages([
                        'csv_file' => __('Header CSV tidak sesuai. Gunakan urutan: :header.', [
                            'header' => implode(', ', $expectedHeaders),
                        ]),
                    ]);
                }

                $header = $normalizedHeader;
                continue;
            }

            if ($header === null) {
                fclose($handle);

                throw ValidationException::withMessages([
                    'csv_file' => __('File tidak memiliki header yang valid.'),
                ]);
            }

            $row = array_pad($row, count($header), null);
            $assoc = [];
            foreach ($header as $index => $column) {
                $assoc[$column] = trim((string) ($row[$index] ?? ''));
            }

            if ($this->rowIsEmpty($assoc)) {
                continue;
            }

            $totalRows++;
            $errors = [];

            $pegawaiNopeg = strtoupper($assoc['pegawai_nopeg']);
            $sertifikatKode = strtoupper($assoc['sertifikat_kode']);
            $noReg = $assoc['no_reg_sertifikat'] !== '' ? $assoc['no_reg_sertifikat'] : null;
            $nomorSertifikat = $assoc['nomor_sertifikat'] !== '' ? $assoc['nomor_sertifikat'] : null;
            $tanggalTerbit = $assoc['tanggal_terbit'];
            $tanggalExpire = $assoc['tanggal_expire'] !== '' ? $assoc['tanggal_expire'] : null;
            $penyelenggara = $assoc['penyelenggara'] !== '' ? $assoc['penyelenggara'] : null;

            if ($pegawaiNopeg === '') {
                $errors[] = __('Nopeg pegawai wajib diisi.');
            } elseif (strlen($pegawaiNopeg) > 5) {
                $errors[] = __('Nopeg maksimal 5 karakter.');
            } else {
                if (!array_key_exists($pegawaiNopeg, $pegawaiCache)) {
                    $pegawaiCache[$pegawaiNopeg] = Pegawai::select('nopeg', 'nama')
                        ->where('nopeg', $pegawaiNopeg)
                        ->first();
                }

                if ($pegawaiCache[$pegawaiNopeg] === null) {
                    $errors[] = __('Pegawai dengan nopeg :nopeg tidak ditemukan.', ['nopeg' => $pegawaiNopeg]);
                }
            }

            if ($sertifikatKode === '') {
                $errors[] = __('Kode sertifikat wajib diisi.');
            } elseif (strlen($sertifikatKode) > 50) {
                $errors[] = __('Kode sertifikat maksimal 50 karakter.');
            } else {
                if (!array_key_exists($sertifikatKode, $sertifikatCache)) {
                    $sertifikatCache[$sertifikatKode] = Sertifikat::select('kode_sertifikat', 'bidang', 'jenjang')
                        ->where('kode_sertifikat', $sertifikatKode)
                        ->first();
                }

                if ($sertifikatCache[$sertifikatKode] === null) {
                    $errors[] = __('Sertifikat dengan kode :kode tidak ditemukan.', ['kode' => $sertifikatKode]);
                }
            }

            if ($nomorSertifikat !== null && strlen($nomorSertifikat) > 100) {
                $errors[] = __('Nomor sertifikat maksimal 100 karakter.');
            }

            if ($noReg !== null && strlen($noReg) > 100) {
                $errors[] = __('No. registrasi maksimal 100 karakter.');
            }

            if ($penyelenggara !== null && strlen($penyelenggara) > 150) {
                $errors[] = __('Penyelenggara maksimal 150 karakter.');
            }

            $tanggalTerbitParsed = null;
            if ($tanggalTerbit === '') {
                $errors[] = __('Tanggal terbit wajib diisi.');
            } else {
                try {
                    $tanggalTerbitParsed = Carbon::createFromFormat('Y-m-d', $tanggalTerbit)->format('Y-m-d');
                } catch (\Throwable $exception) {
                    $errors[] = __('Tanggal terbit menggunakan format YYYY-MM-DD.');
                }
            }

            $tanggalExpireParsed = null;
            if ($tanggalExpire !== null) {
                try {
                    $tanggalExpireParsed = Carbon::createFromFormat('Y-m-d', $tanggalExpire)->format('Y-m-d');

                    if ($tanggalTerbitParsed !== null && $tanggalExpireParsed < $tanggalTerbitParsed) {
                        $errors[] = __('Tanggal expire tidak boleh sebelum tanggal terbit.');
                    }
                } catch (\Throwable $exception) {
                    $errors[] = __('Tanggal expire menggunakan format YYYY-MM-DD.');
                }
            }

            $key = $pegawaiNopeg.'|'.$sertifikatKode.'|'.mb_strtolower($nomorSertifikat ?? '');
            if (in_array($key, $seenKeys, true)) {
                $errors[] = __('Duplikasi data sertifikat pegawai di dalam file.');
            } else {
                $seenKeys[] = $key;
            }

            if ($pegawaiCache[$pegawaiNopeg] !== null && $sertifikatCache[$sertifikatKode] !== null && $nomorSertifikat !== null) {
                $exists = SertifikatPegawai::where('pegawai_nopeg', $pegawaiNopeg)
                    ->where('sertifikat_kode', $sertifikatKode)
                    ->where('nomor_sertifikat', $nomorSertifikat)
                    ->exists();

                if ($exists) {
                    $errors[] = __('Data sertifikat pegawai sudah terdaftar.');
                }
            }

            if (count($errors) > 0) {
                $invalidRows[] = [
                    'line' => $lineNumber,
                    'pegawai_nopeg' => $pegawaiNopeg,
                    'pegawai_nama' => $pegawaiCache[$pegawaiNopeg]->nama ?? null,
                    'sertifikat_kode' => $sertifikatKode,
                    'bidang' => $sertifikatCache[$sertifikatKode]->bidang ?? null,
                    'nomor_sertifikat' => $nomorSertifikat,
                    'no_reg_sertifikat' => $noReg,
                    'tanggal_terbit' => $tanggalTerbit,
                    'tanggal_expire' => $tanggalExpire,
                    'penyelenggara' => $penyelenggara,
                    'errors' => $errors,
                ];
                continue;
            }

            $validRows[] = [
                'line' => $lineNumber,
                'pegawai_nopeg' => $pegawaiNopeg,
                'pegawai_nama' => $pegawaiCache[$pegawaiNopeg]->nama ?? null,
                'sertifikat_kode' => $sertifikatKode,
                'bidang' => $sertifikatCache[$sertifikatKode]->bidang ?? null,
                'nomor_sertifikat' => $nomorSertifikat,
                'no_reg_sertifikat' => $noReg,
                'tanggal_terbit' => $tanggalTerbitParsed,
                'tanggal_expire' => $tanggalExpireParsed,
                'penyelenggara' => $penyelenggara,
            ];

            $cacheRows[] = [
                'pegawai_nopeg' => $pegawaiNopeg,
                'sertifikat_kode' => $sertifikatKode,
                'no_reg_sertifikat' => $noReg,
                'nomor_sertifikat' => $nomorSertifikat,
                'tanggal_terbit' => $tanggalTerbitParsed,
                'tanggal_expire' => $tanggalExpireParsed,
                'penyelenggara' => $penyelenggara,
            ];
        }

        fclose($handle);

        if ($totalRows === 0) {
            throw ValidationException::withMessages([
                'csv_file' => __('File tidak berisi data sertifikat pegawai.'),
            ]);
        }

        $token = null;
        if (count($cacheRows) > 0) {
            $token = (string) Str::uuid();
            Cache::put($this->previewCacheKey($token), [
                'rows' => $cacheRows,
                'user_id' => $request->user()?->getKey(),
            ], now()->addMinutes(10));
        }

        return view('sertifikat_pegawai.bulk-preview', [
            'validRows' => $validRows,
            'invalidRows' => $invalidRows,
            'stats' => [
                'total' => $totalRows,
                'valid' => count($validRows),
                'invalid' => count($invalidRows),
            ],
            'token' => $token,
            'expectedHeaders' => $expectedHeaders,
        ]);
    }

    public function confirmBulk(Request $request)
    {
        $data = $request->validate([
            'token' => ['required', 'string'],
        ]);

        $cacheKey = $this->previewCacheKey($data['token']);
        $payload = Cache::pull($cacheKey);

        if (!$payload || empty($payload['rows'])) {
            throw ValidationException::withMessages([
                'token' => __('Sesi impor tidak ditemukan atau sudah kadaluarsa. Silakan ulangi proses impor.'),
            ]);
        }

        $rows = $payload['rows'];

        DB::transaction(function () use ($rows) {
            foreach ($rows as $row) {
                SertifikatPegawai::create($row);
            }
        });

        return redirect()
            ->route('sertifikat-pegawai.index')
            ->with('status', __('Impor sertifikat pegawai berhasil. :jumlah data ditambahkan.', ['jumlah' => count($rows)]));
    }

    protected function previewCacheKey(string $token): string
    {
        return 'sertifikat-pegawai-bulk-preview-' . $token;
    }

    protected function expectedHeaders(): array
    {
        return [
            'pegawai_nopeg',
            'sertifikat_kode',
            'nomor_sertifikat',
            'no_reg_sertifikat',
            'tanggal_terbit',
            'tanggal_expire',
            'penyelenggara',
        ];
    }

    protected function rowIsEmpty(array $row): bool
    {
        foreach ($row as $value) {
            if ($value !== null && trim((string) $value) !== '') {
                return false;
            }
        }

        return true;
    }
}
