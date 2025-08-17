<?php

namespace Modules\Blog\App\Service\User;

use Modules\Blog\App\Models\Blog;
use Modules\Blog\App\Models\BlogCategory;

class ListCategoryForFilterService
{

    public function getCategoriesForSelect(): array
    {
        return BlogCategory::select('id', 'slug', 'name')->get()->toArray();
    }

}
