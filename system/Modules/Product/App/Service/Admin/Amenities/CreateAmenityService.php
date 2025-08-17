<?php

namespace Modules\Product\App\Service\Admin\Amenities;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Modules\Product\App\Models\Amenity;
use Modules\Shared\App\Events\AdminUserActivityLogEvent;
use Modules\Shared\Constant\ActivityTypeConstant;

class CreateAmenityService
{
    public function addAmenities(array $data, string $ipAddress)
    {
        $data = Validator::make($data, [
            'name' => 'required|string|min:2|max:50|unique:amenities',
            'icon' => 'required|string|min:2|max:30',
            'description' => 'nullable|string|min:5|max:400',
            'category' => 'required|in:amenity,included,whatToBring,excluded',
        ])->validate();

        try {
            DB::beginTransaction();
            $amenity = Amenity::create([
                'name' => $data['name'],
                'icon' => $data['icon'],
                'description' => $data['description'],
                'category' => $data['category']
            ]);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollback();
            throw $exception;
        }

        Event::dispatch(new AdminUserActivityLogEvent(
                "{$amenity->name} amenity has been added by: " . Auth::user()->name,
                Auth::id(),
                ActivityTypeConstant::AMENITY_ADDED,
                $ipAddress)
        );
    }

}
