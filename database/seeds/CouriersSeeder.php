<?php

use Illuminate\Database\Seeder;

use App\Couriers;

class CouriersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
          	'0' => [
          		'sr_no' => 1,
          		'courier_name' => 'DTDC India',
          		'api_code' => 'DCIN'
          	],
            '1' => [
                'sr_no' => 2,
                'courier_name' => 'BLUEDART',
                'api_code' => 'BTIN'
            ],
            '2' => [
                'sr_no' => 3,
                'courier_name' => 'FedEx',
                'api_code' => 'FXIN'
            ],
            '3' => [
                'sr_no' => 4,
                'courier_name' => 'Delhivery',
                'api_code' => 'DYIN'
            ],
            '4' => [
                'sr_no' => 5,
                'courier_name' => 'India Post Domestic',
                'api_code' => 'IPIN'
            ],
            '5' => [
                'sr_no' => 6,
                'courier_name' => 'Safeexpress',
                'api_code' => 'SEIN'
            ],
            '6' => [
                'sr_no' => 7,
                'courier_name' => 'Aramex',
                'api_code' => 'AXIN'
            ],
            '7' => [
                'sr_no' => 8,
                'courier_name' => 'Red Express',
                'api_code' => 'REIN'
            ],
            '8' => [
                'sr_no' => 9,
                'courier_name' => 'GATI-KWE',
                'api_code' => 'GEIN'
            ],
            '9' => [
                'sr_no' => 10,
                'courier_name' => 'Professional Courier',
                'api_code' => 'PCIN'
            ],
            '10' => [
                'sr_no' => 11,
                'courier_name' => 'Red Express WayBill',
                'api_code' => 'RWIN'
            ],
            '11' => [
                'sr_no' => 12,
                'courier_name' => 'Gojavas',
                'api_code' => 'GSIN'
            ],
            '12' => [
                'sr_no' => 13,
                'courier_name' => 'First Flight Courier',
                'api_code' => 'FFIN'
            ],
            '13' => [
                'sr_no' => 14,
                'courier_name' => 'India Post International',
                'api_code' => 'ECOM EXPRESS'
            ],
            '14' => [
                'sr_no' => 15,
                'courier_name' => 'ECOM EXPRESS',
                'api_code' => 'EEIN'
            ],
            '15' => [
                'sr_no' => 16,
                'courier_name' => 'XpressBees',
                'api_code' => 'XSIN'
            ],
            '16' => [
                'sr_no' => 17,
                'courier_name' => 'DOTZOT',
                'api_code' => 'DTIN'
            ],
            '17' => [
                'sr_no' => 18,
                'courier_name' => 'NuvoEx',
                'api_code' => 'NXIN'
            ],
            '18' => [
                'sr_no' => 19,
                'courier_name' => 'DelCart',
                'api_code' => 'DTIN'
            ],
            '19' => [
                'sr_no' => 20,
                'courier_name' => 'QUANTIUM',
                'api_code' => 'QMIN'
            ],
            '20' => [
                'sr_no' => 21,
                'courier_name' => 'Parcelled',
                'api_code' => 'PDIN'
            ]

        ];

        Couriers::insert($data);
    }
}
