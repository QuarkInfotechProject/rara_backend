<?php

namespace Modules\User\Service\Admin;

use Modules\User\App\Models\User;

class ToggleBlockUnblockService
{

    public function toggleUserStatus(int $id): array
    {
       $user = User::findOrFail($id);

        // Toggle the user's status (1 for active, 0 for blocked)
        $user->status = $user->status == 1 ? 0 : 1;
        $user->save();

        // Return the updated user status
        return [
            'user_id' => $user->id,
            'full_name' => $user->full_name,
            'status' => $user->status == 1 ? 'unblocked' : 'blocked',
        ];
    }

}
