<?php

namespace Modules\Sales\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Product\App\Models\Product;
use Modules\User\App\Models\User;

class Booking extends Model
{
    use SoftDeletes, HasFactory;


    public static $status = [
        'pending',
        'in-progress',
        'confirmed',
        'cancelled',
        'completed',
        'no-show',
    ];

    protected $fillable = [
        'product_id',
        'agent_id',
        'user_id',
        'product_name',
        'product_type',
        'from_date',
        'to_date',
        'adult',
        'children',
        'infant',
        'type',
        'status',
        'fullname',
        'mobile_number',
        'email',
        'country',
        'note',
        'has_responded',
        'ceo',
        'group_name',
        'room_required',
        'ref_no',
        'additional_note'
    ];

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function additionalBookingProducts()
    {
        return $this->hasMany(AdditionalBookingProduct::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
