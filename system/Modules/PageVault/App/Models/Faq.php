<?php

namespace Modules\PageVault\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faq extends Model
{

    // category => safety , volunteer, partner, host, impact, inqueryandcancellation

    use HasFactory;

    protected $fillable = [
        'question',
        'answer',
        'category',
        'order'
    ];

}
