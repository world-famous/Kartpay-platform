<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class MerchantPersonalInformation extends Model
{
    protected $fillable = [
                            'merchant_id',
                    	      'owner_name',
                    	      'personal_address',
                            'personal_contact_no',
                            'city',
                            'state',
                            'country',
                            'personal_pan_card',
                            'personal_pan_card_filename',
                            'personal_pan_card_path',
                            'personal_pan_card_is_verified',
                            'personal_pan_card_verified_by_admin_id',
                            'aadhar_no',
                            'aadhar_filename',
                            'aadhar_path',
                            'aadhar_is_verified',
                            'aadhar_verified_by_admin_id',
                            'passport_no',
                            'passport_filename',
                            'passport_path',
                            'passport_is_verified',
                            'passport_verified_by_admin_id',
                            'voter_id_card_filename',
                            'voter_id_card_path',
                            'voter_id_card_is_verified',
                            'voter_id_card_verified_by_admin_id',
                            'electricity_bill_filename',
                            'electricity_bill_path',
                            'electricity_bill_is_verified',
                            'electricity_bill_verified_by_admin_id',
                            'landline_bill_filename',
                            'landline_bill_path',
                            'landline_bill_is_verified',
                            'landline_bill_verified_by_admin_id',
                            'bank_account_statement_filename',
                            'bank_account_statement_path',
                            'bank_account_statement_is_verified',
                            'bank_account_statement_verified_by_admin_id',
                        ];
}
