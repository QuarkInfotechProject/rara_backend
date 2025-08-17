<?php

namespace Modules\Product\App\Service\Admin\Tag;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Modules\Product\App\Models\Tag;
use Modules\Shared\App\Events\AdminUserActivityLogEvent;
use Modules\Shared\Constant\ActivityTypeConstant;

class UpdateTagService
{

    public function updateTag(array $data, string $ipAddress)
    {
        $data = Validator::make($data, [
            'id' => 'required|integer|exists:tags,id',
            'name' => 'required|string|min:2|max:50|unique:tags,name,' . $data['id'],
            'slug' => 'required|string|min:2|max:50',
            'description' => 'nullable|string|min:5|max:400',
            'type' => 'required|in:homestay,experience,circuit,package',
            'latitude' => 'required',
            'longitude' => 'required',
            'display_order' => 'required',
            'zoom_level' => 'required',
        ])->validate();

        try {
            DB::beginTransaction();
            $tag = Tag::find($data['id']);

            if (!$tag) {
                throw new \Exception('Tag Not found');
            }

            $tag->update([
                'name' => $data['name'],
                'slug' => $data['slug'],
                'description' => $data['description'],
                'type' => $data['type'],
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
                'display_order' => $data['display_order'],
                'zoom_level' => $data['zoom_level'],
            ]);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollback();
            throw $exception;
        }

        Event::dispatch(new AdminUserActivityLogEvent(
                "{$tag->name} tag has been updated by: " . Auth::user()->name,
                Auth::id(),
                ActivityTypeConstant::TAG_UPDATED,
                $ipAddress)
        );
    }

}
