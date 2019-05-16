<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsImporterExporterCodeIsReceivedToMerchantDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('merchant_documents', 'importer_exporter_code_path'))
        {
            Schema::table('merchant_documents', function (Blueprint $table)
            {
      				$table->string('importer_exporter_code_is_received')->nullable()->after('importer_exporter_code_verified_by_admin_id');
      				$table->integer('importer_exporter_code_received_by_admin_id')->unsigned()->nullable()->after('importer_exporter_code_is_received');
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
    				$table->dropColumn('importer_exporter_code_is_received');
    				$table->dropColumn('importer_exporter_code_received_by_admin_id');
    			});
	      }
    }
}
