<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddingCalendarIdUMasterFacilityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('u_master_facility', function (Blueprint $table) {
            $table->string('calenderId')->nullable()->after('capacity');
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
            $table->dropColumn('calenderId');
        });
    }
}
