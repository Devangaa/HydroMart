<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('layanan', function (Blueprint $table) {
            // Kita ubah tipenya menjadi text agar muat banyak karakter (JSON)
            $table->text('foto_layanan')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('layanan', function (Blueprint $table) {
            $table->string('foto_layanan')->change();
        });
    }
};
