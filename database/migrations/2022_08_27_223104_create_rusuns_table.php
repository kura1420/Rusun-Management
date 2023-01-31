<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRusunsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rusuns', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama');
            $table->text('alamat');
            $table->char('rt_rw', 20);
            $table->string('keluarahan');
            $table->string('kecamatan');
            $table->string('kota');
            $table->string('provinsi');
            $table->char('kode_pos', 10)->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->integer('total_tower', false, false)->default(0);
            $table->integer('total_unit', false, false)->default(0);
            $table->char('foto_1', 50)->nullable();
            $table->char('foto_2', 50)->nullable();
            $table->char('foto_3', 50)->nullable();
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
        Schema::dropIfExists('rusuns');
    }
}
