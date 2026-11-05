<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('laporan', function (Blueprint $table) {
            $table->id();
            $table->string('nama_pelapor');
            $table->string('no_hp');
            $table->string('lokasi');
            $table->string('kecamatan');
            $table->integer('tinggi_air');
            $table->text('keterangan');
            $table->string('foto')->nullable();
            $table->string('status')->default('Menunggu');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('laporan');
    }
};