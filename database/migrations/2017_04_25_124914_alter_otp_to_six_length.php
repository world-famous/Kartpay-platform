<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOtpToSixLength extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table)
        {
            $table->string('otp', 6)->default('')->change();
        });
    		Schema::table('merchants', function (Blueprint $table)
        {
            $table->string('otp', 6)->default('')->change();
        });
    		Schema::table('otp_generators', function (Blueprint $table)
        {
            $table->string('otp', 6)->default('')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
