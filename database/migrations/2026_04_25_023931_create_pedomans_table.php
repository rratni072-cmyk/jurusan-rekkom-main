<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pedomans', function (Blueprint $table) {
            $table->id();
            $table->string('nama_file');
            $table->string('format_file')->default('PDF');
            $table->string('file_path');
            $table->integer('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pedomans');
    }
};
