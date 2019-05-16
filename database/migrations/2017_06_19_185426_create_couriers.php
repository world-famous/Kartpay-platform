<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouriers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('couriers', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('slug');
            $table->string('name');
            $table->string('phone');
            $table->string('other_name');
            $table->string('web_url');
            $table->string('required_fields');
            $table->string('optional_fields');
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
        Schema::drop('couriers');
    }
}
