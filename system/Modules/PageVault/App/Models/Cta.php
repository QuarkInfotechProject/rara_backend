<?php

namespace Modules\PageVault\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cta extends Model
{
    use HasFactory;

    protected $fillable = [
        'fullname',
        'email',
        'phone_number',
        'description',
        'type',
        'status'
    ];

    const STATUS_NEW = 'new';
    const STATUS_PROCESSING = 'processing';
    const STATUS_CONTACTED = 'contacted';
    const STATUS_COMPLETED = 'completed';
    const STATUS_ONHOLD = 'onhold';
    const STATUS_CANCELLED = 'cancelled';

    const TYPE_CONTACT = 'contact';
    const TYPE_VOLUNTEER = 'volunteer';
    const TYPE_PARTNER = 'partner';
    const TYPE_HOST = 'host';


}
