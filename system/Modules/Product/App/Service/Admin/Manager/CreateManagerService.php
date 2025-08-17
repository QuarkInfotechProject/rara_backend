<?php

namespace Modules\Product\App\Service\Admin\Manager;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Modules\Product\App\Models\Manager;
use Modules\Shared\App\Events\AdminUserActivityLogEvent;
use Modules\Shared\Constant\ActivityTypeConstant;

class CreateManagerService
{
    public function addManager(array $data, string $ipAddress)
    {
        $data = Validator::make($data, [
            'firstname' => 'required|string|min:2|max:50',
            'lastname' => 'required|string|min:2|max:50',
            'description' => 'nullable|string|min:5|max:400',
            'email' => 'nullable|string|min:5|max:400',
            'phone_number' => 'nullable|string|min:5|max:400',
        ])->validate();

        try {

            DB::beginTransaction();
            $amenity = Manager::create([
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'description' => $data['description'],
                'email' => $data['email'],
                'phone_number' => $data['phone_number'],
                'status' => 1,
            ]);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollback();
            throw $exception;
        }

        Event::dispatch(new AdminUserActivityLogEvent(
                "{$amenity->name} manager has been added by: " . Auth::user()->name,
                Auth::id(),
                ActivityTypeConstant::MANAGER_ADDED,
                $ipAddress)
        );
    }

}
