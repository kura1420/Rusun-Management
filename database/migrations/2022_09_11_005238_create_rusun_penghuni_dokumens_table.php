<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRusunPenghuniDokumensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rusun_penghuni_dokumens', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->char('file', 50);
            $table->text('keterangan')->nullable();
            $table->foreignUuid('dokumen_id');
            $table->foreignUuid('rusun_penghuni_id');
            $table->foreignUuid('rusun_unit_detail_id');
            $table->foreignUuid('rusun_detail_id');
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
        Schema::dropIfExists('rusun_penghuni_dokumens');
    }
}
