<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterFacilityEquipmentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('u_master_facility_equipment', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('setId');	
            $table->string('facilId');	
            $table->date('effDate');
            $table->string('status');	
            $table->string('building');	
            $table->string('room');	
            $table->string('Descr');	
            $table->string('roomChar');	
            $table->string('quantity');
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
        Schema::dropIfExists('u_master_facility_equipment');
    }
}
