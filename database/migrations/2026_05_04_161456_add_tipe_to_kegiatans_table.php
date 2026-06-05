<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Tambah kolom `tipe` untuk klasifikasi kegiatan (filter publik).
     * Nilai default 'akademik' aman untuk data existing — tidak ada
     * data yang ter-orphan setelah migration.
     */
    public function up(): void
    {
        Schema::table('kegiatans', function (Blueprint $table) {
            $table->enum('tipe', ['workshop', 'seminar', 'lomba', 'kunjungan', 'hima', 'akademik'])
                ->default('akademik')
                ->after('tanggal')
                ->comment('Tipe kegiatan untuk filter publik di /kemahasiswaan/kegiatan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kegiatans', function (Blueprint $table) {
            $table->dropColumn('tipe');
        });
    }
};
