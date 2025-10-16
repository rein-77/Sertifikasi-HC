<?php

use App\Models\Pegawai;
use App\Models\Sertifikat;
use App\Models\SertifikatPegawai;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    // Create a test user with pegawai
    $this->pegawai = Pegawai::factory()->create([
        'nopeg' => 'T0001',
        'nama' => 'Test User',
    ]);

    $this->user = User::factory()->create([
        'pegawai_nopeg' => $this->pegawai->nopeg,
    ]);

    // Create test pegawais for assignments
    $this->testPegawai1 = Pegawai::factory()->create([
        'nopeg' => 'P0001',
        'nama' => 'Pegawai Test 1',
    ]);

    $this->testPegawai2 = Pegawai::factory()->create([
        'nopeg' => 'P0002',
        'nama' => 'Pegawai Test 2',
    ]);

    // Create test sertifikats
    $this->testSertifikat1 = Sertifikat::create([
        'kode_sertifikat' => 'CERT-001',
        'bidang' => 'Kesehatan',
        'jenjang' => 'Dasar',
        'nama_penerbit' => 'Kemenkes',
    ]);

    $this->testSertifikat2 = Sertifikat::create([
        'kode_sertifikat' => 'CERT-002',
        'bidang' => 'Keselamatan Kerja',
        'jenjang' => 'Lanjut',
        'nama_penerbit' => 'K3',
    ]);
});

test('sertifikat pegawai index page can be rendered', function () {
    $response = $this->actingAs($this->user)->get(route('sertifikat-pegawai.index'));

    $response->assertOk();
    $response->assertViewIs('sertifikat_pegawai.index');
    $response->assertViewHas('sertifikatPegawais');
    $response->assertSee('Sertifikat Pegawai');
});

test('sertifikat pegawai index displays data correctly', function () {
    $sertifikatPegawai = SertifikatPegawai::create([
        'pegawai_nopeg' => $this->testPegawai1->nopeg,
        'sertifikat_kode' => $this->testSertifikat1->kode_sertifikat,
        'nomor_sertifikat' => 'NO-12345',
        'no_reg_sertifikat' => 'REG-001',
        'tanggal_terbit' => '2024-01-15',
        'tanggal_expire' => '2026-01-15',
        'penyelenggara' => 'Test Organizer',
    ]);

    $response = $this->actingAs($this->user)->get(route('sertifikat-pegawai.index'));

    $response->assertOk();
    $response->assertSee($this->testPegawai1->nopeg);
    $response->assertSee($this->testPegawai1->nama);
    $response->assertSee($this->testSertifikat1->kode_sertifikat);
    $response->assertSee('NO-12345');
});

test('sertifikat pegawai index can search by nopeg', function () {
    SertifikatPegawai::create([
        'pegawai_nopeg' => $this->testPegawai1->nopeg,
        'sertifikat_kode' => $this->testSertifikat1->kode_sertifikat,
        'nomor_sertifikat' => 'SEARCH-001',
        'tanggal_terbit' => '2024-01-15',
    ]);

    SertifikatPegawai::create([
        'pegawai_nopeg' => $this->testPegawai2->nopeg,
        'sertifikat_kode' => $this->testSertifikat2->kode_sertifikat,
        'nomor_sertifikat' => 'SEARCH-002',
        'tanggal_terbit' => '2024-01-15',
    ]);

    $response = $this->actingAs($this->user)->get(route('sertifikat-pegawai.index', ['search' => 'P0001']));

    $response->assertOk();
    $response->assertSee('SEARCH-001');
    $response->assertDontSee('SEARCH-002');
});

test('sertifikat pegawai index can search by nomor sertifikat', function () {
    SertifikatPegawai::create([
        'pegawai_nopeg' => $this->testPegawai1->nopeg,
        'sertifikat_kode' => $this->testSertifikat1->kode_sertifikat,
        'nomor_sertifikat' => 'UNIQUE-123',
        'tanggal_terbit' => '2024-01-15',
    ]);

    $response = $this->actingAs($this->user)->get(route('sertifikat-pegawai.index', ['search' => 'UNIQUE-123']));

    $response->assertOk();
    $response->assertSee('UNIQUE-123');
});

