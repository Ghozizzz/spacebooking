<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddingLastDayYearUMonitorClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('u_monitor_classes', function (Blueprint $table) {
            $table->date('lastDateOfYear')->nullable()->after('eventId');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('u_monitor_classes', function (Blueprint $table) {
            $table->dropColumn('lastDateOfYear');
        });
    }
}
