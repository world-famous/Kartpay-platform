<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStateCodeToStates extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('states', 'state_code'))
        {
          Schema::table('states', function (Blueprint $table)
          {
    				$table->string('state_code')->nullable()->after('state_name');
    				$table->string('state_status')->default('Active')->after('state_code');
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
        if (Schema::hasTable('states'))
        {
    			Schema::table('states', function (Blueprint $table)
          {
    				$table->dropColumn('state_code');
    				$table->dropColumn('state_status');
    			});
	      }
    }
}
