<?php

namespace Modules\Media\Service\Popup;

use Modules\Media\App\Models\Popup;

class IndexPopupService
{
    public function getAllPopups()
    {
        $popups = Popup::with('files')->get();

        return $popups->map(function ($popup) {
            $data['files'] = [
                'popupImage' => $this->getMediaFiles($popup, 'popupImage'),
            ];

            return [
                'id' => $popup->id,
                'name' => $popup->name,
                'url' => $popup->slug,
                'status' => $popup->status,
                'popupImage' => $data['files']['popupImage'],
                'publishedDate' => $popup->created_at ? $popup->created_at->format('Y-m-d') : null,
                'updated_at' => $popup->updated_at ? $popup->updated_at->format('Y-m-d') : null,

            ];
        });
    }

    private function getMediaFiles($product, $type, $multiple = false)
    {
        $baseImageFiles = $product->filterFiles($type)->get();
        if ($multiple) {
            return $baseImageFiles->map(function ($file) {
                return [
                    $file->id
                ];
            })->toArray();
        } else {
            $baseImage = $baseImageFiles->map(function ($file) {
                return [
                    $file->id
                ];
            })->first();
            return $baseImage ?? '';
        }
    }
}
