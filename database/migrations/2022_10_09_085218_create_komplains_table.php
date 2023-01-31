<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKomplainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('komplains', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->char('kode', 50)->unique();
            $table->string('judul');
            $table->text('penjelasan');
            $table->boolean('status')->default(0);
            $table->dateTime('tanggal_dibuat');
            $table->dateTime('tanggal_ditanggapi')->nullable();
            $table->dateTime('tanggal_diselesaikan')->nullable();
            $table->char('province_id', 36)->nullable();
            $table->char('regencie_id', 36)->nullable();
            $table->char('district_id', 36)->nullable();
            $table->char('village_id', 36)->nullable();
            $table->foreignId('komplain_user_id');
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
        Schema::dropIfExists('komplains');
    }
}
