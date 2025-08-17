<?php

namespace Modules\User\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NextErrorLog extends Model
{
    use HasFactory;

    protected $table = 'next_error_logs';

    protected $fillable = [
        'name',
        'stack',
        'message',
        'source'
    ];
}
