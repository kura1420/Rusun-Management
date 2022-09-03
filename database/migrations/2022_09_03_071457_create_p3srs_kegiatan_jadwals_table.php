<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateP3srsKegiatanJadwalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('p3srs_kegiatan_jadwals', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->date('tanggal');
            $table->boolean('status')->default(0);
            $table->string('lokasi');
            $table->text('keterangan');
            $table->foreignUuid('p3srs_kegiatan_id');
            $table->foreignUuid('rusun_id');
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
        Schema::dropIfExists('p3srs_kegiatan_jadwals');
    }
}
