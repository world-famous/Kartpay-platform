<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableMerchantPersonalInformation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('merchant_personal_informations', function (Blueprint $table)
        {
          $table->increments('id');
          $table->integer('merchant_id')->unsigned();
          $table->string('owner_name', 255);
          $table->string('personal_address', 150);
          $table->string('personal_contact_no', 10);
          $table->string('city', 20);
          $table->string('state', 255);
          $table->string('country', 255);
          $table->string('personal_pan_card', 50);
          $table->string('personal_pan_card_filename', 255);
          $table->string('personal_pan_card_path', 255);
          $table->string('aadhar_no', 12)->nullable();
          $table->string('aadhar_filename', 255)->nullable();
          $table->string('aadhar_path', 255)->nullable();
          $table->integer('aadhar_is_verified')->default(0)->nullable();
          $table->integer('aadhar_verified_by_admin_id')->unsigned()->nullable();

          $table->string('passport_no', 8)->nullable();
          $table->string('passport_filename', 255)->nullable();
          $table->string('passport_path', 255)->nullable();
          $table->integer('passport_is_verified')->default(0)->nullable();
          $table->integer('passport_verified_by_admin_id')->unsigned()->nullable();

          $table->string('voter_id_card_filename', 255)->nullable();
          $table->string('voter_id_card_path', 255)->nullable();
          $table->integer('voter_id_card_is_verified')->default(0)->nullable();
          $table->integer('voter_id_card_verified_by_admin_id')->unsigned()->nullable();

          $table->string('electricity_bill_filename', 255)->nullable();
          $table->string('electricity_bill_path', 255)->nullable();
          $table->integer('electricity_bill_is_verified')->default(0)->nullable();
          $table->integer('electricity_bill_verified_by_admin_id')->unsigned()->nullable();

          $table->string('landline_bill_filename', 255)->nullable();
          $table->string('landline_bill_path', 255)->nullable();
          $table->integer('landline_bill_is_verified')->default(0)->nullable();
          $table->integer('landline_bill_verified_by_admin_id')->unsigned()->nullable();

          $table->string('bank_account_statement_filename', 255)->nullable();
          $table->string('bank_account_statement_path', 255)->nullable();
          $table->integer('bank_account_statement_is_verified')->default(0)->nullable();
          $table->integer('bank_account_statement_verified_by_admin_id')->unsigned()->nullable();

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
        Schema::drop('merchant_personal_informations');
    }
}
