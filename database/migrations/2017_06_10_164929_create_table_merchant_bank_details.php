<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMerchantBankDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_bank_details', function (Blueprint $table)
        {
          $table->increments('id');
          $table->integer('merchant_id')->unsigned();
          $table->string('bank_name', 255)->nullable();
    			$table->string('account_number', 255)->nullable();
    			$table->string('bank_ifsc_code', 255)->nullable();

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
        Schema::drop('merchant_bank_details');
    }
}
