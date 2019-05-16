<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAvatarToUsers extends Migration
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
    				if (!Schema::hasColumn('users', 'avatar_file_name'))
            {					
    					$table->string('avatar_file_name', 255)->nullable()->after('type');
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
        if (Schema::hasTable('users')) {
			Schema::table('users', function (Blueprint $table) {
				if (Schema::hasColumn('users', 'avatar_file_name')) {
					$table->dropColumn('avatar_file_name');
				}
			});
		}
    }
}
