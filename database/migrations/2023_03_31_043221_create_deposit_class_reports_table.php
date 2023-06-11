<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepositClassReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deposit_class_reports', function (Blueprint $table) {
            $table->string('report_number_class_deposit')->primary();
            $table->string('id_pegawai');
            $table->string('id_member');
            $table->unsignedInteger('id_class_promo');
            $table->integer('total_deposit');
            $table->float('total_price');
            $table->date('expired_date');
            $table->datetime('datetime');
            $table->foreign('id_pegawai')->references('id_pegawai')->on('pegawais')->onDelete('cascade');
            $table->foreign('id_member')->references('id_member')->on('members')->onDelete('cascade');
            $table->foreign('id_class_promo')->references('id_class_promo')->on('class_promos')->onDelete('cascade');
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
        Schema::dropIfExists('deposit_class_reports');
    }
}
