<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassRunningDailiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_running_dailies', function (Blueprint $table) {
            $table->increments('id_class_running_daily');
            $table->unsignedInteger('id_class_running');
            $table->date('date');
            $table->int('slot');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('status');
            $table->foreign('id_class_running')->references('id_class_running')->on('class_runnings')->onUpdate('cascade');
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
        Schema::dropIfExists('class_running_dailies');
    }
}
