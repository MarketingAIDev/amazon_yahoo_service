<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\CanResetPassword;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        '_token',
        'email',
        'email_verified_at',
        'password',        
        'role',        
        'family_name',
        'cell_phone',
        'y_register_percent',
        'y_lower_bound',
        'y_upper_bound',
        'fee_include',
        'ex_key',
        'is_tracking',
        'is_permitted',
        'register_number',
        'yahoo_token',
        'yahoo_token1',
        'yahoo_token2',
        'access_key',
        'secret_key',
        'partner_tag',
        'web_hook',
        'fall_pro',
        'is_registering',
        'len',
        'name',
        'total',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array
     */
    protected $hidden = [
        'password',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = [
    ];

    public function csv_list() {
        return $this->hasMany(
            UserLog::class,
            'user_id'
        );
    }
}
