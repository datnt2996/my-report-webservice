<?php

namespace App\Model;

use Carbon\Carbon;
use App\Model\Enroll;
use App\Helpers\AuthHelper;
use Jenssegers\Mongodb\Eloquent\SoftDeletes;
use Jenssegers\Mongodb\Eloquent\Model as Eloquent;

class Event extends Eloquent
{
	use SoftDeletes;
	protected $connection = 'mongodb';
	protected $collection = 'events';
	protected $softDelete = true;
	protected $fillable =
	[
		'_id',
		'user_id',
		'address',
		'number_phone',
		'title',
		'description',
		'handle',
		'represent_position',
		'organization_name',
		'time_start',
		'time_end',
		'price',
		'total_person',
		'category_lv1',
		'category_lv2',
		'trade_assurance',
		'is_trade_assurance',
		'trade_assurance_status',
		'trade_assurance_reason',
		'total_rate',
		'total_star',
		'published_at',
		'created_at',
		'updated_at'
		
	];
	protected $primarykey = 'id';
	protected $guarded = [];

	public function scopeQueryCategory($query, $category)
	{
		if (!empty($category)) {
			return $query->where('category_lv1', $category);
		}
	}
	
	public function event_images()
	{
		return $this->hasMany('App\Model\EventImage', 'event_id');
	}
	public function event_comment()
	{
		return $this->hasMany('App\Model\Comment', 'event_id');
	}
	public function event_image()
	{
		return $this->hasMany('App\Model\EventImage', 'event_id')->where('position', 1);
	}
	public function event_times()
	{
		return $this->hasMany('App\Model\EventTime', 'event_id');
	}

	public static function boot()
	{
		parent::boot();
		static::deleting(function ($event) {
			$event->event_comment()->delete();
			$event->event_images()->delete();
			$event->event_times()->delete();
		});
	}

	public function scopeQueryEventTimeStatus($query, $status)
	{
		$time = Carbon::now()->toDateTimeString();
	
		if($status == 'done'){
			return $query->where('time_end', '<',$time);
		}
		if($status == 'doing'){
			return $query->where('time_start', '<=',$time)
			->where('time_end', '>=',$time);
		}
		if($status == 'will'){
			return $query->where('time_start', '>',$time);
		}
	}
	public function scopeQueryByEnroll($query, $status, $userId){
		if (!empty($status)){
			$event_ids = Enroll::where('user_id', $userId)->select('event_id')->pluck('event_id');
			return $query->whereIn('_id', $event_ids);
		}
	}
	public function scopeQueryTextSearch($query, $word)
	{
		if (!empty($word)) {
			return $query->whereRaw([
				'$text'=>
				[
					'$search'=> "\"" . $word . "\""
				]
			]);
		}
	}
	public function scopeQueryByUserId($query, $userId)
	{
		if (!empty($userId)) {
			return $query->where('user_id', $userId);
		}
	}
}