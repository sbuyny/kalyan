<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKalyansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kalyans', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('trubok');
            $table->integer('kalyannaya_id');
            $table->foreign('kalyannaya_id')->references('id')->on('kalyannayas')->onDelete('cascade');
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
        Schema::dropIfExists('kalyans');
    }
}
