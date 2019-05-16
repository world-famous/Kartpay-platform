<?php

use Illuminate\Database\Seeder;

use App\Merchant;

class UpdateMerchantIds extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $merchants = Merchant::get();

        if($merchants)
        {
            $merchant_id = 0;
          	foreach($merchants as $merchant)
            {
          		$details = Merchant::where('id', $merchant->id)->first();

          		if($details->type == 'merchant')
              {
                  $details->merchant_id = 0;
                  $merchant_id = $details->id;
              }
              else if($details->type == 'staff')
              {
                  $details->merchant_id = 1;
                  $details->merchant_id = $merchant_id;
              }
              $details->save();
        	}
        }
    }
}
