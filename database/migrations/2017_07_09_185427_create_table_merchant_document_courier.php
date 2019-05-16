<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMerchantDocumentCourier extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_document_couriers', function (Blueprint $table)
        {
          $table->increments('id');
          $table->integer('merchant_document_id')->unsigned()->nullable();
          $table->integer('courier_id')->unsigned()->nullable();
          $table->string('courier_tracking_id')->nullable();
          $table->timestamps();

          if (Schema::hasTable('merchant_documents'))
          {
            $table->foreign('merchant_document_id')->references('id')->on('merchant_documents')->onDelete('cascade');
          }

          if (Schema::hasTable('couriers'))
          {
            $table->foreign('courier_id')->references('id')->on('couriers')->onDelete('cascade');
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
        Schema::drop('merchant_document_couriers');
    }
}
