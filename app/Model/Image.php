<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Image extends Eloquent
{
    use SoftDeletes;
    protected $connection = 'mongodb';
    protected $collection = 'images';
    protected $softDelete = true;
    protected $fillable =
    [
        '_id',
        'event_id',
        'image_id',
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