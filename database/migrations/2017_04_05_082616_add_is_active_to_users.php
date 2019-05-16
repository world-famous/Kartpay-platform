<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsActiveToUsers extends Migration
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
    				if (!Schema::hasColumn('users', 'is_active'))
            {
    					$table->tinyInteger('is_active')->default(0)->after('remember_token');
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
    				if (Schema::hasColumn('users', 'is_active'))
            {
    					$table->dropColumn('is_active');
    				}
    			});
       }
    }
}
