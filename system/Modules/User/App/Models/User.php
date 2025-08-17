<?php

namespace Modules\User\App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Passport\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory;

    public const STATUS_ACTIVE = 1;
    public const STATUS_BLOCKED = 2;

    /**
     * @var string[]
     */
    public static $userStatus = [
        self::STATUS_ACTIVE => 'Active',
        self::STATUS_BLOCKED => 'Blocked',
    ];

    protected $guard = 'user';


    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'uuid',
        'phone_no',
        'email',
        'date_of_birth',
        'gender',
        'offers_notification',
        'profile_picture',
        'full_name',
        'password',
        'status',
        'remarks',
        'oauth_type',
        'oauth_id',
        'country'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
