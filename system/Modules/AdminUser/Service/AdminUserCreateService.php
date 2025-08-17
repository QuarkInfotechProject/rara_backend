<?php

namespace Modules\AdminUser\Service;


use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Modules\AdminUser\App\Models\AdminUser;
use Modules\AdminUser\DTO\AdminUserCreateDTO;
use Modules\Shared\App\Events\AdminUserActivityLogEvent;
use Modules\Shared\Constant\ActivityTypeConstant;
use Spatie\Permission\Models\Role;

class AdminUserCreateService
{
    function create(AdminUserCreateDTO $userCreateDTO, string $ipAddress)
    {
        try {
            DB::beginTransaction();

            $adminUser = AdminUser::create([
                'uuid' => Str::uuid(),
                'name' => $userCreateDTO->name,
                'email' => $userCreateDTO->email,
                'password' => Hash::make($userCreateDTO->password),
            ]);

            $role = Role::findById($userCreateDTO->groupId);
            $adminUser->assignRole($role);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollback();
            throw $exception;
        }

        Event::dispatch(new AdminUserActivityLogEvent(
                "{$userCreateDTO->name} admin user has been created by: " . Auth::user()->name,
                Auth::id(),
                ActivityTypeConstant::ADMIN_USER_CREATED,
                $ipAddress)
        );
    }
}
