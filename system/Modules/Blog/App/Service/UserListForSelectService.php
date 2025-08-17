<?php

namespace Modules\Blog\App\Service;

use Modules\AdminUser\App\Models\AdminUser;
use Modules\User\App\Models\User;

class UserListForSelectService
{

    public function getUserListForSelect()
    {
        $adminUsers = AdminUser::select('id', 'name')->get();

        $adminUserList = [];
        foreach ($adminUsers as $adminUser) {
            $adminUserList[] = [
                'id' => $adminUser->id,
                'name' => $adminUser->name,
            ];
        }

        return $adminUserList;
    }

}
