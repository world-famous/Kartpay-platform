<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsAdminApprovalToMerchantDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('merchant_documents', function (Blueprint $table)
        {
    			$table->integer('is_admin_approval')->default('0');
    		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('merchant_documents', function (Blueprint $table)
        {
    			$table->dropColumn('is_admin_approval');
    		});
    }
}
