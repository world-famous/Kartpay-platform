<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCourierTrackingToMerchantDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('merchant_documents', 'courier_id'))
        {
            Schema::table('merchant_documents', function (Blueprint $table)
            {
      				$table->integer('courier_id')->unsigned()->after('kartpay_merchant_agreement_verified_by_admin_id');
      				$table->string('courier_tracking_id')->nullable()->after('courier_id');

      				if (Schema::hasTable('couriers'))
              {
      					$table->foreign('courier_id')->references('id')->on('couriers')->onDelete('cascade');
      				}
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasTable('merchant_documents'))
        {
    			Schema::table('merchant_documents', function (Blueprint $table)
          {
    				$table->dropColumn('courier_id');
    				$table->dropColumn('courier_tracking_id');
    			});
    		}
    }
}
