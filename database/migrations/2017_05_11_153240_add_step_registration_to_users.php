<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStepRegistrationToUsers extends Migration
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
    				if (!Schema::hasColumn('users', 'step_registration'))
            {
    					$table->string('step_registration', 255)->default('email')->after('avatar_file_name');
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
    				if (Schema::hasColumn('users', 'step_registration'))
            {
    					$table->dropColumn('step_registration');
    				}
    			});
	      }
    }
}
