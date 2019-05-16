<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddGstInToMerchantDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(Schema::hasTable('merchant_documents'))
        {
    			Schema::table('merchant_documents', function (Blueprint $table)
          {
    				$table->string('gst_in_file', 255)->nullable()->after('importer_exporter_code_verified_by_admin_id');
    				$table->string('gst_in_path', 255)->nullable()->after('gst_in_file');
    				$table->string('gst_in_is_verified')->nullable()->after('gst_in_path');
    				$table->integer('gst_in_verified_by_admin_id')->unsigned()->nullable()->after('gst_in_is_verified');
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
        if (Schema::hasTable('merchant_documents'))
        {
    			Schema::table('merchant_documents', function (Blueprint $table)
          {
    				$table->dropColumn('gst_in_file');
    				$table->dropColumn('gst_in_path');
    				$table->dropColumn('gst_in_is_verified');
    				$table->dropColumn('gst_in_verified_by_admin_id');
    			});
	      }
    }
}
