<?php

namespace Modules\Blog\App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogLog extends Model
{

    protected $fillable = [
        'type',
        'data',
        'blog_id'
    ];

}
