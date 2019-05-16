<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddBusinessInformationToMerchants extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasTable('merchants'))
        {
    			Schema::table('merchants', function (Blueprint $table)
          {
    				if (!Schema::hasColumn('merchants', 'firm_name'))
            {
    					$table->string('firm_name', 50)->nullable()->after('avatar_file_name');
    					$table->string('firm_address', 150)->nullable()->after('firm_name');
    					$table->string('city', 20)->nullable()->after('firm_address');
    					$table->string('state', 50)->nullable()->after('city');
    					$table->string('country', 50)->nullable()->after('state');
    					$table->string('business_contact_no', 10)->nullable()->after('country');

    					$table->string('vat_doc_file_name', 255)->nullable()->after('business_contact_no');
    					$table->string('vat_doc_path', 255)->nullable()->after('vat_doc_file_name');
    					$table->integer('vat_doc_is_verified')->default(0)->nullable()->after('vat_doc_path');
    					$table->integer('vat_doc_verified_by_admin_id')->unsigned()->nullable()->after('vat_doc_is_verified');

    					$table->string('cst_doc_file_name', 255)->nullable()->after('vat_doc_verified_by_admin_id');
    					$table->string('cst_doc_path', 255)->nullable()->after('cst_doc_file_name');
    					$table->integer('cst_doc_is_verified')->default(0)->nullable()->after('cst_doc_path');
    					$table->integer('cst_doc_verified_by_admin_id')->unsigned()->nullable()->after('cst_doc_is_verified');

    					$table->string('service_tax_doc_file_name', 255)->nullable()->after('cst_doc_verified_by_admin_id');
    					$table->string('service_tax_doc_path', 255)->nullable()->after('service_tax_doc_file_name');
    					$table->integer('service_tax_doc_is_verified')->default(0)->nullable()->after('service_tax_doc_path');
    					$table->integer('service_tax_doc_verified_by_admin_id')->unsigned()->nullable()->after('service_tax_doc_is_verified');

    					$table->string('gumasta_doc_file_name', 255)->nullable()->after('service_tax_doc_verified_by_admin_id');
    					$table->string('gumasta_doc_path', 255)->nullable()->after('gumasta_doc_file_name');
    					$table->integer('gumasta_doc_is_verified')->default(0)->nullable()->after('gumasta_doc_path');
    					$table->integer('gumasta_doc_verified_by_admin_id')->unsigned()->nullable()->after('gumasta_doc_is_verified');
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
        if (Schema::hasTable('merchants'))
        {
    			Schema::table('merchants', function (Blueprint $table)
          {
    				if (Schema::hasColumn('merchants', 'firm_name'))
            {
    					$table->dropColumn('firm_name');
    					$table->dropColumn('firm_address');
    					$table->dropColumn('city');
    					$table->dropColumn('state');
    					$table->dropColumn('country');
    					$table->dropColumn('business_contact_no');

    					$table->dropColumn('vat_doc_file_name');
    					$table->dropColumn('vat_doc_path');
    					$table->dropColumn('vat_doc_is_verified');
    					$table->dropColumn('vat_doc_verified_by_admin_id');

    					$table->dropColumn('cst_doc_file_name');
    					$table->dropColumn('cst_doc_path');
    					$table->dropColumn('cst_doc_is_verified');
    					$table->dropColumn('cst_doc_verified_by_admin_id');

    					$table->dropColumn('service_tax_doc_file_name');
    					$table->dropColumn('service_tax_doc_path');
    					$table->dropColumn('service_tax_doc_is_verified');
    					$table->dropColumn('service_tax_doc_verified_by_admin_id');

    					$table->dropColumn('gumasta_doc_file_name');
    					$table->dropColumn('gumasta_doc_path');
    					$table->dropColumn('gumasta_doc_is_verified');
    					$table->dropColumn('gumasta_doc_verified_by_admin_id');
    				}
    			});
	      }
    }
}
