<?php

namespace Modules\Blog\App\Service;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Modules\Blog\App\Models\Blog;
use Modules\Shared\App\Events\AdminUserActivityLogEvent;
use Modules\Shared\Constant\ActivityTypeConstant;

class TrashBlogService
{

    public function trashBlog(int $id, $ipAddress)
    {
        try {
            DB::beginTransaction();
            $blog = Blog::find($id);

            if (!$blog) {
                throw new \Exception('Blog not found');
            }

            if (!in_array($blog->status, ['draft', 'published'])) {
                throw new \Exception('Cannot perform this action.');
            }

            $blog->update([
                'status' => 'deleted'
            ]);

            DB::commit();
        } catch (\Exception $exception) {
            throw $exception;
        }

        Event::dispatch(new AdminUserActivityLogEvent(
                "{$blog->title} blog has trashed by: " . Auth::user()->name,
                Auth::id(),
                ActivityTypeConstant::BLOG_TRASHED,
                $ipAddress)
        );
    }

}
