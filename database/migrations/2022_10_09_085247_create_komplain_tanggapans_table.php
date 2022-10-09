<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKomplainTanggapansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('komplain_tanggapans', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->text('penjelasan');
            $table->foreignId('ditanggapi_user_id');
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
        Schema::dropIfExists('komplain_tanggapans');
    }
}
