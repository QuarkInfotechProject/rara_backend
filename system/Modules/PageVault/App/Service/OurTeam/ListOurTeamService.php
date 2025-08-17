<?php

namespace Modules\PageVault\App\Service\OurTeam;

use Modules\PageVault\App\Models\OurTeam;

class ListOurTeamService
{
    public function listOurTeam()
    {
        $teamMembers = OurTeam::query()
            ->select('id', 'name', 'position', 'bio', 'linkedIn_link', 'order', 'is_active')
            ->orderBy('order', 'asc')
            ->get();

        return $teamMembers->map(function ($member) {
            $mediaFiles = $this->getTeamMemberMediaFiles($member);

            return [
                'id' => $member->id,
                'name' => $member->name,
                'position' => $member->position,
                'bio' => $member->bio,
                'linkedIn_link' => $member->linkedIn_link,
                'order' => $member->order,
                'is_active' => $member->is_active,
                'ourTeamProfilePic' => $mediaFiles ?? '',
            ];
        });
    }

    private function getTeamMemberMediaFiles($teamMember)
    {
        $baseImageFiles = $teamMember->filterFiles('ourTeamProfilePic')->get();
        $baseImage = $baseImageFiles->map(function ($file) {
            return [
                'id' => $file->id,
                'baseImageUrl' => $file->path . '/' . $file->temp_filename,
            ];
        })->first();

        return $baseImage ?? '';
    }

}
