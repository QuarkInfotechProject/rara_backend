<?php

namespace Modules\Product\App\Service\Admin\Manager;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Modules\Product\App\Models\Manager;
use Modules\Shared\App\Events\AdminUserActivityLogEvent;
use Modules\Shared\Constant\ActivityTypeConstant;

class DeleteManagerService
{
    public function deleteManager($id, $ipAddress)
    {
        try {
            DB::beginTransaction();
            $manager = Manager::find($id);

            $managerName = $manager->name;

            if (!$manager) {
                throw new \Exception('Manager not found');
            }

//            if ($manager->products()->exists()) {
//                throw new \Exception('Cannot delete the category because it is associated with one or more blogs.');
//            }

            $manager->delete();

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollback();
            throw $exception;
        }

        Event::dispatch(new AdminUserActivityLogEvent(
                "{$managerName} manager has been deleted by: " . Auth::user()->name,
                Auth::id(),
                ActivityTypeConstant::MANAGER_DELETED,
                $ipAddress)
        );
    }

}
