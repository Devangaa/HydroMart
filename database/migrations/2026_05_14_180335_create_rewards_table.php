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
        Schema::create('rewards', function (Blueprint $table) {
            $table->id();
            $table->string('nama_reward');
            $table->integer('poin_diperlukan');
            $table->text('deskripsi')->nullable();
            $table->decimal('diskon', 12, 2)->default(0);
            $table->decimal('minimal_pembelian', 12, 2)->default(0);
            $table->integer('durasi_reward')->comment('dalam hari');
            $table->boolean('is_delete')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rewards');
    }
};