test('sertifikat pegawai create page can be rendered', function () {
    $response = $this->actingAs($this->user)->get(route('sertifikat-pegawai.create'));

    $response->assertOk();
    $response->assertViewIs('sertifikat_pegawai.create');
    $response->assertViewHas('pegawais');
    $response->assertViewHas('sertifikats');
    $response->assertSee('Tambah Sertifikat Pegawai');
});

test('sertifikat pegawai can be created', function () {
    $data = [
        'pegawai_nopeg' => $this->testPegawai1->nopeg,
        'sertifikat_kode' => $this->testSertifikat1->kode_sertifikat,
        'nomor_sertifikat' => 'NEW-12345',
        'no_reg_sertifikat' => 'REG-NEW-001',
        'tanggal_terbit' => '2024-06-01',
        'tanggal_expire' => '2026-06-01',
        'penyelenggara' => 'New Organizer',
    ];

    $response = $this->actingAs($this->user)->post(route('sertifikat-pegawai.store'), $data);

    $response->assertRedirect(route('sertifikat-pegawai.index'));
    $response->assertSessionHas('status');

    $this->assertDatabaseHas('sertifikat_pegawai', [
        'pegawai_nopeg' => $this->testPegawai1->nopeg,
        'sertifikat_kode' => $this->testSertifikat1->kode_sertifikat,
        'nomor_sertifikat' => 'NEW-12345',
    ]);
});

test('sertifikat pegawai can be created with nullable fields', function () {
    $data = [
        'pegawai_nopeg' => $this->testPegawai1->nopeg,
        'sertifikat_kode' => $this->testSertifikat1->kode_sertifikat,
        'tanggal_terbit' => '2024-06-01',
    ];

    $response = $this->actingAs($this->user)->post(route('sertifikat-pegawai.store'), $data);

    $response->assertRedirect(route('sertifikat-pegawai.index'));

    $this->assertDatabaseHas('sertifikat_pegawai', [
        'pegawai_nopeg' => $this->testPegawai1->nopeg,
        'sertifikat_kode' => $this->testSertifikat1->kode_sertifikat,
        'nomor_sertifikat' => null,
        'no_reg_sertifikat' => null,
        'tanggal_expire' => null,
        'penyelenggara' => null,
    ]);
});

test('sertifikat pegawai requires pegawai_nopeg', function () {
    $data = [
        'sertifikat_kode' => $this->testSertifikat1->kode_sertifikat,
        'nomor_sertifikat' => 'TEST-001',
        'tanggal_terbit' => '2024-01-15',
    ];

    $response = $this->actingAs($this->user)->post(route('sertifikat-pegawai.store'), $data);

    $response->assertSessionHasErrors(['pegawai_nopeg']);
});

test('sertifikat pegawai requires sertifikat_kode', function () {
    $data = [
        'pegawai_nopeg' => $this->testPegawai1->nopeg,
        'nomor_sertifikat' => 'TEST-001',
        'tanggal_terbit' => '2024-01-15',
    ];

    $response = $this->actingAs($this->user)->post(route('sertifikat-pegawai.store'), $data);

    $response->assertSessionHasErrors(['sertifikat_kode']);
});

test('sertifikat pegawai requires tanggal_terbit', function () {
    $data = [
        'pegawai_nopeg' => $this->testPegawai1->nopeg,
        'sertifikat_kode' => $this->testSertifikat1->kode_sertifikat,
        'nomor_sertifikat' => 'TEST-001',
    ];

    $response = $this->actingAs($this->user)->post(route('sertifikat-pegawai.store'), $data);

    $response->assertSessionHasErrors(['tanggal_terbit']);
});

test('sertifikat pegawai validates pegawai_nopeg exists', function () {
    $data = [
        'pegawai_nopeg' => 'XXXXX',
        'sertifikat_kode' => $this->testSertifikat1->kode_sertifikat,
        'nomor_sertifikat' => 'TEST-001',
        'tanggal_terbit' => '2024-01-15',
    ];

    $response = $this->actingAs($this->user)->post(route('sertifikat-pegawai.store'), $data);

    $response->assertSessionHasErrors(['pegawai_nopeg']);
});

