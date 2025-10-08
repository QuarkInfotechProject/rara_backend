<?php

namespace Modules\Product\App\Service\Admin\Product\Category;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Modules\Product\App\Models\Product;
use Modules\Product\App\Models\ProductCategory;
use Modules\Shared\App\Events\AdminUserActivityLogEvent;
use Modules\Shared\Constant\ActivityTypeConstant;

class UpdateProductCategoryService
{
    public function updateCategory(array $data)
    {
        $validator = $this->validate($data);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $category = ProductCategory::findOrFail($data['id']);

        $keywords = null;

        if (!empty($data['keywords'])) {
            if (is_array($data['keywords'])) {
                $keywords = $data['keywords'];
            } else {
                $keywords = array_filter(array_map('trim', explode(',', $data['keywords'])));
            }
        }
        $category->update([
            'category_name'    => $data['category_name'],
            'slug'             => $data['slug'],
            'description'      => $data['description'] ?? null,
            'status'           => $data['status'],
            'meta_title'       => $data['meta_title'] ?? null,
            'meta_description' => $data['meta_description'] ?? null,
            'keywords'         => $keywords,
        ]);
        return $category;
    }

    private function validate(array $data)
    {
        return Validator::make($data, [
            'id'            => 'required|exists:products,id',
            'category_name' => 'required|string|max:255',
            'slug' => [
                'required',
                'string',
                'max:255',
                Rule::unique('product_categories', 'slug')->ignore($data['id']),
            ],
            'description'      => 'nullable|string',
            'status'           => 'required|string|in:active,inactive',
            'meta_title'       => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:255',
            'keywords'         => 'nullable|string|max:1000',
        ]);
    }
}
