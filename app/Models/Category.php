<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'name',
        'access_key',
        'secret_key',
        'partner_tag',
        'yahoo_id',
        'target_price',
        'fall_pro',
        'web_hook',
        'len',
        'file_name',
        'reg_num',
        'trk_num',
        'is_reg',
        'stop',
        'round',
    ];

    public function user() {
        return $this->belongsTo(
            User::class,
            'user_id'
        );
    }

    public function items() {
        return $this->hasMany(
            Item::class,
        );
    }
}
