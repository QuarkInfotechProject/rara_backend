<?php

namespace Modules\PageVault\App\Service\PageVault;

use Modules\PageVault\App\Models\PageVault;

class GetPageDetailService
{
    public function getPageDetail(string $type)
    {
        $page = PageVault::where('type', $type)->firstOrFail();

        if (!$page) {
            throw new \Exception('Page Not found');
        }

        $entityMetadata = $page->meta()->first();

        $mediaFiles = $this->getMediaFiles($page);

        return [
            'title' => $page->title,
            'slug' => $page->slug,
            'header' => $page->header,
            'content1' => $page->content1 ?? null,
            'content2' => $page->content2 ?? null,
            'content3' => $page->content3 ?? null,
            'is_active' => $page->is_active,
            'updated_at' => $page->updated_at,
            'meta' => [
                'metaTitle' => $entityMetadata->meta_title ?? null,
                'keywords' => isset($entityMetadata->meta_keywords) ? json_decode($entityMetadata->meta_keywords) : null,
                'metaDescription' => $entityMetadata->meta_description ?? null,
            ],
            'featured_image' => $mediaFiles

        ];

    }

    private function getMediaFiles($blog)
    {
        $baseImageFiles = $blog->filterFiles('featuredImage')->get();

        $baseImage = $baseImageFiles->map(function ($file) {
            return [
                'id' => $file->id,
                'baseImageUrl' => $file->path . '/' . $file->temp_filename,
            ];
        })->first();

        return $baseImage ?? '';
    }

}
