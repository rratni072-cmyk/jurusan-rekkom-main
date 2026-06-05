<?php

declare(strict_types=1);

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
        Schema::table('kegiatans', function (Blueprint $table) {
            $table->unsignedInteger('views')
                ->default(0)
                ->after('is_published')
                ->comment('Jumlah views kegiatan (session-deduped, skip admin login)');
            $table->index('views');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('kegiatans', function (Blueprint $table) {
            $table->dropIndex(['views']);
            $table->dropColumn('views');
        });
    }
};
