<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKomplainFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('komplain_files', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->char('filename', 50);
            $table->char('tipe', 10);
            $table->foreignUuid('komplain_tanggapan_id')->nullable();
            $table->foreignUuid('komplain_id');
            $table->foreignUuid('pengelola_id')->nullable();
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
        Schema::dropIfExists('komplain_files');
    }
}
