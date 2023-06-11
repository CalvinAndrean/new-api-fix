<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClassPromosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('class_promos', function (Blueprint $table) {
            $table->increments('id_class_promo');
            $table->unsignedInteger('id_class');
            $table->float('total_price');
            $table->float('amount_deposit');
            $table->integer('duration');
            $table->float('bonus');
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
        Schema::dropIfExists('class_promos');
    }
}