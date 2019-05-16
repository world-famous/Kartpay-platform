<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterBankNameToBankIdOnMerchantBankDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
       Schema::table('merchant_bank_details', function (Blueprint $table)
       {
          if (Schema::hasColumn('merchant_bank_details', 'bank_name'))
          {
          		$table->dropColumn('bank_name');
          }
          $table->integer('bank_id')->unsigned()->nullable()->after('merchant_id');

    			if (Schema::hasTable('banks'))
          {
    				$table->foreign('bank_id')->references('id')->on('banks')->onDelete('cascade');
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
        Schema::table('merchant_bank_details', function (Blueprint $table)
        {
    			if (Schema::hasColumn('merchant_bank_details', 'bank_id'))
          {
    				$table->dropForeign(['bank_id']);
    				$table->dropColumn('bank_id');
    			}
	      });
    }
}
