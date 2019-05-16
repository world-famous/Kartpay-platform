<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCityCodeToCitys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('citys', 'city_code'))
        {
            Schema::table('citys', function (Blueprint $table)
            {
      				$table->string('city_code')->nullable()->after('city_name');
      				$table->string('city_status')->default('Active')->after('city_code');
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
        if (Schema::hasTable('citys'))
        {
    			Schema::table('citys', function (Blueprint $table)
          {
    				$table->dropColumn('city_code');
    				$table->dropColumn('city_status');
    			});
	      }
    }
}
