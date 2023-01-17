<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgramLaporansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('program_laporans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('judul');
            $table->text('penjelasan');
            $table->foreignUuid('kegiatan_id')->nullable();
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
        Schema::dropIfExists('program_laporans');
    }
}
