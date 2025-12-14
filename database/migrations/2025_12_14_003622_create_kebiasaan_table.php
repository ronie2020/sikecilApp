<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
   public function up(): void
{
        Schema::create('kebiasaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->date('tanggal');
            
            // Pastikan kolom k1 sampai k7 ada di sini!
            $table->boolean('k1')->default(false);
            $table->boolean('k2')->default(false);
            $table->boolean('k3')->default(false);
            $table->boolean('k4')->default(false);
            $table->boolean('k5')->default(false);
            $table->boolean('k6')->default(false);
            $table->boolean('k7')->default(false);
            
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kebiasaan');
    }
};