<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class EventImage extends Eloquent
{
    use SoftDeletes;
    protected $connection = 'mongodb';
    protected $collection = 'event_images';
    protected $softDelete = true;
    protected $fillable =
    [
        '_id',
        'event_id',
        'image_id',
        'position',
        'src',
        'width',
        'height',
        'is_checked',
        'is_used',
        'created_at',
        'updated_at'
    ];
    protected $primarykey = 'id';
    protected $guarded = [];
}