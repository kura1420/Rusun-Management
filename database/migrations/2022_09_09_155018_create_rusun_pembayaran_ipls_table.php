<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRusunPembayaranIplsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rusun_pembayaran_ipls', function (Blueprint $table) {
            $table->foreignUuid('rusun_id');
            $table->foreignUuid('rusun_unit_detail_id');
            $table->foreignUuid('rusun_detail_id');
            $table->foreignUuid('pemilik_id');
            $table->foreignUuid('pemilik_penghuni_id');
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
        Schema::dropIfExists('rusun_pembayaran_ipls');
    }
}
