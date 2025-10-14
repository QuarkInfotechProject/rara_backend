<?php

namespace Modules\Media\Service\Popup;

use Modules\Media\App\Models\Popup;
use Modules\Shared\Exception\Exception;
use Modules\Shared\StatusCode\ErrorCode;

class ShowPopupService
{
    public function show(string $slug)
    {
        $popup = Popup::with('files')
        ->where('slug', $slug)
            ->first();

        if (!$popup) {
            throw new Exception('Popup not found.', ErrorCode::NOT_FOUND);
        }

        return [
            'id' => $popup->id,
            'name' => $popup->name,
            'url' => $popup->slug,

            'popupImage' => $this->getMediaFiles($popup, 'popupImage'),
        ];
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
