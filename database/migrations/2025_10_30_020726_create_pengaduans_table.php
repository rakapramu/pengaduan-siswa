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
        Schema::create('pengaduans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('siswa_id')->references('id')->on('siswas');
            $table->string('topik');
            $table->text('deskripsi');
            $table->enum('status', ['proses', 'batal', 'selesai'])->default('proses');
            // $table->foreignId('guru_id')->references('id')->on('gurus');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengaduans');
    }
};
