<?php

namespace Modules\User\Service;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\DB;
use Modules\User\DTO\UserChangePasswordDTO;

class UserChangePasswordService
{
    function changePassword(UserChangePasswordDTO $userChangePasswordDTO, Authenticatable $user)
    {
        try {
            DB::beginTransaction();

            $user->update([
                'password' => bcrypt($userChangePasswordDTO->confirmPassword)
            ]);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollback();
            throw $exception;
        }
    }
}
