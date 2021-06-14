<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsOnUserBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_bookings', function (Blueprint $table) {
            //
            $table->string('eventName')->nullable()->after('bookReason');
            $table->string('eventType')->nullable()->after('eventName');
            $table->string('requestorPhone')->nullable()->after('requestorId');
            $table->string('requestorFacility')->nullable()->after('requestorPhone');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_bookings', function (Blueprint $table) {
            //
        });
    }
}
