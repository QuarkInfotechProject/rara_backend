<?php

namespace Modules\Product\App\Service\Admin\Product\Category;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Product\App\Models\ProductCategory;

class PaginateProductCategoryService
{
    public function paginate(array $filters = [], int $perPage = 25): LengthAwarePaginator
    {
        $query = ProductCategory::query()
            ->select([
                'id',
                'category_name',
                'slug',
                'description',
                'status',
                'meta_title',
                'meta_description',
                'keywords'
            ])
            ->orderBy('id', 'desc');

        $this->applyFilters($query, $filters);

        return $query->paginate($perPage);
    }

    private function applyFilters($query, array $filters): void
    {
        if (!empty($filters['category_name'])) {
            $query->where('category_name', 'like', '%' . $filters['category_name'] . '%');
        }
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
    }
}
