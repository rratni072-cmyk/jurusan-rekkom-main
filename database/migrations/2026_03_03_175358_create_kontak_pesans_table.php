<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migration untuk tabel kontak_pesans.
 *
 * Menyimpan pesan masuk dari pengunjung via form kontak.
 */
return new class extends Migration
{
    /**
     * Buat tabel kontak_pesans.
     */
    public function up(): void
    {
        Schema::create('kontak_pesans', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 255)->comment('Nama pengirim');
            $table->string('email', 255)->comment('Email pengirim');
            $table->string('subjek', 255)->comment('Subjek pesan');
            $table->text('pesan')->comment('Isi pesan');
            $table->boolean('is_read')->default(false)->comment('Status sudah dibaca/belum');
            $table->timestamps();
        });
    }

    /**
     * Hapus tabel kontak_pesans.
     */
    public function down(): void
    {
        Schema::dropIfExists('kontak_pesans');
    }
};
