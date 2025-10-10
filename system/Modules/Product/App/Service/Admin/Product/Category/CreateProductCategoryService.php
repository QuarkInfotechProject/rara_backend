<?php

namespace Modules\Product\App\Service\Admin\Product\Category;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Modules\Product\App\Models\ProductCategory;
use Modules\Shared\App\Events\AdminUserActivityLogEvent;
use Modules\Shared\Constant\ActivityTypeConstant;

class CreateProductCategoryService
{
    /**
     * Create a new product category with meta fields.
     *
     * @param array $data
     * @param string $ipAddress
     * @return \Modules\Product\App\Models\ProductCategory
     * @throws \Throwable
     */
    public function createProductCategory(array $data, string $ipAddress)
    {
        $validated = Validator::make($data, [
            'name' => 'required|string|min:2|max:100',
            'slug' => 'required|string|min:2|max:100|unique:product_categories,slug',
            'description' => 'nullable|string|min:5|max:1000',
            'status' => 'required|string|in:active,inactive',
            'meta.metaTitle' => 'nullable|string|max:255',
            'meta.keywords' => 'nullable|array',
            'meta.metaDescription' => 'nullable|string|max:255',
        ])->validate();

        try {
            DB::beginTransaction();

            request()->merge([
                'meta' => $validated['meta'] ?? []
            ]);

            $productCategory = ProductCategory::create([
                'name' => $validated['name'],
                'slug' => $validated['slug'],
                'description' => $validated['description'] ?? null,
                'status' => $validated['status'],
            ]);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        Event::dispatch(new AdminUserActivityLogEvent(
            "{$productCategory->category_name} product category has been added by: " . Auth::user()->name,
            Auth::id(),
            ActivityTypeConstant::PRODUCT_CATEGORY_ADDED,
            $ipAddress
        ));

        return $productCategory;
    }
}
