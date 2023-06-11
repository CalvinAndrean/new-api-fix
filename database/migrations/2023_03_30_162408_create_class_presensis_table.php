<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassPresensisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_presensis', function (Blueprint $table) {
            $table->string('id_class_presensi')->primary();
            $table->unsignedInteger('id_class_booking');
            $table->datetime('datetime_presensi');
            $table->float('remaining_deposit');
            $table->foreign('id_class_booking')->references('id_class_booking')->on('class_bookings')->onDelete('cascade');
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
        Schema::dropIfExists('class_presensis');
    }
}
