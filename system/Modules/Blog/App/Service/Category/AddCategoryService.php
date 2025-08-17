<?php

namespace Modules\Blog\App\Service\Category;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Modules\Blog\App\Models\BlogCategory;
use Modules\Shared\App\Events\AdminUserActivityLogEvent;
use Modules\Shared\Constant\ActivityTypeConstant;

class AddCategoryService
{

    public function addCategory(array $data, string $ipAddress)
    {
        $data = Validator::make($data, [
            'name' => 'required|string|min:2|max:20|unique:blog_categories',
            'slug' => 'required|string|min:2|max:15|unique:blog_categories',
            'description' => 'nullable|string|min:5|max:400',
        ])->validate();

        try {
            DB::beginTransaction();
            $blogCategory = BlogCategory::create([
                'name' => $data['name'],
                'slug' => $data['slug'],
                'description' => $data['description']
            ]);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollback();
            throw $exception;
        }

        Event::dispatch(new AdminUserActivityLogEvent(
                "{$blogCategory->name} blog category has been added by: " . Auth::user()->name,
                Auth::id(),
                ActivityTypeConstant::BLOG_CATEGORY_ADDED,
                $ipAddress)
        );
    }
}
