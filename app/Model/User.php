<?php

namespace App\Model;

use Jenssegers\Mongodb\Eloquent\Model as Eloquent;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;

class User extends Eloquent
{
	use SoftDeletes;
	protected $connection = 'mongodb';
	protected $collection = 'users';
	protected $softDelete = true;
	protected $fillable =
		[
			'id',
			'full_name',
			'email',
			'screen_name',
			'image',
			'number_phone',
			'receive_announcements',
			'locale',
			'user_type',
			'password',
			'roles',
			'is_organization',
			'is_admin',
			'is_vip',
			'created_at',
			'updated_at'
		];
	protected $primarykey = 'id';
	protected $guarded = [];
}