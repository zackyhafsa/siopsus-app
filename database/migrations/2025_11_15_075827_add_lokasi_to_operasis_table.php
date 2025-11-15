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
        Schema::table('operasis', function (Blueprint $table) {
            $table->string('lokasi')->nullable()->after('foto_kendaraan');     // alamat / deskripsi lokasi
            $table->decimal('latitude', 10, 7)->nullable()->after('lokasi');   // opsional
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('operasis', function (Blueprint $table) {
            $table->dropColumn(['lokasi', 'latitude', 'longitude']);
        });
    }
};
