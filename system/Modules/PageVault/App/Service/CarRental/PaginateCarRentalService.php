<?php

namespace Modules\PageVault\App\Service\CarRental;

use Modules\PageVault\App\Models\CarRental;

class PaginateCarRentalService
{
    public function paginateCarRental($filters = [])
    {
        $query = CarRental::query()
            ->select('id', 'user_name', 'email', 'contact', 'status', 'type', 'pickup_time', 'pickup_address', 'destination_address', 'max_people', 'message')
            ->orderBy('id', 'desc');

        if (!empty($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        return $query->paginate(25);
    }
}
