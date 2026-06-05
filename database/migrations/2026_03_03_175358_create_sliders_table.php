<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration untuk tabel sliders.
 *
 * Menyimpan data slider hero carousel di halaman beranda.
 */
return new class extends Migration
{
    /**
     * Buat tabel sliders.
     */
    public function up(): void
    {
        Schema::create('sliders', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 255)->comment('Judul slider');
            $table->text('deskripsi')->nullable()->comment('Deskripsi/subtitle');
            $table->string('gambar')->comment('Path gambar background');
            $table->string('tombol_teks', 100)->nullable()->comment('Teks tombol CTA');
            $table->string('tombol_url', 255)->nullable()->comment('URL tujuan tombol');
            $table->integer('urutan')->default(0)->comment('Urutan tampil');
            $table->boolean('is_active')->default(true)->comment('Status aktif/nonaktif');
            $table->timestamps();
        });
    }

    /**
     * Hapus tabel sliders.
     */
    public function down(): void
    {
        Schema::dropIfExists('sliders');
    }
};
