<?php

namespace Modules\Product\App\Service\Admin\Amenities;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Modules\Product\App\Models\Amenity;
use Modules\Shared\App\Events\AdminUserActivityLogEvent;
use Modules\Shared\Constant\ActivityTypeConstant;

class DeleteAmenityService
{
    public function deleteAmenity($id, $ipAddress)
    {
        try {
            DB::beginTransaction();
            $amenity = Amenity::find($id);

            $amenityName = $amenity->name;

            if (!$amenity) {
                throw new \Exception('Amenity not found');
            }

//            if ($amenity->products()->exists()) {
//                throw new \Exception('Cannot delete the category because it is associated with one or more blogs.');
//            }

            $amenity->delete();

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollback();
            throw $exception;
        }

        Event::dispatch(new AdminUserActivityLogEvent(
                "{$amenityName} amenity has been deleted by: " . Auth::user()->name,
                Auth::id(),
                ActivityTypeConstant::AMENITY_DELETED,
                $ipAddress)
        );
    }

}
