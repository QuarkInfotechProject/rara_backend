<?php

namespace Modules\PageVault\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CarRental extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'car_rentals';

    protected $fillable = [
        'user_name',
        'email',
        'contact',
        'max_people',
        'pickup_address',
        'destination_address',
        'pickup_time',
        'message',
        'type',
        'status',
    ];

    protected $dates = ['deleted_at'];

    protected $casts = [
        'pickup_time' => 'datetime',
    ];
}
