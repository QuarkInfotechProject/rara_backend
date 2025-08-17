<?php

namespace Modules\Blog\App\Service;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Modules\Blog\App\Models\Blog;
use Modules\Shared\App\Events\AdminUserActivityLogEvent;
use Modules\Shared\Constant\ActivityTypeConstant;

class RestoreBlogService
{
    public function restoreBlog(int $id, $ipAddress)
    {
        try {
            DB::beginTransaction();
            $blog = Blog::find($id);

            if (!$blog) {
                throw new \Exception('Blog not found');
            }

            if (!in_array($blog->status, ['deleted'])) {
                throw new \Exception('Cannot perform this action.');
            }

            $blog->update([
                'status' => 'draft'
            ]);

            DB::commit();
        } catch (\Exception $exception) {
            throw $exception;
        }

        Event::dispatch(new AdminUserActivityLogEvent(
                "{$blog->title} blog has restored by: " . Auth::user()->name,
                Auth::id(),
                ActivityTypeConstant::BLOG_RESTORE,
                $ipAddress)
        );
    }

}
