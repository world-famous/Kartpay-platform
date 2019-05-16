<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBankDetailsToMerchants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('merchants'))
        {
    			Schema::table('merchants', function (Blueprint $table)
          {
    				if (!Schema::hasColumn('merchants', 'bank_name'))
            {
    					$table->string('bank_name', 255)->nullable()->after('gumasta_doc_verified_by_admin_id');
    					$table->string('account_number', 255)->nullable()->after('bank_name');
    					$table->string('bank_ifsc_code', 255)->nullable()->after('account_number');
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
       if (Schema::hasTable('merchants'))
       {
    			Schema::table('merchants', function (Blueprint $table) 
          {
    				if (Schema::hasColumn('merchants', 'bank_name'))
            {
    					$table->dropColumn('bank_name');
    					$table->dropColumn('account_number');
    					$table->dropColumn('bank_ifsc_code');
    				}
    			});
	      }
    }
}
