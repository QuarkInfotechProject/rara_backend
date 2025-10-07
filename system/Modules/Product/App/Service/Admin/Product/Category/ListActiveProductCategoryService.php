<?php

namespace Modules\Product\App\Service\Admin\Product\Category;

use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Product\App\Models\ProductCategory;

class ListActiveProductCategoryService
{
    public function activeList(): array
    {
        return ProductCategory::query()
            ->select(['id', 'category_name'])
            ->where('status', 'active')
            ->orderBy('id', 'asc')
            ->get()
            ->toArray();
    }
}
