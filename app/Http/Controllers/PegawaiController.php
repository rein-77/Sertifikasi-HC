<?php

namespace App\Http\Controllers;

use App\Http\Requests\PegawaiRequest;
use App\Models\Pegawai;
use Carbon\Carbon;
use Illuminate\Contracts\Filesystem\FileNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PegawaiController extends Controller
{
    public function index(Request $request)
    {
        $search = trim((string) $request->query('search', ''));

        $pegawais = Pegawai::query()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($inner) use ($search) {
                    $inner->where('nopeg', 'like', "%{$search}%")
                        ->orWhere('nama', 'like', "%{$search}%")
                        ->orWhere('nip', 'like', "%{$search}%");
                });
            })
            ->orderBy('nopeg')
            ->paginate(10)
            ->withQueryString();

        return view('pegawai.index', [
            'pegawais' => $pegawais,
            'search' => $search,
        ]);
    }

    public function create()
    {
        return view('pegawai.create');
    }

    public function import()
    {
        return view('pegawai.import', [
            'expectedHeaders' => $this->expectedHeaders(),
        ]);
    }

    public function store(PegawaiRequest $request)
    {
        Pegawai::create($request->validated());

        return redirect()
            ->route('pegawais.index')
            ->with('status', __('Pegawai berhasil ditambahkan.'));
    }

    public function edit(Pegawai $pegawai)
    {
        return view('pegawai.edit', compact('pegawai'));
    }

    public function update(PegawaiRequest $request, Pegawai $pegawai)
    {
        $pegawai->update($request->validated());

        return redirect()
            ->route('pegawais.index')
            ->with('status', __('Data pegawai berhasil diperbarui.'));
    }

    public function destroy(Pegawai $pegawai)
    {
        $pegawai->delete();

        return redirect()
            ->route('pegawais.index')
            ->with('status', __('Pegawai berhasil dihapus.'));
    }

    public function downloadTemplate(): StreamedResponse
    {
        $headers = $this->expectedHeaders();
        $sampleRows = [
            ['A0001', 'Sally Setiawan', '1987654321', '1990-05-12', 'Analis SDM', '2020-01-01', 'Divisi SDM'],
            ['A0002', 'Danu Priambodo', '1987654322', '1988-07-23', 'Supervisor Operasional', '2019-03-15', 'Unit Operasional']
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

        return response()->streamDownload($callback, 'template_pegawai.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function previewBulk(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:csv,txt', 'max:2048'],
        ]);

        $uploadedFile = $request->file('file');
        $handle = fopen($uploadedFile->getRealPath(), 'r');

        if ($handle === false) {
            throw ValidationException::withMessages([
                'file' => __('File tidak dapat dibaca. Silakan coba lagi.'),
            ]);
        }

        $expectedHeaders = $this->expectedHeaders();
        $header = null;
        $lineNumber = 0;
        $totalRows = 0;
        $validRows = [];
        $invalidRows = [];
        $cacheRows = [];
        $seenNopegs = [];
        $seenNips = [];

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
                        'file' => __('Header CSV tidak sesuai. Gunakan urutan: :header.', [
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
                    'file' => __('File tidak memiliki header yang valid.'),
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

            $nopeg = strtoupper($assoc['nopeg']);
            $nama = $assoc['nama'];
            $nip = $assoc['nip'] !== '' ? $assoc['nip'] : null;
            $tglLahir = $assoc['tgl_lahir'] !== '' ? $assoc['tgl_lahir'] : null;
            $jabatan = $assoc['jabatan'] !== '' ? $assoc['jabatan'] : null;
            $tanggalMenjabat = $assoc['tanggal_menjabat'] !== '' ? $assoc['tanggal_menjabat'] : null;
            $unitKerja = $assoc['unit_kerja'] !== '' ? $assoc['unit_kerja'] : null;

            if ($nopeg === '') {
                $errors[] = __('Nopeg wajib diisi.');
            } elseif (strlen($nopeg) > 5) {
                $errors[] = __('Nopeg maksimal 5 karakter.');
            } elseif (in_array($nopeg, $seenNopegs, true)) {
                $errors[] = __('Duplikasi Nopeg di dalam file.');
            } elseif (Pegawai::withTrashed()->where('nopeg', $nopeg)->exists()) {
                $errors[] = __('Nopeg sudah terdaftar.');
            }

            if ($nama === '') {
                $errors[] = __('Nama wajib diisi.');
            }

            if ($nip !== null) {
                if (strlen($nip) > 50) {
                    $errors[] = __('NIP maksimal 50 karakter.');
                } elseif (in_array($nip, $seenNips, true)) {
                    $errors[] = __('Duplikasi NIP di dalam file.');
                } elseif (Pegawai::withTrashed()->where('nip', $nip)->exists()) {
                    $errors[] = __('NIP sudah terdaftar.');
                }
            }

            $tglLahirParsed = null;
            if ($tglLahir !== null) {
                try {
                    $tglLahirParsed = Carbon::createFromFormat('Y-m-d', $tglLahir)->format('Y-m-d');
                } catch (\Throwable $exception) {
                    $errors[] = __('Tanggal lahir menggunakan format YYYY-MM-DD.');
                }
            }

            $tanggalMenjabatParsed = null;
            if ($tanggalMenjabat !== null) {
                try {
                    $tanggalMenjabatParsed = Carbon::createFromFormat('Y-m-d', $tanggalMenjabat)->format('Y-m-d');
                } catch (\Throwable $exception) {
                    $errors[] = __('Tanggal menjabat menggunakan format YYYY-MM-DD.');
                }
            }

            if ($jabatan !== null && strlen($jabatan) > 100) {
                $errors[] = __('Jabatan maksimal 100 karakter.');
            }

            if ($unitKerja !== null && strlen($unitKerja) > 100) {
                $errors[] = __('Unit kerja maksimal 100 karakter.');
            }

            if (count($errors) > 0) {
                $invalidRows[] = [
                    'line' => $lineNumber,
                    'nopeg' => $nopeg,
                    'nama' => $nama,
                    'nip' => $nip,
                    'tgl_lahir' => $tglLahir,
                    'jabatan' => $jabatan,
                    'tanggal_menjabat' => $tanggalMenjabat,
                    'unit_kerja' => $unitKerja,
                    'errors' => $errors,
                ];
                continue;
            }

            $seenNopegs[] = $nopeg;
            if ($nip !== null) {
                $seenNips[] = $nip;
            }

            $validRows[] = [
                'line' => $lineNumber,
                'nopeg' => $nopeg,
                'nama' => $nama,
                'nip' => $nip,
                'tgl_lahir' => $tglLahirParsed,
                'jabatan' => $jabatan,
                'tanggal_menjabat' => $tanggalMenjabatParsed,
                'unit_kerja' => $unitKerja,
            ];

            $cacheRows[] = [
                'nopeg' => $nopeg,
                'nama' => $nama,
                'nip' => $nip,
                'tgl_lahir' => $tglLahirParsed,
                'jabatan' => $jabatan,
                'tanggal_menjabat' => $tanggalMenjabatParsed,
                'unit_kerja' => $unitKerja,
            ];
        }

        fclose($handle);

        if ($totalRows === 0) {
            throw ValidationException::withMessages([
                'file' => __('File tidak berisi data pegawai.'),
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

        return view('pegawai.bulk-preview', [
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

        try {
            DB::transaction(function () use ($rows) {
                foreach ($rows as $row) {
                    Pegawai::create($row);
                }
            });
        } catch (QueryException $exception) {
            throw ValidationException::withMessages([
                'token' => __('Gagal menyimpan data impor. Pastikan data belum pernah ditambahkan dan coba lagi.'),
            ]);
        }

        return redirect()
            ->route('pegawais.index')
            ->with('status', __('Impor pegawai berhasil. :jumlah data ditambahkan.', ['jumlah' => count($rows)]));
    }

    protected function previewCacheKey(string $token): string
    {
        return 'pegawai-bulk-preview-' . $token;
    }

    protected function expectedHeaders(): array
    {
        return [
            'nopeg',
            'nama',
            'nip',
            'tgl_lahir',
            'jabatan',
            'tanggal_menjabat',
            'unit_kerja',
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
