<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMonitorClassesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('u_monitor_classes', function (Blueprint $table) {
            $table->bigIncrements('id_table');
            $table->string('institution')->nullable();	
            $table->string('term')->nullable();
            $table->string('session')->nullable();
            $table->string('courseId')->nullable();
            $table->string('offerNbr')->nullable();
            $table->string('career')->nullable();
            $table->string('acadGroup')->nullable();
            $table->string('fakultas')->nullable();
            $table->string('acadOrg')->nullable();
            $table->string('jurusan')->nullable();
            $table->string('classNbr')->nullable();
            $table->string('section')->nullable();
            $table->string('subject')->nullable();
            $table->string('catalog')->nullable();
            $table->string('description')->nullable();
            $table->string('component')->nullable();
            $table->string('id')->nullable();
            $table->string('displayName')->nullable();
            $table->string('patNbr')->nullable();
            $table->string('hari')->nullable();
            $table->string('jam')->nullable();
            $table->string('facilId')->nullable();
            $table->string('totEnrl')->nullable();
            $table->string('classStat')->nullable();
            $table->string('classType')->nullable();
            $table->string('combSectsId')->nullable();
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
        Schema::dropIfExists('u_monitor_classes');
    }
}
