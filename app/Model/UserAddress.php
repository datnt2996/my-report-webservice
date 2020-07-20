<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class UserAddress extends Eloquent
{
    use SoftDeletes;
    protected $connection = 'mongodb';
    protected $collection = 'users_address';
    protected $softDelete = true;
    protected $fillable =
    [
        'id',
        'user_id',
        'first_name',
        'last_name',
        'company',
        'address1',
        'address2',
        'city',
        'province',
        'country',
        'zip',
        'phone',
        'province_code',
        'country_code',
        'address_default',
        'created_at',
        'updated_at'
    ];
    protected $primarykey = 'id';
    protected $guarded = [];
}