<?php

namespace Modules\PageVault\App\Service\OurTeam;

use Modules\PageVault\App\Models\OurTeam;

class GetDetailOurTeamService
{
    public function getOurTeamDetail($id)
    {

        $page = OurTeam::where('id', $id)->first();

        if (!$page) {
            throw new \Exception('Our Detail Not found');
        }

        $entityMetadata = $page->meta()->first();

        $mediaFiles = $this->getMediaFiles($page);

        return [
            'name' => $page->name,
            'position' => $page->position,
            'bio' => $page->bio,
            'linkedIn_link' => $page->linkedIn_Link ?? '',
            'order' => $page->order ?? '',
            'is_active' => $page->is_active,
            'updated_at' => $page->updated_at,
            'meta' => [
                'metaTitle' => $entityMetadata->meta_title ?? '',
                'keywords' => isset($entityMetadata->meta_keywords) ? json_decode($entityMetadata->meta_keywords) : null,
                'metaDescription' => $entityMetadata->meta_description ?? '',
            ],
            'ourTeamProfilePic' => $mediaFiles
        ];
    }


    private function getMediaFiles($blog)
    {
        $baseImageFiles = $blog->filterFiles('ourTeamProfilePic')->get();

        $baseImage = $baseImageFiles->map(function ($file) {
            return [
                'id' => $file->id,
                'baseImageUrl' => $file->path . '/' . $file->temp_filename,
            ];
        })->first();

        return $baseImage ?? '';
    }

}
