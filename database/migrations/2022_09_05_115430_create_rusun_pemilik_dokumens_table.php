<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRusunPemilikDokumensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rusun_pemilik_dokumens', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->char('file');
            $table->text('keterangan')->nullable();
            $table->foreignUuid('dokumen_id');
            $table->foreignUuid('rusun_pemilik_id');
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
        Schema::dropIfExists('rusun_pemilik_dokumens');
    }
}
