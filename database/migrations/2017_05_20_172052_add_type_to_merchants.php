<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeToMerchants extends Migration
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
    				if (!Schema::hasColumn('merchants', 'type')) {
    					$table->string('type', 100)->default('merchant')->after('is_active');
    					$table->integer('merchant_id')->default('0')->after('type');
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
    				if (Schema::hasColumn('merchants', 'type'))
            {
    					$table->dropColumn('type');
    				}
    			});
	      }
    }
}
