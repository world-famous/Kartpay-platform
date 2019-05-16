<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLiveDomainActiveToMerchants extends Migration
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
    				if (!Schema::hasColumn('merchants', 'live_domain_active'))
            {
    					$table->integer('live_domain_active')->default('0')->after('is_active');
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
    				if (Schema::hasColumn('merchants', 'live_domain_active'))
            {
    					$table->dropColumn('live_domain_active');
    				}
    			});
	      }
    }
}
