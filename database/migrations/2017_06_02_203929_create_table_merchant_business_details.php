<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMerchantBusinessDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_business_details', function (Blueprint $table)
        {
          $table->increments('id');
          $table->integer('merchant_id')->unsigned();
          $table->string('business_legal_name', 255);
          $table->string('business_trading_name', 255);
          $table->string('business_registered_address', 255);
          $table->string('business_state', 255);
          $table->string('business_city', 255);
          $table->string('business_country', 255);
          $table->string('business_pin_code', 255);

          $table->timestamps();

    			if (Schema::hasTable('merchants'))
          {
    				$table->foreign('merchant_id')->references('id')->on('merchants')->onDelete('cascade');
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
        Schema::drop('merchant_business_details');
    }
}
