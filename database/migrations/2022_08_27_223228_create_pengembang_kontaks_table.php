<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengembangKontaksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pengembang_kontaks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama');
            $table->char('handphone', 20)->unique();
            $table->string('email')->unique();
            $table->string('posisi')->nullable();
            $table->foreignUuid('pengembang_id');
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
        Schema::dropIfExists('pengembang_kontaks');
    }
}
