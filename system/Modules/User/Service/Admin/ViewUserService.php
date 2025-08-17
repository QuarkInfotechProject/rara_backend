<?php

namespace Modules\User\Service\Admin;

use Modules\User\App\Models\User;

class ViewUserService
{

    public function getUserDetailById(int $id): array
    {
        $user = User::select('full_name', 'country', 'email', 'phone_no', 'status')
            ->findOrFail($id);

        return $user->toArray();
    }

}
