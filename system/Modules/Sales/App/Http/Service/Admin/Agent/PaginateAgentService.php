<?php

namespace Modules\Sales\App\Http\Service\Admin\Agent;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Sales\App\Models\Agent;

class PaginateAgentService
{

    public function paginateAgents(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        try {
            $query = Agent::select([
                'id',
                'firstname',
                'lastname',
                'is_active',
                'email',
                'phone',
                'company',
                'address',
                'country'
            ]);

            if (isset($filters['status'])) {
                $query->where('is_active', $filters['status'] === 'active');
            }

            if (isset($filters['name'])) {
                $query->where(function ($q) use ($filters) {
                    $q->where('firstname', 'like', '%' . $filters['name'] . '%')
                        ->orWhere('lastname', 'like', '%' . $filters['name'] . '%');
                });
            }

            return $query->paginate($perPage);

        } catch (\Exception $e) {
            throw $e;
        }

    }
}
