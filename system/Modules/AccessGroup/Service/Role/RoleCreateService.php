<?php

namespace Modules\AccessGroup\Service\Role;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Modules\Shared\App\Events\AdminUserActivityLogEvent;
use Modules\Shared\Constant\ActivityTypeConstant;
use Spatie\Permission\Models\Role;

class RoleCreateService
{
    function create($request, string $ipAddress)
    {
        $request->validate([
            'groupName' => ['required', 'min:2']
        ]);

        Role::create([
            'name' => $request->groupName,
        ]);

        $this->logRoleCreation($request, $ipAddress);
    }

    private function logRoleCreation($request, $ipAddress)
    {
        Event::dispatch(new AdminUserActivityLogEvent(
                'User group created of name: ' . $request->groupName,
                Auth::id(),
                ActivityTypeConstant::USER_GROUP_CREATED,
                $ipAddress)
        );
    }
}
