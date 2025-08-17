<?php

namespace Modules\Blog\App\Service\Category;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Modules\Blog\App\Models\BlogCategory;
use Modules\Shared\App\Events\AdminUserActivityLogEvent;
use Modules\Shared\Constant\ActivityTypeConstant;

class DeleteCategoryService
{

    public function deleteCategory($id, $ipAddress)
    {
        try {
            DB::beginTransaction();
            $blogCategory = BlogCategory::find($id);

            $blogName = $blogCategory->name;

            if (!$blogCategory) {
                throw new \Exception('Blog Category not found');
            }

            if ($blogCategory->blogs()->exists()) {
                throw new \Exception('Cannot delete the category because it is associated with one or more blogs.');
            }

            $entityMetadata = $blogCategory->meta()->first();

            if ($entityMetadata) {
                $entityMetadata->delete();
            }

            $blogCategory->delete();

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollback();
            throw $exception;
        }

        Event::dispatch(new AdminUserActivityLogEvent(
                "{$blogName} blog category has been deleted by: " . Auth::user()->name,
                Auth::id(),
                ActivityTypeConstant::BLOG_CATEGORY_DELETED,
                $ipAddress)
        );


    }

}
