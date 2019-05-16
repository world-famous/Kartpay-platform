<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{

    protected $fillable = [
                              'type', 'name', 'address',
                              'phone', 'email', 'zip',
                              'state', 'city',
                          ];

    /*
     * Get Transaction Class that related to Address
     *
    */
    public function transaction()
    {
        return $this->belongsTo(Transaction::class);
    }
    /*
     * END Get Transaction Class that related to Address
     *
    */
}
