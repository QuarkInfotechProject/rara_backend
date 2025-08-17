<?php

namespace Modules\PageVault\App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Modules\Media\Trait\HasMedia;
use Modules\Meta\Trait\HasMetaData;

class PageVault extends Model
{

    use HasFactory, HasMetaData, HasMedia;

    const TYPE_ABOUT_US = 'aboutUs';
    const TYPE_TERMS_AND_CONDITIONS = 'termsAndConditions';
    const TYPE_PRIVACY_POLICY = 'privacyPolicy';
    const TYPE_INQUERY_AND_CANCELLATION= 'inQueryAndCancellation';
    const TYPE_IMPACT = 'impact';
    const TYPE_HOST = 'host';
    const TYPE_PARTNER = 'partner';
    const TYPE_VOLUNTEER = 'volunteer';
    const TYPE_SAFETY = 'safety';

    protected $fillable = [
        'type',
        'title',
        'slug',
        'header',
        'main_image',
        'content1',
        'content2',
        'content3',
        'is_active',
        'created_at',
        'updated_at',
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
