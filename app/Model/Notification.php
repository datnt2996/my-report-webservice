<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Notification extends Eloquent
{
    use SoftDeletes;
    protected $connection = 'mongodb';
    protected $collection = 'notifications';
    protected $softDelete = true;
    protected $fillable =
    [
        '_id',
        'user_id',
        'body',
        'content',
        'type',
        'status',
        'created_at',
        'updated_at'
    ];
    protected $primarykey = 'id';
    protected $guarded = [];
}