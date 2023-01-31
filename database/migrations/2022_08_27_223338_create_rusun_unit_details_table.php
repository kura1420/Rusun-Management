<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRusunUnitDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rusun_unit_details', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('ukuran');
            $table->integer('jumlah', false, false)->default(0);
            $table->char('foto', 50)->nullable();
            $table->foreignUuid('rusun_id');
            $table->foreignUuid('rusun_detail_id');
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
        Schema::dropIfExists('rusun_unit_details');
    }
}
