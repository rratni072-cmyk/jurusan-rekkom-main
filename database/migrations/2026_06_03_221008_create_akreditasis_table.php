<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('akreditasis', function (Blueprint $table) {
            $table->id();
            $table->string('program_studi');
            $table->string('no_sk')->nullable();
            $table->string('peringkat');
            $table->string('tahun_sk')->nullable();
            $table->date('tanggal_kedaluwarsa')->nullable();
            $table->string('status')->nullable();
            $table->string('file_sertifikat')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('akreditasis');
    }
};