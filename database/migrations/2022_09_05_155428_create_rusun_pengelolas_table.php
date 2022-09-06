<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRusunPengelolasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rusun_pengelolas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->text('keterangan')->nullable();
            $table->foreignUuid('rusun_id');
            $table->foreignUuid('pengelola_id');
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
        Schema::dropIfExists('rusun_pengelolas');
    }
}
