<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class Category extends Eloquent
{
	use SoftDeletes;
	protected $connection = 'mongodb';
	protected $collection = 'categories';
	protected $softDelete = true;
	protected $fillable =
	[
		'_id',
		'title',
		'slug',
		'description',
		'level',
		'parent_id',
		'published',
		'updated_at',
		'created_at',
	];
	protected $primarykey = 'id';
	protected $guarded = [];

	public function scopeQueryLevel($query, $level)
	{
		if (!empty($level)) {
			return $query->where('level', $level);
		}
	}
}