<?php

namespace Modules\AdminUser\App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class AdminUser extends Authenticatable
{
    use HasRoles, HasFactory, Notifiable, HasApiTokens, SoftDeletes;

    public const ACTIVE = 1;
    public const INACTIVE = 2;
    public const DELETED = 3;

    /**
     * @var string[]
     */
    public static $adminUserStatus = [
        self::ACTIVE => 'Active',
        self::INACTIVE => 'Inactive',
        self::DELETED => 'Deleted',
    ];

    protected $guard_name = 'admin';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id',
        'uuid',
        'name',
        'super_admin',
        'email',
        'password',
        'status',
        'remarks',
        'bio'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
}
