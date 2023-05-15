<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MailLog extends Model
{
    use HasFactory;

    protected $table = 'mail_logs';

    protected $fillable = [
        'user_id',
        'category_id',
        'asin',
        'msg',
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
