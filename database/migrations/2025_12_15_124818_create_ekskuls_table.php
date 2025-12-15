<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Tabel Data Master Ekskul
        Schema::create('ekskuls', function (Blueprint $table) {
            $table->id();
            $table->string('nama'); // Contoh: Futsal, Tari
            $table->string('pembina'); // Nama Guru Pembina
            $table->string('jadwal'); // Hari & Jam
            $table->enum('kategori', ['olahraga', 'seni', 'akademik', 'teknologi']);
            $table->text('deskripsi');
            $table->string('foto')->nullable(); // Path foto cover
            $table->timestamps();
        });

        // Tabel Absensi Ekskul
        Schema::create('presensi_ekskuls', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ekskul_id')->constrained('ekskuls')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Siswa
            $table->date('tanggal');
            $table->enum('status', ['Hadir', 'Sakit', 'Izin', 'Alpa']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('presensi_ekskuls');
        Schema::dropIfExists('ekskuls');
    }
};