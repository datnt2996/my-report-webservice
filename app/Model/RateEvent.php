<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class RateEvent extends Eloquent
{
    protected $connection = 'mongodb';
    protected $collection = 'rate_events';
    protected $softDelete = true;
    protected $fillable =
    [
        '_id',
        'event_id',
        'user_id',
        'start',
        'title',
        'content',
        'created_at',
        'updated_at'
    ];
    protected $primarykey = 'id';
    protected $guarded = [];
}