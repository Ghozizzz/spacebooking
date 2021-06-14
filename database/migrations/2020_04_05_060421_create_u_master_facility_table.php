<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUMasterFacilityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('u_master_facility', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('setId');
            $table->string('facilId');
            $table->string('status');
            $table->string('building');
            $table->string('description');
            $table->string('type');
            $table->string('location');
            $table->string('capacity');
            $table->timestamps();
            $table->softDeletes();  
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('u_master_facility');
    }
}
