<?php

namespace Modules\Sales\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Agent extends Model
{
    use SoftDeletes, HasFactory;

    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'phone',
        'company',
        'website',
        'homestay_margin',
        'experience_margin',
        'package_margin',
        'pan_no',
        'address',
        'city',
        'country',
        'postal_code',
        'contract_start_date',
        'contract_end_date',
        'bank_name',
        'bank_account_number',
        'bank_ifsc_code',
        'notes',
        'is_active'
    ];
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function sales()
    {
        return $this->hasMany(Sales::class);
    }
}
