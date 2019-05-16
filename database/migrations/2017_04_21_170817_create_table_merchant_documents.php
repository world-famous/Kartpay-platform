<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMerchantDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_documents', function (Blueprint $table)
        {
          $table->increments('id');
          $table->integer('merchant_id')->unsigned();
          $table->string('document_file_name', 255);
          $table->string('document_path', 255);
          $table->integer('is_verified')->default(0)->nullable();
          $table->integer('verified_by_admin_id')->unsigned()->nullable();
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
        Schema::drop('merchant_documents');
    }
}
