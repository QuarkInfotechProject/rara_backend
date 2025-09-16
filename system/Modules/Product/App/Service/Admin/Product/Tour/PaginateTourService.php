<?php

namespace Modules\Product\App\Service\Admin\Product\Tour;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Product\App\Models\Product;

class PaginateTourService
{
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Product::query()
             ->where('type', 'tour')
            ->select([
                'id',
                'name',
                'short_code',
                'display_order',
                'status',
                'type',
                'display_homepage',
                'cornerstone'
            ])
            ->orderBy('id', 'desc');

        $this->applyFilters($query, $filters);

        return $query->paginate($perPage);
    }

    private function applyFilters($query, array $filters): void
    {
        if (!empty($filters['name'])) {
            $query->where('name', 'like', '%' . $filters['name'] . '%');
        }
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
    }
}
