<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddIsReceivedToMerchantDocuments extends Migration
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
      				$table->string('proprietor_pan_card_is_received')->nullable()->after('proprietor_pan_card_verified_by_admin_id');
      				$table->integer('proprietor_pan_card_received_by_admin_id')->unsigned()->nullable()->after('proprietor_pan_card_is_received');

      				$table->string('gumasta_is_received')->nullable()->after('gumasta_verified_by_admin_id');
      				$table->integer('gumasta_received_by_admin_id')->unsigned()->nullable()->after('gumasta_is_received');

      				$table->string('gst_in_is_received')->nullable()->after('gst_in_verified_by_admin_id');
      				$table->integer('gst_in_received_by_admin_id')->unsigned()->nullable()->after('gst_in_is_received');

      				$table->string('passport_is_received')->nullable()->after('passport_verified_by_admin_id');
      				$table->integer('passport_received_by_admin_id')->unsigned()->nullable()->after('passport_is_received');

      				$table->string('aadhar_card_is_received')->nullable()->after('aadhar_card_verified_by_admin_id');
      				$table->integer('aadhar_card_received_by_admin_id')->unsigned()->nullable()->after('aadhar_card_is_received');

      				$table->string('driving_license_is_received')->nullable()->after('driving_license_verified_by_admin_id');
      				$table->integer('driving_license_received_by_admin_id')->unsigned()->nullable()->after('driving_license_is_received');

      				$table->string('voter_id_card_is_received')->nullable()->after('voter_id_card_verified_by_admin_id');
      				$table->integer('voter_id_card_received_by_admin_id')->unsigned()->nullable()->after('voter_id_card_is_received');

      				$table->string('property_tax_receipt_is_received')->nullable()->after('property_tax_receipt_verified_by_admin_id');
      				$table->integer('property_tax_receipt_received_by_admin_id')->unsigned()->nullable()->after('property_tax_receipt_is_received');

      				$table->string('bank_canceled_cheque_is_received')->nullable()->after('bank_canceled_cheque_verified_by_admin_id');
      				$table->integer('bank_canceled_cheque_received_by_admin_id')->unsigned()->nullable()->after('bank_canceled_cheque_is_received');

      				$table->string('audited_balance_sheet_is_received')->nullable()->after('audited_balance_sheet_verified_by_admin_id');
      				$table->integer('audited_balance_sheet_received_by_admin_id')->unsigned()->nullable()->after('audited_balance_sheet_is_received');

      				$table->string('current_account_statement_is_received')->nullable()->after('current_account_statement_verified_by_admin_id');
      				$table->integer('current_account_statement_received_by_admin_id')->unsigned()->nullable()->after('current_account_statement_is_received');

      				$table->string('income_tax_return_is_received')->nullable()->after('income_tax_return_verified_by_admin_id');
      				$table->integer('income_tax_return_received_by_admin_id')->unsigned()->nullable()->after('income_tax_return_is_received');

      				$table->string('kartpay_merchant_agreement_is_received')->nullable()->after('kartpay_merchant_agreement_verified_by_admin_id');
      				$table->integer('kartpay_merchant_agreement_received_by_admin_id')->unsigned()->nullable()->after('kartpay_merchant_agreement_is_received');
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
    				$table->dropColumn('proprietor_pan_card_is_received');
    				$table->dropColumn('proprietor_pan_card_received_by_admin_id');
    				$table->dropColumn('gumasta_is_received');
    				$table->dropColumn('gumasta_received_by_admin_id');
    				$table->dropColumn('gst_in_is_received');
    				$table->dropColumn('gst_in_received_by_admin_id');
    				$table->dropColumn('passport_is_received');
    				$table->dropColumn('passport_received_by_admin_id');
    				$table->dropColumn('aadhar_card_is_received');
    				$table->dropColumn('aadhar_card_received_by_admin_id');
    				$table->dropColumn('driving_license_is_received');
    				$table->dropColumn('driving_license_received_by_admin_id');
    				$table->dropColumn('voter_id_card_is_received');
    				$table->dropColumn('voter_id_card_received_by_admin_id');
    				$table->dropColumn('property_tax_receipt_is_received');
    				$table->dropColumn('property_tax_receipt_received_by_admin_id');
    				$table->dropColumn('bank_canceled_cheque_is_received');
    				$table->dropColumn('bank_canceled_cheque_received_by_admin_id');
    				$table->dropColumn('audited_balance_sheet_is_received');
    				$table->dropColumn('audited_balance_sheet_received_by_admin_id');
    				$table->dropColumn('current_account_statement_is_received');
    				$table->dropColumn('current_account_statement_received_by_admin_id');
    				$table->dropColumn('income_tax_return_is_received');
    				$table->dropColumn('income_tax_return_received_by_admin_id');
    				$table->dropColumn('kartpay_merchant_agreement_is_received');
    				$table->dropColumn('kartpay_merchant_agreement_received_by_admin_id');
    			});
	      }
    }
}
