<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAvatarToMerchants extends Migration
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
    				if (!Schema::hasColumn('merchants', 'avatar_file_name'))
            {
    					$table->string('avatar_file_name', 255)->nullable()->after('remember_token');
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
    				if (Schema::hasColumn('merchants', 'avatar_file_name'))
            {
    					$table->dropColumn('avatar_file_name');
    				}
    			});
	      }
    }
}
