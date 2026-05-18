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
        Schema::create('ulasan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_detailtransaksi');
            $table->char('id_akun', 36);
            $table->unsignedBigInteger('id_produk')->nullable();
            $table->unsignedBigInteger('id_layanan')->nullable();
            $table->timestamp('tanggal_ulasan')->useCurrent();
            $table->text('komentar')->nullable();
            $table->integer('rating');
            $table->text('balasan')->nullable();
            $table->timestamp('tanggal_balasan')->nullable();
            $table->boolean('isdelete')->default(false);
            $table->timestamps();

            // Foreign Keys
            $table->foreign('id_akun')->references('id')->on('akun')->onDelete('cascade');
            $table->foreign('id_produk')->references('id')->on('products')->onDelete('set null');
            $table->foreign('id_layanan')->references('id')->on('layanan')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ulasan');
    }
};
