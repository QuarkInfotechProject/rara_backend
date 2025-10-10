<?php

namespace Modules\Product\App\Service\Admin\Product\Activities;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Product\App\Models\Product;

class PaginateActivitiesService
{
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Product::query()
            ->where('type', 'activities')
            ->with('category.categoryDetail')
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

        $paginator = $query->paginate($perPage);

        $paginator->getCollection()->transform(function ($product) {
            $product->category_name = $product->category?->categoryDetail?->name;
            unset($product->category);
            return $product;
        });

        return $paginator;
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
