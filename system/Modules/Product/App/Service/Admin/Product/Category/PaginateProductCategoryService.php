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
                'name',
                'slug',
                'description',
                'status',
            ])
            ->with('meta')
            ->orderBy('id', 'desc');

        $this->applyFilters($query, $filters);

        $paginator = $query->paginate($perPage);

        $paginator->getCollection()->transform(function ($category) {
            $entityMetadata = $category->meta()->first();

            $category->meta = [
                'metaTitle' => $entityMetadata->meta_title ?? '',
                'keywords' => !empty($entityMetadata->meta_keywords)
                    ? json_decode($entityMetadata->meta_keywords, true)
                    : [],
                'metaDescription' => $entityMetadata->meta_description ?? '',
            ];
            return $category;
        });

        return $paginator;
    }

    private function applyFilters($query, array $filters): void
    {
        if (!empty($filters['category_name'])) {
            $query->where('name', 'like', '%' . $filters['category_name'] . '%');
        }
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
    }
}
