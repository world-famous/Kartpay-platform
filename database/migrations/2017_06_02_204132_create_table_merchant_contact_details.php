<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMerchantContactDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_contact_details', function (Blueprint $table)
        {
          $table->increments('id');
          $table->integer('merchant_id')->unsigned();
          $table->string('owner_name', 255);
          $table->string('owner_email', 255);
          $table->string('owner_mobile_no', 255);
          $table->string('owner_address', 255);
          $table->string('owner_state', 255);
          $table->string('owner_city', 255);
          $table->string('owner_country', 255);
          $table->string('owner_pin_code', 255);

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
        Schema::drop('merchant_contact_details');
    }
}
