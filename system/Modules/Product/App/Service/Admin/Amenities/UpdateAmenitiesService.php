<?php

namespace Modules\Product\App\Service\Admin\Amenities;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Modules\Product\App\Models\Amenity;
use Modules\Shared\App\Events\AdminUserActivityLogEvent;
use Modules\Shared\Constant\ActivityTypeConstant;

class UpdateAmenitiesService
{

    public function updateAmenity(array $data, string $ipAddress)
    {
        $data = Validator::make($data, [
            'id' => 'required|integer|exists:amenities,id',
            'name' => 'required|string|min:2|max:50|unique:amenities,name,' . $data['id'],
            'icon' => 'required|string|min:2|max:30',
            'description' => 'nullable|string|min:5|max:400',
            'category' => 'required|in:amenity,included,whatToBring,excluded',
        ])->validate();

        try {
            DB::beginTransaction();
            $amenity = Amenity::find($data['id']);

            if (!$amenity) {
                throw new \Exception('Amenity Not found');
            }

            $amenity->update([
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
                "{$amenity->name} amenity has been updated by: " . Auth::user()->name,
                Auth::id(),
                ActivityTypeConstant::AMENITY_UPDATED,
                $ipAddress)
        );

    }
}