test('sertifikat pegawai validates sertifikat_kode exists', function () {
    $data = [
        'pegawai_nopeg' => $this->testPegawai1->nopeg,
        'sertifikat_kode' => 'INVALID-CODE',
        'nomor_sertifikat' => 'TEST-001',
        'tanggal_terbit' => '2024-01-15',
    ];

    $response = $this->actingAs($this->user)->post(route('sertifikat-pegawai.store'), $data);

    $response->assertSessionHasErrors(['sertifikat_kode']);
});

test('sertifikat pegawai validates tanggal_expire after tanggal_terbit', function () {
    $data = [
        'pegawai_nopeg' => $this->testPegawai1->nopeg,
        'sertifikat_kode' => $this->testSertifikat1->kode_sertifikat,
        'nomor_sertifikat' => 'TEST-001',
        'tanggal_terbit' => '2024-12-31',
        'tanggal_expire' => '2024-01-01',
    ];

    $response = $this->actingAs($this->user)->post(route('sertifikat-pegawai.store'), $data);

    $response->assertSessionHasErrors(['tanggal_expire']);
});

test('sertifikat pegawai edit page can be rendered', function () {
    $sertifikatPegawai = SertifikatPegawai::create([
        'pegawai_nopeg' => $this->testPegawai1->nopeg,
        'sertifikat_kode' => $this->testSertifikat1->kode_sertifikat,
        'nomor_sertifikat' => 'EDIT-001',
        'tanggal_terbit' => '2024-01-15',
    ]);

    $response = $this->actingAs($this->user)->get(route('sertifikat-pegawai.edit', $sertifikatPegawai));

    $response->assertOk();
    $response->assertViewIs('sertifikat_pegawai.edit');
    $response->assertViewHas('sertifikatPegawai');
    $response->assertSee('EDIT-001');
});

test('sertifikat pegawai can be updated', function () {
    $sertifikatPegawai = SertifikatPegawai::create([
        'pegawai_nopeg' => $this->testPegawai1->nopeg,
        'sertifikat_kode' => $this->testSertifikat1->kode_sertifikat,
        'nomor_sertifikat' => 'OLD-001',
        'tanggal_terbit' => '2024-01-15',
    ]);

    $updateData = [
        'pegawai_nopeg' => $this->testPegawai1->nopeg,
        'sertifikat_kode' => $this->testSertifikat1->kode_sertifikat,
        'nomor_sertifikat' => 'UPDATED-001',
        'no_reg_sertifikat' => 'REG-UPDATED',
        'tanggal_terbit' => '2024-06-01',
        'tanggal_expire' => '2026-06-01',
        'penyelenggara' => 'Updated Organizer',
    ];

    $response = $this->actingAs($this->user)->put(route('sertifikat-pegawai.update', $sertifikatPegawai), $updateData);

    $response->assertRedirect(route('sertifikat-pegawai.index'));
    $response->assertSessionHas('status');

    $this->assertDatabaseHas('sertifikat_pegawai', [
        'id' => $sertifikatPegawai->id,
        'nomor_sertifikat' => 'UPDATED-001',
        'penyelenggara' => 'Updated Organizer',
    ]);
});

test('sertifikat pegawai can be deleted', function () {
    $sertifikatPegawai = SertifikatPegawai::create([
        'pegawai_nopeg' => $this->testPegawai1->nopeg,
        'sertifikat_kode' => $this->testSertifikat1->kode_sertifikat,
        'nomor_sertifikat' => 'DELETE-001',
        'tanggal_terbit' => '2024-01-15',
    ]);

    $response = $this->actingAs($this->user)->delete(route('sertifikat-pegawai.destroy', $sertifikatPegawai));

    $response->assertRedirect(route('sertifikat-pegawai.index'));
    $response->assertSessionHas('status');

    $this->assertSoftDeleted('sertifikat_pegawai', [
        'id' => $sertifikatPegawai->id,
    ]);
});

test('sertifikat pegawai import page can be rendered', function () {
    $response = $this->actingAs($this->user)->get(route('sertifikat-pegawai.import'));

    $response->assertOk();
    $response->assertViewIs('sertifikat_pegawai.import');
    $response->assertSee('Impor Sertifikat Pegawai');
    $response->assertSee('Download Template CSV');
});

