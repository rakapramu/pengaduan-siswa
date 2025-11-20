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
        Schema::create('detail_sesis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sesi_id')->references('id')->on('sesi_konselings');
            $table->date('tanggal');
            $table->time('waktu_mulai');
            $table->time('waktu_selesai')->nullable();
            $table->text('tindak_lanjut')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_sesis');
    }
};
