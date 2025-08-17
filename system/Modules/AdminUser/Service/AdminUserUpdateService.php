<?php

namespace Modules\AdminUser\Service;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Modules\AdminUser\App\Models\AdminUser;
use Modules\AdminUser\DTO\AdminUserUpdateDTO;
use Modules\Shared\App\Events\AdminUserActivityLogEvent;
use Modules\Shared\Constant\ActivityTypeConstant;
use Modules\Shared\Exception\Exception;
use Modules\Shared\StatusCode\ErrorCode;
use Spatie\Permission\Models\Role;

class AdminUserUpdateService
{
    function update(AdminUserUpdateDTO $userUpdateDTO, string $ipAddress)
    {
        try {
            DB::beginTransaction();
            $adminUser = AdminUser::where('uuid', $userUpdateDTO->uuid)->first();

            if (!$adminUser) {
                throw new Exception('Admin user not found.', ErrorCode::NOT_FOUND);
            }

            $updateData = [
                'name' => $userUpdateDTO->name,
            ];

            if ($userUpdateDTO->password) {
                $updateData['password'] = Hash::make($userUpdateDTO->password);
            }

            $adminUser->update($updateData);

            if ($userUpdateDTO->groupId) {
                $role = Role::findById($userUpdateDTO->groupId);

                $adminUser->roles()->sync([$role->id]);
            }

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollback();
            throw $exception;
        }

        Event::dispatch(new AdminUserActivityLogEvent(
                "{$adminUser->name} admin user has been updated by: " . Auth::user()->name,
                Auth::id(),
                ActivityTypeConstant::ADMIN_USER_UPDATED,
                $ipAddress)
        );
    }
}
