<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddingApproveOnUserBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_bookings', function (Blueprint $table) {
            $table->dateTime('approvedOn')->nullable()->after('approvalStatus');
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
            $table->dropColumn('approvedOn');
        });
    }
}
