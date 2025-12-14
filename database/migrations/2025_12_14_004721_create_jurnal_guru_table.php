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
    Schema::create('jurnal_guru', function (Blueprint $table) {
        $table->id();
        $table->foreignId('guru_id')->constrained('users')->onDelete('cascade');
        $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade');
        $table->string('mata_pelajaran');
        $table->string('materi_pokok');
        $table->text('deskripsi_kegiatan')->nullable();
        $table->string('foto_bukti')->nullable();
        $table->timestamps();
    });
}

    public function down(): void
    {
        Schema::dropIfExists('jurnal_guru');
    }

};
