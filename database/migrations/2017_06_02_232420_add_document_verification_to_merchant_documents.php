<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDocumentVerificationToMerchantDocuments extends Migration
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
    				$table->string('proprietor_pan_card_file', 255)->nullable();
    				$table->string('proprietor_pan_card_path', 255)->nullable();
    				$table->string('gumasta_file', 255)->nullable();
    				$table->string('gumasta_path', 255)->nullable();
    				$table->string('vat_certificate_file', 255)->nullable();
    				$table->string('vat_certificate_path', 255)->nullable();
    				$table->string('cst_certificate_file', 255)->nullable();
    				$table->string('cst_certificate_path', 255)->nullable();
    				$table->string('excise_registration_no_file', 255)->nullable();
    				$table->string('excise_registration_no_path', 255)->nullable();
    				$table->string('importer_exporter_code_file', 255)->nullable();
    				$table->string('importer_exporter_code_path', 255)->nullable();
    				$table->string('passport_file', 255)->nullable();
    				$table->string('passport_path', 255)->nullable();
    				$table->string('aadhar_card_file', 255)->nullable();
    				$table->string('aadhar_card_path', 255)->nullable();
    				$table->string('driving_license_file', 255)->nullable();
    				$table->string('driving_license_path', 255)->nullable();
    				$table->string('voter_id_card_file', 255)->nullable();
    				$table->string('voter_id_card_path', 255)->nullable();
    				$table->string('property_tax_receipt_file', 255)->nullable();
    				$table->string('property_tax_receipt_path', 255)->nullable();
    				$table->string('bank_canceled_cheque_file', 255)->nullable();
    				$table->string('bank_canceled_cheque_path', 255)->nullable();
    				$table->string('audited_balance_sheet_file', 255)->nullable();
    				$table->string('audited_balance_sheet_path', 255)->nullable();
    				$table->string('current_account_statement_file', 255)->nullable();
    				$table->string('current_account_statement_path', 255)->nullable();
    				$table->string('income_tax_return_file', 255)->nullable();
    				$table->string('income_tax_return_path', 255)->nullable();
    				$table->string('kartpay_merchant_agreement_file', 255)->nullable();
    				$table->string('kartpay_merchant_agreement_path', 255)->nullable();
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
    				$table->dropColumn('proprietor_pan_card_file');
    				$table->dropColumn('proprietor_pan_card_path');
    				$table->dropColumn('gumasta_file');
    				$table->dropColumn('gumasta_path');
    				$table->dropColumn('vat_certificate_file');
    				$table->dropColumn('vat_certificate_path');
    				$table->dropColumn('cst_certificate_file');
    				$table->dropColumn('cst_certificate_path');
    				$table->dropColumn('excise_registration_no_file');
    				$table->dropColumn('excise_registration_no_path');
    				$table->dropColumn('passport_file');
    				$table->dropColumn('passport_path');
    				$table->dropColumn('aadhar_card_file');
    				$table->dropColumn('aadhar_card_path');
    				$table->dropColumn('driving_license_file');
    				$table->dropColumn('driving_license_path');
    				$table->dropColumn('voter_id_card_file');
    				$table->dropColumn('voter_id_card_path');
    				$table->dropColumn('property_tax_receipt_file');
    				$table->dropColumn('property_tax_receipt_path');
    				$table->dropColumn('bank_canceled_cheque_file');
    				$table->dropColumn('bank_canceled_cheque_path');
    				$table->dropColumn('audited_balance_sheet_file');
    				$table->dropColumn('audited_balance_sheet_path');
    				$table->dropColumn('current_account_statement_file');
    				$table->dropColumn('current_account_statement_path');
    				$table->dropColumn('income_tax_return_file');
    				$table->dropColumn('income_tax_return_path');
    				$table->dropColumn('kartpay_merchant_agreement_file');
    				$table->dropColumn('kartpay_merchant_agreement_path');
    			});
	      }
    }

}
