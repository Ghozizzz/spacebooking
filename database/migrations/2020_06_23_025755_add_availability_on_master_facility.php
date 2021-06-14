<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAvailabilityOnMasterFacility extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('u_master_facility', function (Blueprint $table) {
            //
            $table->string('days')->nullable()->after('room_desc');
            $table->string('start_time')->nullable()->after('days');
            $table->string('end_time')->nullable()->after('start_time');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('u_master_facility', function (Blueprint $table) {
            //
        });
    }
}
