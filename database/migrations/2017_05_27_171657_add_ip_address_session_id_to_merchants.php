<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIpAddressSessionIdToMerchants extends Migration
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
    				if (!Schema::hasColumn('merchants', 'ip_address'))
            {
    					$table->string('ip_address', 45)->nullable()->after('webhook');
    					$table->string('session_id', 191)->nullable()->after('ip_address');
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
    				if (Schema::hasColumn('merchants', 'ip_address'))
            {
    					$table->dropColumn('ip_address');
    					$table->dropColumn('session_id');
    				}
    			});
	      }
    }
}
