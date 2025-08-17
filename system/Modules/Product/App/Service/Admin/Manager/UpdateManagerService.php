<?php

namespace Modules\Product\App\Service\Admin\Manager;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Modules\Product\App\Models\Manager;
use Modules\Shared\App\Events\AdminUserActivityLogEvent;
use Modules\Shared\Constant\ActivityTypeConstant;

class UpdateManagerService
{

    public function updateManager(array $data, string $ipAddress)
    {
        $data = Validator::make($data, [
            'id' => 'required|integer|exists:managers,id',
            'firstname' => 'required|string|min:2|max:50',
            'lastname' => 'required|string|min:2|max:50',
            'description' => 'nullable|string|min:5|max:400',
            'email' => 'nullable|email',
            'phone_number' => 'nullable',
        ])->validate();

        try {
            DB::beginTransaction();
            $manager = Manager::find($data['id']);

            if (!$manager) {
                throw new \Exception('Manager Not found');
            }

            $manager->update([
                'firstname' => $data['firstname'],
                'lastname' => $data['lastname'],
                'description' => $data['description'],
                'email' => $data['email'],
                'phone_number' => $data['phone_number']
            ]);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollback();
            throw $exception;
        }

        Event::dispatch(new AdminUserActivityLogEvent(
                "{$manager->name} manager has been updated by: " . Auth::user()->name,
                Auth::id(),
                ActivityTypeConstant::MANAGER_UPDATED,
                $ipAddress)
        );

    }
}
