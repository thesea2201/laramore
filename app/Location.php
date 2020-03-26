<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [
        'name',
        'address_address',
        'lat',
        'long',
        'type'
    ];
}
