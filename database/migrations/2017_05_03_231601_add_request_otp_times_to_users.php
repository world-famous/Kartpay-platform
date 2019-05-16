<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRequestOtpTimesToUsers extends Migration
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
    				if (!Schema::hasColumn('users', 'request_otp_times'))
            {
    					$table->integer('request_otp_times')->default(0)->nullable()->after('otp');
    					$table->timestamp('last_send_otp')->nullable()->after('request_otp_times');
    					$table->timestamp('last_send_email_secret_login')->nullable()->after('last_send_otp');
    					$table->integer('allow_login')->default(1)->nullable()->after('last_send_email_secret_login');
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
    				if (Schema::hasColumn('users', 'request_otp_times'))
            {
    					$table->dropColumn('request_otp_times');
    					$table->dropColumn('last_send_otp');
    					$table->dropColumn('last_send_email_secret_login');
    					$table->dropColumn('allow_login');
    				}
    			});
	      }
    }
}
