<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class SetDocumentFileNameToAllowNullMerchantDocument extends Migration
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
    				$table->string('document_file_name', 255)->nullable()->change();
    				$table->string('document_path', 255)->nullable()->change();
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
        //
    }
}
