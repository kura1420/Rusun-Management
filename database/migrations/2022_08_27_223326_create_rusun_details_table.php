<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRusunDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rusun_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('nama_tower');
            $table->integer('jumlah_unit', false, false)->default(0);
            $table->string('jenis_unit');
            $table->char('foto', 50)->nullable();
            $table->integer('jumlah_lantai', false, false)->default(0);
            $table->text('keterangan')->nullable();
            $table->string('ukuran_paling_kecil');
            $table->string('ukuran_paling_besar');
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
        Schema::dropIfExists('rusun_details');
    }
}
