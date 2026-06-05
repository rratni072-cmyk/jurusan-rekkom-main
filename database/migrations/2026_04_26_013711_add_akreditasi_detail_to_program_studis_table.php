<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('program_studis', function (Blueprint $table) {
            $table->string('no_sk')->nullable()->after('akreditasi');
            $table->year('tahun_sk')->nullable()->after('no_sk');
            $table->date('tanggal_kedaluwarsa')->nullable()->after('tahun_sk');
            $table->string('sertifikat')->nullable()->after('tanggal_kedaluwarsa');
        });
    }

    public function down(): void
    {
        Schema::table('program_studis', function (Blueprint $table) {
            $table->dropColumn(['no_sk', 'tahun_sk', 'tanggal_kedaluwarsa', 'sertifikat']);
        });
    }
};