test('sertifikat pegawai template can be downloaded', function () {
    $response = $this->actingAs($this->user)->get(route('sertifikat-pegawai.template'));

    $response->assertOk();
    $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
    $response->assertDownload('template_sertifikat_pegawai.csv');
});

test('sertifikat pegawai bulk import validates csv file required', function () {
    $response = $this->actingAs($this->user)->post(route('sertifikat-pegawai.bulk.preview'), []);

    $response->assertSessionHasErrors(['csv_file']);
});

test('sertifikat pegawai bulk import validates csv mime type', function () {
    Storage::fake('local');
    $file = UploadedFile::fake()->create('test.txt', 100);

    $response = $this->actingAs($this->user)->post(route('sertifikat-pegawai.bulk.preview'), [
        'csv_file' => $file,
    ]);

    $response->assertSessionHasErrors(['csv_file']);
});

test('sertifikat pegawai bulk import shows preview with valid data', function () {
    $csvContent = "pegawai_nopeg,sertifikat_kode,nomor_sertifikat,no_reg_sertifikat,tanggal_terbit,tanggal_expire,penyelenggara\n";
    $csvContent .= "{$this->testPegawai1->nopeg},{$this->testSertifikat1->kode_sertifikat},BULK-001,REG-001,2024-01-15,2026-01-15,Bulk Organizer\n";
    $csvContent .= "{$this->testPegawai2->nopeg},{$this->testSertifikat2->kode_sertifikat},BULK-002,,2024-02-01,,\n";

    $file = UploadedFile::fake()->createWithContent('import.csv', $csvContent);

    $response = $this->actingAs($this->user)->post(route('sertifikat-pegawai.bulk.preview'), [
        'csv_file' => $file,
    ]);

    $response->assertOk();
    $response->assertViewIs('sertifikat_pegawai.bulk-preview');
    $response->assertViewHas('validRows');
    $response->assertViewHas('invalidRows');
    $response->assertViewHas('stats');
    $response->assertViewHas('token');
    $response->assertSee('BULK-001');
    $response->assertSee('BULK-002');
});

test('sertifikat pegawai bulk import detects invalid pegawai nopeg', function () {
    $csvContent = "pegawai_nopeg,sertifikat_kode,nomor_sertifikat,no_reg_sertifikat,tanggal_terbit,tanggal_expire,penyelenggara\n";
    $csvContent .= "XXXXX,{$this->testSertifikat1->kode_sertifikat},INVALID-001,,2024-01-15,,\n";

    $file = UploadedFile::fake()->createWithContent('import.csv', $csvContent);

    $response = $this->actingAs($this->user)->post(route('sertifikat-pegawai.bulk.preview'), [
        'csv_file' => $file,
    ]);

    $response->assertOk();
    $response->assertSee('tidak ditemukan');
});

test('sertifikat pegawai bulk import detects invalid sertifikat code', function () {
    $csvContent = "pegawai_nopeg,sertifikat_kode,nomor_sertifikat,no_reg_sertifikat,tanggal_terbit,tanggal_expire,penyelenggara\n";
    $csvContent .= "{$this->testPegawai1->nopeg},INVALID-CODE,TEST-001,,2024-01-15,,\n";

    $file = UploadedFile::fake()->createWithContent('import.csv', $csvContent);

    $response = $this->actingAs($this->user)->post(route('sertifikat-pegawai.bulk.preview'), [
        'csv_file' => $file,
    ]);

    $response->assertOk();
    $response->assertSee('tidak ditemukan');
});

test('sertifikat pegawai bulk import detects invalid date format', function () {
    $csvContent = "pegawai_nopeg,sertifikat_kode,nomor_sertifikat,no_reg_sertifikat,tanggal_terbit,tanggal_expire,penyelenggara\n";
    $csvContent .= "{$this->testPegawai1->nopeg},{$this->testSertifikat1->kode_sertifikat},TEST-001,,2024/01/15,,\n";

    $file = UploadedFile::fake()->createWithContent('import.csv', $csvContent);

    $response = $this->actingAs($this->user)->post(route('sertifikat-pegawai.bulk.preview'), [
        'csv_file' => $file,
    ]);

    $response->assertOk();
    $response->assertSee('YYYY-MM-DD');
});

