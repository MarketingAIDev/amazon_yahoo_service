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
        'y_img_url',
        'item_name',
        'code_kind',
        'asin',
        'jan',

        'y_register_price',
        'y_target_price',
        'y_min_price',
        'postage',
        'y_shop_list',
        'y_shops',
        
        'status',
        'in_stock',
        'is_mailed',
        'update_time',
    ];
}
