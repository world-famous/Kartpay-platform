<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableCity extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('citys', function (Blueprint $table)
        {
          $table->increments('id');
          $table->string('city_name', 255);
          $table->integer('state_id')->unsigned();

          $table->timestamps();

    			if (Schema::hasTable('states')) {
    				$table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');
    			}
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('citys');
    }
}
