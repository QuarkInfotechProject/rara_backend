<?php

namespace Modules\User\Service\Admin;

use Modules\User\App\Models\User;

class PaginateUserService
{

    public function getPaginatedUsers($filters)
    {
        $query = User::query();

        if ($filters) {

            if (isset($filters['full_name'])) {
                $query->where('full_name', 'like', '%' . $filters['full_name'] . '%');
            }

            if (isset($filters['email'])) {
                $query->where('email', 'like', '%' . $filters['email'] . '%');
            }

            if (isset($filters['status'])) {
                $query->where('status', $filters['status']);
            }

            if (isset($filters['phone_no'])) {
                $query->where('phone_no', 'like', '%' . $filters['phone_no'] . '%');
            }
        }

        $query->orderBy('created_at', 'desc');

        return $query->select('id', 'full_name', 'country', 'email', 'phone_no', 'status')
            ->paginate(20);
    }

}
