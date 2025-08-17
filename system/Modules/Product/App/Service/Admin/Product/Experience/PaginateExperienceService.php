<?php

namespace Modules\Product\App\Service\Admin\Product\Experience;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Product\App\Models\Product;

class PaginateExperienceService
{
    public function paginate(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Product::query()
            ->where('type', 'experience')
            ->select([
                'id',
                'name',
                'short_code',
                'display_order',
                'status',
                'display_homepage',
                'cornerstone'
            ])
            ->with(['tags' => function ($query) {
                $query->select('tags.id', 'tags.name');
            }]);

        $this->applyFilters($query, $filters);

        $paginator = $query->paginate($perPage);

        $paginator->getCollection()->transform(function ($product) {
            $product->tag_names = $product->tags->pluck('name');
            unset($product->tags);
            return $product;
        });

        return $paginator;
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
