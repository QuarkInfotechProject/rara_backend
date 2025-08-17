<?php

namespace Modules\Product\App\Service\Admin\Product\Homestay;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Product\App\Models\Product;

class PaginateHomestayService
{

    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Product::query()
            ->where('type', 'homestay')
            ->select([
                'id',
                'name',
                'short_code',
                'display_order',
                'status',
                'display_homepage',
                'cornerstone'
            ]);

        $this->applyFilters($query, $filters);

        return $query->paginate($perPage);
    }

    private function applyFilters($query, array $filters): void
    {
        if (isset($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }

        if (isset($filters['status'])) {
            $query->where('status', $filters['status']);
        }
    }
}
