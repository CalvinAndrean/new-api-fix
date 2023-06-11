<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstructorAbsentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instructor_absents', function (Blueprint $table) {
            $table->increments('id_absent');
            $table->string('id_instructor');
            $table->unsignedInteger('id_class_running_daily');
            $table->string('reason');
            $table->string('id_substitute_instructor');
            $table->boolean('is_confirmed');
            $table->foreign('id_instructor')->references('id_instructor')->on('instructors')->onDelete('cascade');
            $table->foreign('id_substitute_instructor')->references('id_instructor')->on('instructors')->onDelete('cascade');
            $table->foreign('id_class_running_daily')->references('id_class_running_daily')->on('class_running_dailies')->onDelete('cascade');
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
        Schema::dropIfExists('instructor_absents');
    }
}
