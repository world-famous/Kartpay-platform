<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMerchantWebsiteDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_website_details', function (Blueprint $table)
        {
          $table->increments('id');
          $table->integer('merchant_id')->unsigned();
          $table->text('domain_name');

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
        Schema::drop('merchant_website_details');
    }
}
