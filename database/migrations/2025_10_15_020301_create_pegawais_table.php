<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pegawais', function (Blueprint $table) {
            $table->id();
            $table->string('nopeg', 5)->unique();
            $table->string('nama');
            $table->string('nip', 50)->unique()->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->string('jabatan', 100)->nullable();
            $table->date('tanggal_menjabat')->nullable();
            $table->string('unit_kerja', 100)->nullable();
            $table->timestamps();
            $table->softDeletes(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pegawais');
    }
};
