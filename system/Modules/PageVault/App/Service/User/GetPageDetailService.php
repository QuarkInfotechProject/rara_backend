<?php

namespace Modules\PageVault\App\Service\User;

use Modules\PageVault\App\Models\PageVault;

class GetPageDetailService
{

    public function getPageVaultData(string $slug): ?array
    {
        $pageVault = PageVault::where('slug', $slug)
            ->where('is_active', 1)
            ->select('id','type', 'title', 'slug', 'header', 'content1', 'content2', 'content3')
            ->first();

        if (!$pageVault) {
            return null;
        }
        $entityMetadata = $pageVault->meta()->first();

        return [
            'type' => $pageVault->type,
            'title' => $pageVault->title,
            'slug' => $pageVault->slug,
            'header' => $pageVault->header,
            'content1' => $pageVault->content1,
            'content2' => $pageVault->content2,
            'content3' => $pageVault->content3,
            'featuredImage' => $this->getMediaFiles($pageVault, 'featuredImage'),
            'meta' => [
                'metaTitle' => $entityMetadata->meta_title ?? null,
                'keywords' => isset($entityMetadata->meta_keywords) ? json_decode($entityMetadata->meta_keywords) : null,
                'metaDescription' => $entityMetadata->meta_description ?? null,
            ],
        ];
    }

    private function getMediaFiles(PageVault $pageVault, string $type): string
    {
        $baseImageFiles = $pageVault->filterFiles($type)->get();
        $baseImage = $baseImageFiles->map(function ($file) {
            return $file->path . '/' . $file->temp_filename;
        })->first();

        return $baseImage ?? '';
    }



}
