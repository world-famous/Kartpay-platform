<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableBinEngine extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bin_engines', function (Blueprint $table)
        {
            $table->increments('id');
            $table->integer('bin_number')->unsigned()->unique();
            $table->string('card_issuer', 255);
            $table->string('card_type', 255);
            $table->string('bank_name', 255);
            $table->string('country_name', 255);
            $table->string('country_code', 255);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('bin_engines');
    }
}
