<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateP3srsKegiatanDokumentasisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('p3srs_kegiatan_dokumentasis', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->char('file', 50);
            $table->string('nama')->nullable();
            $table->char('file_type', 10)->nullable();
            $table->foreignUuid('p3srs_kegiatan_jadwal_id');
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
        Schema::dropIfExists('p3srs_kegiatan_dokumentasis');
    }
}
