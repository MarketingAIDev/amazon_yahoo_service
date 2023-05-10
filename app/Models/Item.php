<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
	use HasFactory;

	protected $table = 'items';

	protected $fillable = [
		'user_id',
		'category_id',
		'img_url',
		'name',
		'asin',
		'jan',

		'register_price',
		'target_price',
		'min_price',
		'shop_url',
		
		'status',
		'is_notified',
	];

	public function category() {
		return $this->belongsTo(
			User::class,
			'category_id'
		);
	}

	public function user() {
		return $this->belongsTo(
			User::class,
			'user_id'
		);
	}
}
