<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRusunOutstandingPenghunisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rusun_outstanding_penghunis', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->text('response');
            $table->decimal('total', 14, 2);
            $table->foreignUuid('pemilik_penghuni_id');
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
        Schema::dropIfExists('rusun_outstanding_penghunis');
    }
}
