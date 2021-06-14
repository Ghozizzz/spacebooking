<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddReasonOnUserBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_bookings', function (Blueprint $table) {
            $table->text('bookReason')->nullable()->after('bookDuration');
            $table->text('approvalReason')->nullable()->after('approvalStatus');
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
            $table->dropColumn('bookReason');
            $table->dropColumn('approvalReason');
        });
    }
}
