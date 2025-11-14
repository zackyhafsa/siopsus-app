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
        Schema::create('operasis', function (Blueprint $table) {
            $table->id();
            $table->dateTime('tanggal_operasi')->nullable();
            $table->string('nama_penelusur');
            $table->string('nomor_polisi');
            $table->enum('jenis_kendaraan', ['R2', 'R4'])->nullable();
            $table->date('jatuh_tempo_pajak')->nullable();
            $table->unsignedBigInteger('pokok_pkb')->default(0);
            $table->unsignedBigInteger('denda_pkb')->default(0);
            $table->unsignedBigInteger('opsen_pkb')->default(0);
            $table->unsignedBigInteger('denda_opsen_pkb')->default(0);
            $table->unsignedBigInteger('pokok_swdkllj')->default(0);
            $table->unsignedBigInteger('denda_swdkllj')->default(0);

            // Foto
            $table->string('foto_kendaraan')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('operasis');
    }
};
