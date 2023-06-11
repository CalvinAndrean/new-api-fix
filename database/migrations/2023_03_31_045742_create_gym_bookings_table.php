<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGymBookingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gym_bookings', function (Blueprint $table) {
            $table->increments('id_gym_booking');
            $table->unsignedInteger('id_gym_session');
            $table->string('id_member');
            $table->datetime('datetime_booking');
            $table->datetime('datetime_presensi');
            $table->foreign('id_gym_session')->references('id_gym_session')->on('gym_sessions')->onDelete('cascade');
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
        Schema::dropIfExists('gym_bookings');
    }
}