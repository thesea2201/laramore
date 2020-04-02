<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UrlView extends Model
{
    /**
     * Fields that are mass assignable
     *
     * @var array
     */
    protected $fillable = ['ip_address', 'user_id'];
}
