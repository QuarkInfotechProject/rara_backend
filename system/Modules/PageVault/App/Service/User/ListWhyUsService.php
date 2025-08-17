<?php

namespace Modules\PageVault\App\Service\User;

use Modules\PageVault\App\Models\WhyUs;

class ListWhyUsService
{
    public function execute()
    {
        $whyUsItems = WhyUs::select('id', 'title', 'description', 'link', 'order')
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        return $whyUsItems->map(function ($item) {
            return [
                'title' => $item->title,
                'description' => $item->description,
                'link' => $item->link,
                'whyUsImage' => $this->getMediaFiles($item),
            ];
        });
    }

    private function getMediaFiles($whyUs)
    {
        $baseImageFiles = $whyUs->filterFiles('whyUsImage')->get();
        $baseImage = $baseImageFiles->map(function ($file) {
            return $file->path . '/' . $file->temp_filename;
        })->first();

        return $baseImage ?? '';
    }
}
