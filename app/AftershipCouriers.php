<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class AftershipCouriers extends Model
{
    protected $fillable = [
                          	'slug',
                          	'name',
                          	'phone',
                          	'other_name',
                          	'web_url',
                          	'required_fields',
                          	'optional_fields',
                          	'default_language',
                          	'support_languages',
                          	'service_from_country_iso3'
                          ];

    /**
    * @param $field (string)
    * @param $value (string)
    */
    public static function details($field, $value)
    {
       $courier = self::where($field, $value)->first();

        if($courier)
        {
            return $courier;
        }

        $aftership = new Aftership();
        $couriers = $aftership->couriers();

        $insert = [];

        if(!$couriers['data']['couriers'])
        {
            return false;
        }

        foreach($couriers['data']['couriers'] as $c)
        {
            $details = $c;

            foreach($c as $key => $x)
            {
                if(is_array($x))
                {
                    $details[$key] = json_encode($x);
                }
            }

            $info = self::where('slug', $c['slug'])->first();

            if($info)
            {
                self::update($details, ['slug', $c['slug']]);
                continue;
            }

            if($c[$field] == $value)
            {
                return self::create($details);
            }
            $insert[] = $details;
        }
        self::insert($insert);
        return false;
    }
}
