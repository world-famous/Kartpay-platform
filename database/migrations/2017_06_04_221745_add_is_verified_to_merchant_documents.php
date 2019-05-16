<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsVerifiedToMerchantDocuments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('merchant_documents', 'proprietor_pan_card_path'))
        {
            Schema::table('merchant_documents', function (Blueprint $table)
            {
      				$table->string('proprietor_pan_card_is_verified')->nullable()->after('proprietor_pan_card_path');
      				$table->integer('proprietor_pan_card_verified_by_admin_id')->unsigned()->nullable()->after('proprietor_pan_card_is_verified');

      				$table->string('gumasta_is_verified')->nullable()->after('gumasta_path');
      				$table->integer('gumasta_verified_by_admin_id')->unsigned()->nullable()->after('gumasta_is_verified');

      				$table->string('vat_certificate_is_verified')->nullable()->after('vat_certificate_path');
      				$table->integer('vat_certificate_verified_by_admin_id')->unsigned()->nullable()->after('vat_certificate_is_verified');

      				$table->string('cst_certificate_is_verified')->nullable()->after('cst_certificate_path');
      				$table->integer('cst_certificate_verified_by_admin_id')->unsigned()->nullable()->after('cst_certificate_is_verified');

      				$table->string('excise_registration_no_is_verified')->nullable()->after('excise_registration_no_path');
      				$table->integer('excise_registration_no_verified_by_admin_id')->unsigned()->nullable()->after('excise_registration_no_is_verified');

      				$table->string('passport_is_verified')->nullable()->after('passport_path');
      				$table->integer('passport_verified_by_admin_id')->unsigned()->nullable()->after('passport_is_verified');

      				$table->string('aadhar_card_is_verified')->nullable()->after('aadhar_card_path');
      				$table->integer('aadhar_card_verified_by_admin_id')->unsigned()->nullable()->after('aadhar_card_is_verified');

      				$table->string('driving_license_is_verified')->nullable()->after('driving_license_path');
      				$table->integer('driving_license_verified_by_admin_id')->unsigned()->nullable()->after('driving_license_is_verified');

      				$table->string('voter_id_card_is_verified')->nullable()->after('voter_id_card_path');
      				$table->integer('voter_id_card_verified_by_admin_id')->unsigned()->nullable()->after('voter_id_card_is_verified');

      				$table->string('property_tax_receipt_is_verified')->nullable()->after('property_tax_receipt_path');
      				$table->integer('property_tax_receipt_verified_by_admin_id')->unsigned()->nullable()->after('property_tax_receipt_is_verified');

      				$table->string('bank_canceled_cheque_is_verified')->nullable()->after('bank_canceled_cheque_path');
      				$table->integer('bank_canceled_cheque_verified_by_admin_id')->unsigned()->nullable()->after('bank_canceled_cheque_is_verified');

      				$table->string('audited_balance_sheet_is_verified')->nullable()->after('audited_balance_sheet_path');
      				$table->integer('audited_balance_sheet_verified_by_admin_id')->unsigned()->nullable()->after('audited_balance_sheet_is_verified');

      				$table->string('current_account_statement_is_verified')->nullable()->after('current_account_statement_path');
      				$table->integer('current_account_statement_verified_by_admin_id')->unsigned()->nullable()->after('current_account_statement_is_verified');

      				$table->string('income_tax_return_is_verified')->nullable()->after('income_tax_return_path');
      				$table->integer('income_tax_return_verified_by_admin_id')->unsigned()->nullable()->after('income_tax_return_is_verified');

      				$table->string('kartpay_merchant_agreement_is_verified')->nullable()->after('kartpay_merchant_agreement_path');
      				$table->integer('kartpay_merchant_agreement_verified_by_admin_id')->unsigned()->nullable()->after('kartpay_merchant_agreement_is_verified');
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
    				$table->dropColumn('proprietor_pan_card_is_verified');
    				$table->dropColumn('proprietor_pan_card_verified_by_admin_id');
    				$table->dropColumn('gumasta_is_verified');
    				$table->dropColumn('gumasta_verified_by_admin_id');
    				$table->dropColumn('vat_certificate_is_verified');
    				$table->dropColumn('vat_certificate_verified_by_admin_id');
    				$table->dropColumn('cst_certificate_is_verified');
    				$table->dropColumn('cst_certificate_verified_by_admin_id');
    				$table->dropColumn('excise_registration_no_is_verified');
    				$table->dropColumn('excise_registration_no_verified_by_admin_id');
    				$table->dropColumn('passport_is_verified');
    				$table->dropColumn('passport_verified_by_admin_id');
    				$table->dropColumn('aadhar_card_is_verified');
    				$table->dropColumn('aadhar_card_verified_by_admin_id');
    				$table->dropColumn('driving_license_is_verified');
    				$table->dropColumn('driving_license_verified_by_admin_id');
    				$table->dropColumn('voter_id_card_is_verified');
    				$table->dropColumn('voter_id_card_verified_by_admin_id');
    				$table->dropColumn('property_tax_receipt_is_verified');
    				$table->dropColumn('property_tax_receipt_verified_by_admin_id');
    				$table->dropColumn('bank_canceled_cheque_is_verified');
    				$table->dropColumn('bank_canceled_cheque_verified_by_admin_id');
    				$table->dropColumn('audited_balance_sheet_is_verified');
    				$table->dropColumn('audited_balance_sheet_verified_by_admin_id');
    				$table->dropColumn('current_account_statement_is_verified');
    				$table->dropColumn('current_account_statement_verified_by_admin_id');
    				$table->dropColumn('income_tax_return_is_verified');
    				$table->dropColumn('income_tax_return_verified_by_admin_id');
    				$table->dropColumn('kartpay_merchant_agreement_is_verified');
    				$table->dropColumn('kartpay_merchant_agreement_verified_by_admin_id');
    			});
	      }
    }
}
