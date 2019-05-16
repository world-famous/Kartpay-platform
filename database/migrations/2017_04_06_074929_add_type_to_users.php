<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTypeToUsers extends Migration
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
    				if (!Schema::hasColumn('users', 'type'))
            {
    					$table->string('type', 100)->default('merchant')->after('is_active');
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
    				if (Schema::hasColumn('users', 'type'))
            {
    					$table->dropColumn('type');
    				}
    			});
	      }
    }
}
