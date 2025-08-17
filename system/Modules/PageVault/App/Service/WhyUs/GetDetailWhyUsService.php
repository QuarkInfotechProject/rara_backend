<?php

namespace Modules\PageVault\App\Service\WhyUs;

use Modules\PageVault\App\Models\WhyUs;

class GetDetailWhyUsService
{
    public function getWhyUsDetail(int $id)
    {
        $whyUs = WhyUs::findOrFail($id);

        if (!$whyUs) {
            throw new \Exception('Detail Not found');
        }

        $mediaFiles = $this->getMediaFiles($whyUs);

        return [
            'title' => $whyUs->title,
            'description' => $whyUs->description,
            'link' => $whyUs->link,
            'order' => $whyUs->order,
            'is_active' => $whyUs->is_active,
            'whyUsImage' => $mediaFiles
        ];
    }

    private function getMediaFiles($blog)
    {
        $baseImageFiles = $blog->filterFiles('whyUsImage')->get();

        $baseImage = $baseImageFiles->map(function ($file) {
            return [
                'id' => $file->id,
                'baseImageUrl' => $file->path . '/' . $file->temp_filename,
            ];
        })->first();

        return $baseImage ?? '';
    }
}
