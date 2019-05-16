<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddCountryCodeToCountrys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('countrys', 'country_code'))
        {
            Schema::table('countrys', function (Blueprint $table)
            {
      				$table->string('country_code')->nullable()->after('country_name');
      				$table->string('country_status')->default('Active')->after('country_code');
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
      if (Schema::hasTable('countrys'))
      {
        Schema::table('countrys', function (Blueprint $table)
        {
          $table->dropColumn('country_code');
          $table->dropColumn('country_status');
        });
	    }
    }
}
