<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePollingKanidatsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('polling_kanidats', function (Blueprint $table) {
            $table->uuid('id')->primary();

            $table->dateTime('waktu');
            $table->boolean('apakah_pemilik', false, false);

            $table->foreignUuid('pemilik_penghuni_memilih');
            $table->foreignUuid('grup_id');
            $table->foreignUuid('program_kanidat_id');
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
        Schema::dropIfExists('polling_kanidats');
    }
}
