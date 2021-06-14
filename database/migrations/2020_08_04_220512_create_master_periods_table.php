<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMasterPeriodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('master_periods', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('institution')->nullable();
            $table->string('career')->nullable();
            $table->string('term')->nullable();
            $table->string('description')->nullable();
            $table->string('shortDesc')->nullable();
            $table->date('beginDate')->nullable();
            $table->date('endDate')->nullable();
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
        Schema::dropIfExists('master_periods');
    }
}
