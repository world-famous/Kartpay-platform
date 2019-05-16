<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table)
        {
            $table->increments('id');
            $table->string('kartpay_id', 6)->unique();
            $table->string('merchant_id', 8);
            $table->string('access_key', 20);
            $table->string('merchant_order_id')->nullable();
            $table->string('currency', 3);
            $table->decimal('merchant_order_amount', 10, 3);
            $table->string('customer_email');
            $table->string('customer_phone');
            $table->string('success_url');
            $table->string('failed_url');
            $table->string('encryption');
            $table->timestamp('paid_at')->nullable();
            $table->string('status')->default('Pending');
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
        Schema::dropIfExists('transactions');
    }
}