test('sertifikat pegawai bulk import detects expire before terbit', function () {
    $csvContent = "pegawai_nopeg,sertifikat_kode,nomor_sertifikat,no_reg_sertifikat,tanggal_terbit,tanggal_expire,penyelenggara\n";
    $csvContent .= "{$this->testPegawai1->nopeg},{$this->testSertifikat1->kode_sertifikat},TEST-001,,2024-12-31,2024-01-01,\n";

    $file = UploadedFile::fake()->createWithContent('import.csv', $csvContent);

    $response = $this->actingAs($this->user)->post(route('sertifikat-pegawai.bulk.preview'), [
        'csv_file' => $file,
    ]);

    $response->assertOk();
    $response->assertSee('tidak boleh sebelum');
});

test('sertifikat pegawai bulk import detects duplicates in file', function () {
    $csvContent = "pegawai_nopeg,sertifikat_kode,nomor_sertifikat,no_reg_sertifikat,tanggal_terbit,tanggal_expire,penyelenggara\n";
    $csvContent .= "{$this->testPegawai1->nopeg},{$this->testSertifikat1->kode_sertifikat},DUP-001,,2024-01-15,,\n";
    $csvContent .= "{$this->testPegawai1->nopeg},{$this->testSertifikat1->kode_sertifikat},DUP-001,,2024-01-15,,\n";

    $file = UploadedFile::fake()->createWithContent('import.csv', $csvContent);

    $response = $this->actingAs($this->user)->post(route('sertifikat-pegawai.bulk.preview'), [
        'csv_file' => $file,
    ]);

    $response->assertOk();
    $response->assertSee('Duplikasi');
});

test('sertifikat pegawai bulk import detects existing data in database', function () {
    SertifikatPegawai::create([
        'pegawai_nopeg' => $this->testPegawai1->nopeg,
        'sertifikat_kode' => $this->testSertifikat1->kode_sertifikat,
        'nomor_sertifikat' => 'EXISTING-001',
        'tanggal_terbit' => '2024-01-15',
    ]);

    $csvContent = "pegawai_nopeg,sertifikat_kode,nomor_sertifikat,no_reg_sertifikat,tanggal_terbit,tanggal_expire,penyelenggara\n";
    $csvContent .= "{$this->testPegawai1->nopeg},{$this->testSertifikat1->kode_sertifikat},EXISTING-001,,2024-01-15,,\n";

    $file = UploadedFile::fake()->createWithContent('import.csv', $csvContent);

    $response = $this->actingAs($this->user)->post(route('sertifikat-pegawai.bulk.preview'), [
        'csv_file' => $file,
    ]);

    $response->assertOk();
    $response->assertSee('sudah terdaftar');
});

test('sertifikat pegawai bulk import can be confirmed', function () {
    $csvContent = "pegawai_nopeg,sertifikat_kode,nomor_sertifikat,no_reg_sertifikat,tanggal_terbit,tanggal_expire,penyelenggara\n";
    $csvContent .= "{$this->testPegawai1->nopeg},{$this->testSertifikat1->kode_sertifikat},CONFIRM-001,REG-001,2024-01-15,2026-01-15,Test Org\n";
    $csvContent .= "{$this->testPegawai2->nopeg},{$this->testSertifikat2->kode_sertifikat},CONFIRM-002,,2024-02-01,,\n";

    $file = UploadedFile::fake()->createWithContent('import.csv', $csvContent);

    $previewResponse = $this->actingAs($this->user)->post(route('sertifikat-pegawai.bulk.preview'), [
        'csv_file' => $file,
    ]);

    $token = $previewResponse->viewData('token');

    $response = $this->actingAs($this->user)->post(route('sertifikat-pegawai.bulk.confirm'), [
        'token' => $token,
    ]);

    $response->assertRedirect(route('sertifikat-pegawai.index'));
    $response->assertSessionHas('status');

    $this->assertDatabaseHas('sertifikat_pegawai', [
        'pegawai_nopeg' => $this->testPegawai1->nopeg,
        'sertifikat_kode' => $this->testSertifikat1->kode_sertifikat,
        'nomor_sertifikat' => 'CONFIRM-001',
    ]);

    $this->assertDatabaseHas('sertifikat_pegawai', [
        'pegawai_nopeg' => $this->testPegawai2->nopeg,
        'sertifikat_kode' => $this->testSertifikat2->kode_sertifikat,
        'nomor_sertifikat' => 'CONFIRM-002',
    ]);
});

