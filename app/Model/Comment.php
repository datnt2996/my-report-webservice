<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Comment extends Eloquent
{
    use SoftDeletes;
    protected $connection = 'mongodb';
    protected $collection = 'comments';
    protected $softDelete = true;
    protected $fillable =
    [
        '_id',
        'event_id',
        'user_id',
        'reference_comment_id',
        'title',
        'image_id',
        'note',
        'message',
        'created_at',
        'updated_at'
    ];
    protected $primarykey = 'id';
    protected $guarded = [];
}