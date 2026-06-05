<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration untuk tabel beritas.
 *
 * Menyimpan berita/artikel jurusan dengan relasi ke user (penulis).
 */
return new class extends Migration
{
    /**
     * Buat tabel beritas.
     */
    public function up(): void
    {
        Schema::create('beritas', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 255)->comment('Judul berita');
            $table->string('slug', 255)->unique()->comment('URL-friendly dari judul');
            $table->text('ringkasan')->comment('Ringkasan singkat untuk listing');
            $table->longText('konten')->comment('Konten lengkap berita (HTML)');
            $table->string('gambar')->nullable()->comment('Path gambar utama');
            $table->foreignId('penulis_id')->constrained('users')->onDelete('cascade')->comment('FK ke tabel users');
            $table->dateTime('tanggal_publikasi')->comment('Tanggal tampil di publik');
            $table->boolean('is_published')->default(false)->comment('Status publikasi');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Hapus tabel beritas.
     */
    public function down(): void
    {
        Schema::dropIfExists('beritas');
    }
};
