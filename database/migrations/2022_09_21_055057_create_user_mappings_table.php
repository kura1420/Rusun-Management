<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserMappingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_mappings', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->char('foto', 50)->nullable();
            $table->char('province_id', 36);
            $table->char('regencie_id', 36);
            $table->char('district_id', 36);
            $table->char('village_id', 36);
            $table->string('table');
            $table->uuid('reff_id')->nullable();
            $table->foreignId('user_id');
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
        Schema::dropIfExists('user_mappings');
    }
}
