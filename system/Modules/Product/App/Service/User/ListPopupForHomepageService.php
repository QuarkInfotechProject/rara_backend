<?php

namespace Modules\Product\App\Service\User;

use Modules\Media\App\Models\Popup;

class ListPopupForHomepageService
{
    public function getPopups()
    {
        $popups = Popup::where('status', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return $popups->map(function ($popup) {
            return [
                'id' => $popup->id,
                'name' => $popup->name,
                'slug' => '/popup/' . $popup->slug,
                'status' => $popup->status,
                'popupImage' => $this->getMediaFiles($popup, 'popupImage'),
                'publishedDate' => $popup->created_at ? $popup->created_at->format('Y-m-d') : null,
                'updated_at' => $popup->updated_at ? $popup->updated_at->format('Y-m-d') : null,
            ];
        });
    }
    private function getMediaFiles($post, $type, $multiple = false)
    {
        $baseImageFiles = $post->filterFiles($type)->get();

        if ($multiple) {
            return $baseImageFiles->map(function ($file) {
                return [
                    'id' => $file->id,
                    'url' => $file->path . '/' . $file->temp_filename,
                ];
            })->toArray();
        } else {
            $file = $baseImageFiles->first();
            return $file ? [
                'id' => $file->id,
                'url' => $file->path . '/' . $file->temp_filename,
            ] : null;
        }
    }
}
