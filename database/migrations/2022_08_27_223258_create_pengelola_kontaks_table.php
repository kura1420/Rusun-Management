<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengelolaKontaksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengelola_kontaks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama');
            $table->char('handphone', 20)->unique();
            $table->string('email')->unique();
            $table->string('posisi');
            $table->foreignUuid('pengelola_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pengelola_kontaks');
    }
}
