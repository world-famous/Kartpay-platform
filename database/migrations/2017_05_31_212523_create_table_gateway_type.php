<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableGatewayType extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gateway_types', function (Blueprint $table)
        {
          $table->increments('id');
          $table->string('gateway_type_name');
          $table->tinyInteger('is_enable');
          $table->string('route')->nullable();
          $table->integer('gateway_id')->unsigned()->nullable();
          $table->timestamps();

    			if (Schema::hasTable('gateways'))
          {
    						$table->foreign('gateway_id')->references('id')->on('gateways')->onDelete('cascade');
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
        Schema::drop('gateway_types');
    }
}
