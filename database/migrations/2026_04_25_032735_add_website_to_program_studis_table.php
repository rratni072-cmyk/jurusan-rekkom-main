<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom website ke program_studis untuk link eksternal prodi.
     */
    public function up(): void
    {
        Schema::table('program_studis', function (Blueprint $table) {
            $table->string('website')->nullable()->after('gambar')->comment('URL website prodi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('program_studis', function (Blueprint $table) {
            $table->dropColumn('website');
        });
    }
};
