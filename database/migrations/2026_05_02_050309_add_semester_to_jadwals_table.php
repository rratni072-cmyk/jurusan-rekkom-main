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
        Schema::table('jadwals', function (Blueprint $table) {
            // Semester: 'Ganjil' atau 'Genap'. Default 'Ganjil' sebagai backfill aman.
            $table->enum('semester', ['Ganjil', 'Genap'])
                ->default('Ganjil')
                ->after('tahun_ajaran');

            // FK ke program_studis untuk konsistensi (typo-proof). Nullable agar
            // backward compatible dengan data lama yang hanya pakai string.
            $table->foreignId('program_studi_id')
                ->nullable()
                ->after('id')
                ->constrained('program_studis')
                ->nullOnDelete();

            // Indeks gabungan untuk query filter umum di frontend.
            $table->index(['tahun_ajaran', 'semester', 'is_active'], 'jadwals_filter_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jadwals', function (Blueprint $table) {
            $table->dropIndex('jadwals_filter_idx');
            $table->dropConstrainedForeignId('program_studi_id');
            $table->dropColumn('semester');
        });
    }
};
