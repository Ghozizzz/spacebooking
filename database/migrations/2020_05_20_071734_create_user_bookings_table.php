<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_bookings', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('masterFacilityId');
            $table->string('bookDate');
            $table->string('bookTime');
            $table->string('bookDuration');
            $table->string('file')->nullable();
            $table->string('requestorName');
            $table->string('requestorId');
            $table->string('approverId')->nullable();
            $table->string('approvalStatus')->default('pending');
            $table->string('approvedOn')->nullable();
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
        Schema::dropIfExists('user_bookings');
    }
}
