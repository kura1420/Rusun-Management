<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgramKanidatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('program_kanidats', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('grup_id');
            $table->string('grup_nama');
            $table->boolean('terpilih')->default(0);
            $table->boolean('apakah_pemilik')->default(0);
            $table->boolean('rusun_unit_detail_id');
            $table->boolean('rusun_detail_id');
            $table->foreignUuid('pemilik_penghuni_id');
            $table->foreignUuid('program_jabatan_id');
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
        Schema::dropIfExists('program_kanidats');
    }
}
