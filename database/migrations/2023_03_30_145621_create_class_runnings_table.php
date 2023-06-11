<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassRunningsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_runnings', function (Blueprint $table) {
            $table->increments('id_class_running');
            $table->string('id_instructor');
            $table->unsignedInteger('id_class');
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('slot');
            $table->string('day');
            $table->foreign('id_instructor')->references('id_instructor')->on('instructors')->onDelete('cascade');
            $table->foreign('id_class')->references('id_class')->on('class_details')->onDelete('cascade');
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
        Schema::dropIfExists('class_runnings');
    }
}
