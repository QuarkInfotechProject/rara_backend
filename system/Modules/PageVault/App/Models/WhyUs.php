<?php

namespace Modules\PageVault\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Media\Trait\HasMedia;
use Modules\Meta\Trait\HasMetaData;

class WhyUs extends Model
{
    use HasFactory, HasMedia;

    protected $fillable = [
        'title',
        'description',
        'link',
        'order',
        'is_active',
    ];

    public static function boot()
    {
        parent::boot();

        static::saved(function ($entity) {
            $entity->syncFiles(request('files', []));
        });

        static::deleting(function ($entity) {
            $entity->files()->detach();
        });
    }


}
