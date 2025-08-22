<?php

namespace Modules\Product\App\Service\Admin\Tag;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Modules\Product\App\Models\Tag;
use Modules\Shared\App\Events\AdminUserActivityLogEvent;
use Modules\Shared\Constant\ActivityTypeConstant;

class CreateTagService
{
    public function addTag(array $data, string $ipAddress)
    {
        $data = Validator::make($data, [
            'name' => 'required|string|min:2|max:50|unique:tags',
            'slug' => 'required|string|min:2|max:50|unique:tags',
            'description' => 'nullable|string|min:5|max:400',
//            'type' => 'required|in:homestay,experience,circuit,package',
            'type' => 'required|in:trek,tour,activities,safari',
            'latitude' => 'required',
            'longitude' => 'required',
            'display_order' => 'required',
            'zoom_level' => 'required',
        ])->validate();

        try {
            DB::beginTransaction();

            $amenity = Tag::create([
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
                "{$amenity->name} tags has been added by: " . Auth::user()->name,
                Auth::id(),
                ActivityTypeConstant::AMENITY_ADDED,
                $ipAddress)
        );
    }

}
