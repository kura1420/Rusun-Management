<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgramKegiatansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('program_kegiatans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama');
            $table->date('tanggal_mulai');
            $table->date('tanggal_berakhir');
            $table->boolean('status')->default(0)->comment('menunggu | sudah di verifikasi | proses | ditolak');
            $table->text('keterangan')->nullable();
            $table->foreignUuid('rusun_id');
            $table->foreignUuid('program_id');
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
        Schema::dropIfExists('program_kegiatans');
    }
}
