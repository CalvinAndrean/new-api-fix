<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInstructorPresensisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('instructor_presensis', function (Blueprint $table) {
            $table->increments('id_presensi_instructor');
            $table->unsignedInteger('id_class_running_daily');
            $table->string('id_instructor');
            $table->time('start_class');
            $table->time('end_class');
            $table->boolean('is_presensi');
            $table->foreign('id_instructor')->references('id_instructor')->on('instructors')->onDelete('cascade');
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
        Schema::dropIfExists('instructor_presensis');
    }
}
