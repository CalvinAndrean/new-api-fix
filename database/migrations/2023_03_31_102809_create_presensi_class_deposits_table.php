<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePresensiClassDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('presensi_class_deposits', function (Blueprint $table) {
            $table->string('id_presensi_class_deposit')->primary();
            $table->unsignedInteger('id_class_booking');
            $table->datetime('datetime');
            $table->float('remaining_deposit');
            $table->date('expired_date');
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
        Schema::dropIfExists('presensi_class_deposits');
    }
}
