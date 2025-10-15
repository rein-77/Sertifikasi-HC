<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sertifikat_pegawai', function (Blueprint $table) {
            $table->id();
            $table->string('pegawai_nopeg', 5);
            $table->string('sertifikat_kode', 50);
            $table->string('no_reg_sertifikat', 100)->nullable();
            $table->string('nomor_sertifikat', 100);
            $table->date('tanggal_terbit');
            $table->date('tanggal_expire')->nullable();
            $table->string('penyelenggara', 150)->nullable();
            $table->timestamps();
            $table->softDeletes();

            // Definisi Foreign Key (constrain)
            $table->foreign('pegawai_nopeg')
                ->references('nopeg')
                ->on('pegawais')
                ->onUpdate('cascade') // Jika nopeg di tabel pegawai berubah, di sini juga ikut berubah
                ->onDelete('cascade'); // Jika pegawai dihapus, data sertifikatnya juga terhapus

            $table->foreign('sertifikat_kode')
                ->references('kode_sertifikat')
                ->on('sertifikats')
                ->onUpdate('cascade')
                ->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sertifikat_pegawai');
    }
};
