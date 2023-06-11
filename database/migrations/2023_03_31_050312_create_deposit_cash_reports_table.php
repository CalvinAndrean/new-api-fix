<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDepositCashReportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deposit_cash_reports', function (Blueprint $table) {
            $table->string('report_number_deposit_cash')->primary();
            $table->string('id_member');
            $table->string('id_pegawai');
            $table->unsignedInteger('id_cash_promo');
            $table->date('date_deposit');
            $table->float('amount_deposit');
            $table->float('bonus_deposit');
            $table->float('remaining_deposit');
            $table->float('total_deposit');
            $table->foreign('id_member')->references('id_member')->on('members')->onDelete('cascade');
            $table->foreign('id_pegawai')->references('id_pegawai')->on('pegawais')->onDelete('cascade');
            $table->foreign('id_cash_promo')->references('id_cash_promo')->on('cash_promos')->onDelete('cascade');
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
        Schema::dropIfExists('deposit_cash_reports');
    }
}
