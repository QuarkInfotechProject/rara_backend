<?php

namespace Modules\Blog\App\Service;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Modules\AdminUser\App\Models\AdminUser;
use Modules\Blog\App\Models\Blog;
use Modules\Blog\App\Models\BlogCategory;
use Modules\Blog\App\Models\BlogLog;
use Modules\Blog\App\Models\BlogRelatedProduct;
use Modules\Shared\App\Events\AdminUserActivityLogEvent;
use Modules\Shared\Constant\ActivityTypeConstant;

class UpdateBlogService
{
    public function updateBlog(array $data, string $ipAddress)
    {
        try {
            DB::beginTransaction();
            if ($data['type'] === 'blog') {
                $blog = $this->updateTypeBlog($data);

            } elseif ($data['type'] === 'mediaCoverage') {
                $blog = $this->updateTypeMediaCoverage($data);

            } elseif ($data['type'] === 'report') {
                $blog = $this->updateTypeReport($data);
            }

            if (isset($data['related_product'])) {
                $this->updateRelatedProducts($blog, $data['related_product']);
            }

            DB::commit();
        } catch (\Exception $exception) {

            throw $exception;
        }

        Event::dispatch(new AdminUserActivityLogEvent(
                "{$blog->title} blog has been updated by: " . Auth::user()->name,
                Auth::id(),
                ActivityTypeConstant::BLOG_ADDED,
                $ipAddress)
        );
    }

    private function updateRelatedProducts($blog, $relatedProductIds)
    {
        $existingRelatedProducts = $blog->relatedProducts()->pluck('product_id')->toArray();

        $productsToAdd = array_diff($relatedProductIds, $existingRelatedProducts);
        $productsToDelete = array_diff($existingRelatedProducts, $relatedProductIds);

        if (!empty($productsToDelete)) {
            BlogRelatedProduct::where('blog_id', $blog->id)
                ->whereIn('product_id', $productsToDelete)
                ->delete();
        }

        foreach ($productsToAdd as $productId) {
            BlogRelatedProduct::create([
                'blog_id' => $blog->id,
                'product_id' => $productId,
            ]);
        }
    }


    private function updateTypeBlog($data)
    {
        $validatedData = Validator::make($data, [
            'id' => 'required',
            'type' => 'required|in:blog,mediaCoverage,report',
            'title' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'description' => 'required|string',
            'publish_date' => 'required|date',
            'status' => 'required|in:draft,published,deleted',
            'read_time' => 'required|integer|min:1',
            'slug' => 'required|string|min:2|max:90|unique:blogs,slug,' . $data['id'],
            'blog_category_id' => 'required|exists:blog_categories,id',
            'admin_user_id' => 'required|exists:admin_users,id',
            'display_homepage' => 'required',
            'display_order' => 'required',
        ])->validate();

        $blog = Blog::findOrFail($validatedData['id']);


        if (!$blog) {
            throw new \Exception('Blog Not found');
        }

        $conflictingSlugLog = BlogLog::where('type', 'slug')
            ->where('data', $validatedData['slug'])
            ->where('blog_id', '!=', $blog->id)
            ->first();

        if ($conflictingSlugLog) {
            throw new \Exception('This slug has been used by another blog in the past. Please choose a different slug.');
        }

        $adminUser = AdminUser::findOrFail($data['admin_user_id']);
        $blogCategory = BlogCategory::findOrFail($data['blog_category_id']);


        $blog->update([
            'type' => $data['type'],
            'title' => $data['title'],
            'short_description' => $data['short_description'],
            'description' => $data['description'],
            'publish_date' => $data['publish_date'],
            'status' => $data['status'],
            'read_time' => $data['read_time'],
            'slug' => $data['slug'],
            'blog_category_id' => $blogCategory->id,
            'admin_user_id' => $adminUser->id,
            'display_homepage' => $data['display_homepage'],
            'display_order' => $data['display_order'],
        ]);

        if ($blog->wasChanged('slug')) {
            BlogLog::create([
                'type' => 'slug',
                'data' => $blog->slug,
                'blog_id' => $blog->id
            ]);
        }
        return $blog;
    }

    private function updateTypeMediaCoverage($data)
    {
        $validatedData = Validator::make($data, [
            'id' => 'required',
            'type' => 'required|in:blog,mediaCoverage,report',
            'title' => 'required|string|max:255',
            'media_name' => 'required_if:type,mediaCoverage|string|max:255',
            'short_description' => 'required|string|max:500',
            'publish_date' => 'required|date',
            'status' => 'required|in:draft,published,deleted',
            'slug' => 'required|unique:blogs,slug,' . $data['id'],
            'display_homepage' => 'required',
            'display_order' => 'required',
        ])->validate();

        $blog = Blog::find($data['id']);

        if (!$blog) {
            throw new \Exception('Blog Not found');
        }

        $conflictingSlugLog = BlogLog::where('type', 'slug')
            ->where('data', $validatedData['slug'])
            ->where('blog_id', '!=', $blog->id)
            ->first();

        if ($conflictingSlugLog) {
            throw new \Exception('This slug has been used by another blog in the past. Please choose a different slug.');
        }

        $blog->update([
            'type' => $data['type'],
            'title' => $data['title'],
            'mediaName' => $data['media_name'],
            'short_description' => $data['short_description'],
            'publish_date' => $data['publish_date'],
            'status' => $data['status'],
            'slug' => $data['slug'],
            'display_homepage' => $data['display_homepage'],
            'display_order' => $data['display_order'],
        ]);

        return $blog;
    }


    private function updateTypeReport($data)
    {
        $data = Validator::make($data, [
            'id' => 'required',
            'type' => 'required|in:blog,mediaCoverage,report',
            'title' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'description' => 'required|string',
            'publish_date' => 'required|date',
            'status' => 'required|in:draft,published,deleted',
            'slug' => 'required|alpha_dash|unique:blogs,slug,' . $data['id'],
            'display_homepage' => 'required',
            'display_order' => 'required',

        ])->validate();

        $blog = Blog::find($data['id']);

        if (!$blog) {
            throw new \Exception('Blog Not found');
        }

        $blog->update([
            'type' => $data['type'],
            'title' => $data['title'],
            'short_description' => $data['short_description'],
            'description' => $data['description'],
            'publish_date' => $data['publish_date'],
            'status' => $data['status'],
            'slug' => $data['slug'],
            'display_homepage' => $data['display_homepage'],
            'display_order' => $data['display_order'],
        ]);

        return $blog;
    }


}
