<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_bookings', function (Blueprint $table) {
            $table->increments('id_class_booking');
            $table->unsignedInteger('id_class_running');
            $table->unsignedInteger('id_class_running_daily');
            $table->string('id_member');
            $table->datetime('datetime');
            $table->string('payment_type');
            $table->datetime('datetime_presensi');
            $table->foreign('id_class_running')->references('id_class_running')->on('class_runnings')->onDelete('cascade');
            $table->foreign('id_class_running_daily')->references('id_class_running_daily')->on('class_running_dailies')->onDelete('cascade');
            $table->foreign('id_member')->references('id_member')->on('members')->onDelete('cascade');
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
        Schema::dropIfExists('class_bookings');
    }
}
