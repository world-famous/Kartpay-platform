<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableState extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('states', function (Blueprint $table)
        {
          $table->increments('id');
          $table->string('state_name', 255);
          $table->integer('country_id')->unsigned();

          $table->timestamps();

    			if (Schema::hasTable('countrys')) {
    				$table->foreign('country_id')->references('id')->on('countrys')->onDelete('cascade');
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
        Schema::drop('states');
    }
}
