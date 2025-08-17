<?php

namespace Modules\PageVault\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Media\Trait\HasMedia;

class Promotion extends Model
{
    use HasFactory, HasMedia;

    protected $fillable = [
        'name',
        'description',
        'link',
        'placement_place',
        'is_active',
    ];

    // placementsplace = homepage, sidebar, popup, banner, header, footer

    protected $casts = [
        'is_active' => 'boolean',
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
