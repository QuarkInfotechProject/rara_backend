<?php

namespace Modules\PageVault\App\Service\Promotion;

use Modules\PageVault\App\Models\Promotion;

class GetPromotionDetailService
{

    public function getPromotionDetail(int $id): array
    {
        $promotion = Promotion::findOrFail($id);

        return [
            'id' => $promotion->id,
            'name' => $promotion->name,
            'description' => $promotion->description,
            'link' => $promotion->link,
            'placement_place' => $promotion->placement_place,
            'is_active' => $promotion->is_active ? 1 : 0,
            'promotionImageDesktop' => $this->getMediaFiles($promotion, 'promotionImageDesktop'),
            'promotionImageMobile' => $this->getMediaFiles($promotion, 'promotionImageMobile'),
        ];
    }

    private function getMediaFiles(Promotion $promotion, $type): array
    {
        $baseImageFiles = $promotion->filterFiles($type)->get();

        $baseImage = $baseImageFiles->map(function ($file) {
            return [
                'id' => $file->id,
                'baseImageUrl' => $file->path . '/' . $file->temp_filename,
            ];
        })->first();

        return $baseImage ?? [];
    }



}
