<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPersonalPanCardVerifiedToTableMerchantPersonalInformation extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('merchant_personal_informations'))
        {
    			Schema::table('merchant_personal_informations', function (Blueprint $table)
          {
    				if (!Schema::hasColumn('merchant_personal_informations', 'personal_pan_card_is_verified')) {
    					$table->integer('personal_pan_card_is_verified')->default(0)->nullable()->after('personal_pan_card_path');
    					$table->integer('personal_pan_card_verified_by_admin_id')->unsigned()->nullable()->after('personal_pan_card_is_verified');
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
        if (Schema::hasTable('merchant_personal_informations'))
        {
    			Schema::table('merchant_personal_informations', function (Blueprint $table)
          {
    				if (Schema::hasColumn('merchant_personal_informations', 'personal_pan_card_is_verified'))
            {
    					$table->dropColumn('personal_pan_card_is_verified');
    					$table->dropColumn('personal_pan_card_verified_by_admin_id');
    				}
    			});
	      }
    }
}
