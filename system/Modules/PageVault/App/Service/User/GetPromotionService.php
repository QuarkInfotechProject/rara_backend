<?php

namespace Modules\PageVault\App\Service\User;

use Modules\PageVault\App\Models\Promotion;

class GetPromotionService
{

    public function execute()
    {
        $whyUsItems = Promotion::select('id', 'name', 'description', 'link')
            ->where('is_active', true)
            ->where('placement_place', 'homepage')
            ->get();

        return $whyUsItems->map(function ($item) {
            return [
                'title' => $item->title,
                'description' => $item->description,
                'link' => $item->link,
                'desktop_image' => $this->getMediaFiles($item, 'promotionImageDesktop'),
                'mobile_image' => $this->getMediaFiles($item, 'promotionImageMobile'),
            ];
        });
    }

    private function getMediaFiles($whyUs, $type)
    {
        $baseImageFiles = $whyUs->filterFiles($type)->get();
        $baseImage = $baseImageFiles->map(function ($file) {
            return $file->path . '/' . $file->temp_filename;
        })->first();

        return $baseImage ?? '';
    }

}
