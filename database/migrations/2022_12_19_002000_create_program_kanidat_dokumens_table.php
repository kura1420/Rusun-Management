<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgramKanidatDokumensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('program_kanidat_dokumens', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->char('file', 50);
            $table->foreignUuid('program_dokumen_id');
            $table->foreignUuid('program_kanidat_id');
            $table->foreignUuid('rusun_unit_detail_id');
            $table->foreignUuid('rusun_detail_id');
            $table->foreignUuid('pemilik_penghuni_id');
            $table->foreignUuid('program_id');
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
        Schema::dropIfExists('program_kanidat_dokumens');
    }
}
