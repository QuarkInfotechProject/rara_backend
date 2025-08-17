<?php

namespace Modules\PageVault\App\Service\User;

use Modules\PageVault\App\Models\OurTeam;

class GetTeamListService
{

    public function execute()
    {
        $whyUsItems = OurTeam::select('id', 'name', 'position', 'bio', 'linkedIn_link')
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        return $whyUsItems->map(function ($item) {
            return [
                'name' => $item->name,
                'position' => $item->position,
                'bio' => $item->bio,
                'linkedin_link' => $item->linkedIn_link,
                'whyUsImage' => $this->getMediaFiles($item),
            ];
        });
    }

    private function getMediaFiles($whyUs)
    {
        $baseImageFiles = $whyUs->filterFiles('ourTeamProfilePic')->get();
        $baseImage = $baseImageFiles->map(function ($file) {
            return $file->path . '/' . $file->temp_filename;
        })->first();

        return $baseImage ?? '';
    }


}
