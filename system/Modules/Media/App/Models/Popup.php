<?php

namespace Modules\Media\App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Media\Trait\HasMedia;

class Popup extends Model
{
    use SoftDeletes,HasMedia;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'status',
    ];
}
