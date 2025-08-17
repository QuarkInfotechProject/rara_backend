<?php

namespace Modules\PageVault\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Media\Trait\HasMedia;
use Modules\Meta\Trait\HasMetaData;

class OurTeam extends Model
{

    use HasFactory, HasMetaData, HasMedia;

    protected $fillable = [
        'name',
        'position',
        'bio',
        'linkedIn_link',
        'order',
        'is_active',
        'created_at',
        'updated_at'
    ];

    public static function boot()
    {
        parent::boot();

        static::saved(function ($entity) {
            $entity->saveMetaData(request('meta', []));
            $entity->syncFiles(request('files', []));
        });

        static::deleting(function ($entity) {
            $entity->files()->detach();

            if ($entity->meta) {
                $entity->meta->delete();
            }
        });
    }

}
