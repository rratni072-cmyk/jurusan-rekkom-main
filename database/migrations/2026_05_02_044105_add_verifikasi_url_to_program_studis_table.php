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
        Schema::table('program_studis', function (Blueprint $table) {
            // URL verifikasi eksternal: PDDikti, BAN-PT direktori, atau sumber resmi lain.
            // Nullable agar backward compatible dengan data lama.
            $table->string('verifikasi_url', 500)->nullable()->after('sertifikat');

            // Label custom untuk tombol verifikasi (e.g. "PDDikti", "BAN-PT", "LAM-INFOKOM").
            // Default label dihandle di view jika kolom ini null.
            $table->string('verifikasi_label', 50)->nullable()->after('verifikasi_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('program_studis', function (Blueprint $table) {
            $table->dropColumn(['verifikasi_url', 'verifikasi_label']);
        });
    }
};
