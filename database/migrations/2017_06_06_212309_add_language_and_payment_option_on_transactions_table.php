<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLanguageAndPaymentOptionOnTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table)
        {
            $table->string('language',2)->default('EN')->nullable();
            $table->string('payment_option')
                ->comment('Hosted,Iframe,Seamless')
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function (Blueprint $table)
        {
            $table->dropColumn('language');
            $table->dropColumn('payment_option');
        });
    }
}
