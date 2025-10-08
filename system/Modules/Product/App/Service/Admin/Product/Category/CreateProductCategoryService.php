<?php

namespace Modules\Product\App\Service\Admin\Product\Category;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\Shared\App\Events\AdminUserActivityLogEvent;
use Modules\Shared\Constant\ActivityTypeConstant;
use Modules\Product\App\Models\ProductCategory;

class CreateProductCategoryService
{
    public function createProductCategory($data, $ipAddress){
        $validator = $this->validateCategoryData($data);
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
        try {
            DB::beginTransaction();
            $category = ProductCategory::create([
                'category_name' => $data['category_name'],
                'slug' => $data['slug'],
                'description' => $data['description'] ?? null,
                'status' => $data['status'],
                'meta_title' => $data['meta_title'] ?? null,
                'meta_description' => $data['meta_description'] ?? null,
                'keywords' => isset($data['keywords'])
                    ? json_encode(array_map('trim', explode(',', $data['keywords'])))
                    : null,
            ]);
            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack(); throw $exception;
        }
        Event::dispatch(new AdminUserActivityLogEvent( "{$category->category_name} category has been added by: " . Auth::user()->name, Auth::id(), ActivityTypeConstant::ACTIVITIES_ADDED, $ipAddress ));
        return $category;
    }
    private function validateCategoryData(array $data) {
        return Validator::make($data, [
            'category_name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:product_categories,slug',
            'description' => 'nullable|string',
            'status' => 'required|string|in:active,inactive',
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string',
            'keywords' => 'nullable|string|max:1000',
            ]);
    }
}
