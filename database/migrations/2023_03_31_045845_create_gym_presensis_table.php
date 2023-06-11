<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGymPresensisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gym_presensis', function (Blueprint $table) {
            $table->string('id_gym_presensi')->primary();
            $table->unsignedInteger('id_gym_booking');
            $table->datetime('datetime_presensi');
            $table->foreign('id_gym_booking')->references('id_gym_booking')->on('gym_bookings')->onDelete('cascade');
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
        Schema::dropIfExists('gym_presensis');
    }
}
