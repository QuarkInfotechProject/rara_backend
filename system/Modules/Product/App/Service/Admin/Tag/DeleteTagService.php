<?php

namespace Modules\Product\App\Service\Admin\Tag;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Modules\Product\App\Models\Tag;
use Modules\Shared\App\Events\AdminUserActivityLogEvent;
use Modules\Shared\Constant\ActivityTypeConstant;

class DeleteTagService
{

    public function deleteTag($id, $ipAddress)
    {
        try {
            DB::beginTransaction();
            $tag = Tag::find($id);

            $tagName = $tag->name;

            if (!$tag) {
                throw new \Exception('Tag not found');
            }

            if ($tag->products()->exists()) {
                throw new \Exception('Cannot delete the category because it is associated with one or more blogs.');
            }

            $tag->delete();

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollback();
            throw $exception;
        }

        Event::dispatch(new AdminUserActivityLogEvent(
                "{$tagName} tag has been deleted by: " . Auth::user()->name,
                Auth::id(),
                ActivityTypeConstant::TAG_DELETED,
                $ipAddress)
        );
    }
}
