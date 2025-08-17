<?php

namespace Modules\Blog\App\Service\Category;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Modules\Blog\App\Models\BlogCategory;
use Modules\Shared\App\Events\AdminUserActivityLogEvent;
use Modules\Shared\Constant\ActivityTypeConstant;

class UpdateCategoryService
{

    public function updateCategory(array $data, string $ipAddress)
    {

        $data = Validator::make($data, [
            'id' => 'required|integer|exists:blog_categories,id',
            'name' => 'required|string|min:2|max:20|unique:blog_categories,name,' . $data['id'],
            'slug' => 'required|string|min:2|max:15|unique:blog_categories,slug,' . $data['id'],
            'description' => 'nullable|string|min:5|max:400',
            'meta.metaTitle' => 'nullable|string|max:255',
            'meta.keywords' => 'nullable|array',
            'meta.metaDescription' => 'nullable|string|max:255',
        ])->validate();

        try {
            DB::beginTransaction();
            $blogCategory = BlogCategory::find($data['id']);

            if (!$blogCategory) {
                throw new \Exception('Blog Category Not found');
            }

            $blogCategory->update([
                'name' => $data['name'],
                'slug' => $data['slug'],
                'description' => $data['description']
            ]);

            $entityMetadata = $blogCategory->meta()->first();

            if ($entityMetadata) {
                $entityMetadata->update([
                    'meta_title' => $data['meta']['metaTitle'],
                    'meta_keywords' => $data['meta']['keywords'],
                    'meta_description' => $data['meta']['metaDescription'],
                ]);
            }

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollback();
            throw $exception;
        }

        Event::dispatch(new AdminUserActivityLogEvent(
                "{$blogCategory->name} category has been updated by: " . Auth::user()->name,
                Auth::id(),
                ActivityTypeConstant::BLOG_CATEGORY_UPDATED,
                $ipAddress)
        );

    }

}
