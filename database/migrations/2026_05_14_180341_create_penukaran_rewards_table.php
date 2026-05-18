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
        Schema::create('penukaran_reward', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('id_akun')->constrained('akun');
            $table->foreignId('id_reward')->constrained('rewards');
            $table->string('status_reward')->default('Tersedia')->comment('Tersedia, Digunakan, Kedaluwarsa');
            $table->timestamp('tanggal_klaim')->useCurrent();
            $table->timestamp('tanggal_penukaran')->nullable();
            $table->timestamp('batas_berlaku')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penukaran_reward');
    }
};