test('sertifikat pegawai bulk import validates token required', function () {
    $response = $this->actingAs($this->user)->post(route('sertifikat-pegawai.bulk.confirm'), []);

    $response->assertSessionHasErrors(['token']);
});

test('sertifikat pegawai bulk import validates token validity', function () {
    $response = $this->actingAs($this->user)->post(route('sertifikat-pegawai.bulk.confirm'), [
        'token' => 'invalid-token-12345',
    ]);

    $response->assertSessionHasErrors(['token']);
});

test('sertifikat pegawai relationships work correctly', function () {
    $sertifikatPegawai = SertifikatPegawai::create([
        'pegawai_nopeg' => $this->testPegawai1->nopeg,
        'sertifikat_kode' => $this->testSertifikat1->kode_sertifikat,
        'nomor_sertifikat' => 'REL-001',
        'tanggal_terbit' => '2024-01-15',
    ]);

    expect($sertifikatPegawai->pegawai)->not->toBeNull();
    expect($sertifikatPegawai->pegawai->nopeg)->toBe($this->testPegawai1->nopeg);
    expect($sertifikatPegawai->pegawai->nama)->toBe($this->testPegawai1->nama);

    expect($sertifikatPegawai->sertifikat)->not->toBeNull();
    expect($sertifikatPegawai->sertifikat->kode_sertifikat)->toBe($this->testSertifikat1->kode_sertifikat);
    expect($sertifikatPegawai->sertifikat->bidang)->toBe('Kesehatan');
});

test('sertifikat pegawai soft delete works', function () {
    $sertifikatPegawai = SertifikatPegawai::create([
        'pegawai_nopeg' => $this->testPegawai1->nopeg,
        'sertifikat_kode' => $this->testSertifikat1->kode_sertifikat,
        'nomor_sertifikat' => 'SOFT-001',
        'tanggal_terbit' => '2024-01-15',
    ]);

    $id = $sertifikatPegawai->id;

    $sertifikatPegawai->delete();

    $this->assertSoftDeleted('sertifikat_pegawai', ['id' => $id]);

    // Verify soft deleted record is not in regular query
    expect(SertifikatPegawai::find($id))->toBeNull();

    // Verify soft deleted record can be retrieved with trashed
    expect(SertifikatPegawai::withTrashed()->find($id))->not->toBeNull();
});

test('sertifikat pegawai validates nomor_sertifikat max length', function () {
    $data = [
        'pegawai_nopeg' => $this->testPegawai1->nopeg,
        'sertifikat_kode' => $this->testSertifikat1->kode_sertifikat,
        'nomor_sertifikat' => str_repeat('X', 101),
        'tanggal_terbit' => '2024-01-15',
    ];

    $response = $this->actingAs($this->user)->post(route('sertifikat-pegawai.store'), $data);

    $response->assertSessionHasErrors(['nomor_sertifikat']);
});

test('sertifikat pegawai validates penyelenggara max length', function () {
    $data = [
        'pegawai_nopeg' => $this->testPegawai1->nopeg,
        'sertifikat_kode' => $this->testSertifikat1->kode_sertifikat,
        'tanggal_terbit' => '2024-01-15',
        'penyelenggara' => str_repeat('X', 151),
    ];

    $response = $this->actingAs($this->user)->post(route('sertifikat-pegawai.store'), $data);

    $response->assertSessionHasErrors(['penyelenggara']);
});

test('guests cannot access sertifikat pegawai pages', function () {
    $this->get(route('sertifikat-pegawai.index'))->assertRedirect(route('login'));
    $this->get(route('sertifikat-pegawai.create'))->assertRedirect(route('login'));
    $this->get(route('sertifikat-pegawai.import'))->assertRedirect(route('login'));
    $this->get(route('sertifikat-pegawai.template'))->assertRedirect(route('login'));
});
