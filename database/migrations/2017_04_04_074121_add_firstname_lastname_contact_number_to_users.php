<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFirstnameLastnameContactNumberToUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('users'))
        {
    			Schema::table('users', function (Blueprint $table)
          {
    				if (!Schema::hasColumn('users', 'first_name'))
            {
    					$table->string('first_name', 100)->default('')->after('name');
    				}
    				if (!Schema::hasColumn('users', 'last_name'))
            {
    					$table->string('last_name', 100)->default('')->after('first_name');
    				}
    				if (!Schema::hasColumn('users', 'country_code'))
            {
    					$table->string('country_code', 3)->default('')->after('last_name');
    				}
    				if (!Schema::hasColumn('users', 'contact_no'))
            {
    					$table->string('contact_no', 100)->default('')->after('country_code');
    				}
    				if (!Schema::hasColumn('users', 'verification_code'))
            {
    					$table->string('verification_code', 50)->default('')->after('contact_no');
    				}
    				if (!Schema::hasColumn('users', 'otp'))
            {
    					$table->string('otp', 4)->default('')->after('verification_code');
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
        if (Schema::hasTable('users'))
        {
    			Schema::table('users', function (Blueprint $table)
          {
    				if (Schema::hasColumn('users', 'first_name'))
            {
    					$table->dropColumn('first_name');
    				}
    				if (Schema::hasColumn('users', 'last_name'))
            {
    					$table->dropColumn('last_name');
    				}
    				if (Schema::hasColumn('users', 'contact_no'))
            {
    					$table->dropColumn('contact_no');
    				}
    			});
	      }
    }
}
