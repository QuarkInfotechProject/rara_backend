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

class CreateBlogService
{
    public function createBlog($data, $ipAddress)
    {
        try {
            DB::beginTransaction();
            if ($data['type'] === 'blog') {
                $blog = $this->createTypeBlog($data);

            } elseif ($data['type'] === 'mediaCoverage') {
                $blog = $this->createTypeMediaCoverage($data);

            } elseif ($data['type'] === 'report') {
                $blog = $this->createTypeReport($data);
            }

            if (!empty($data['related_product'])) {
                $this->attachRelatedProducts($blog->id, $data['related_product']);
            }

            DB::commit();
        } catch (\Exception $exception) {
            throw $exception;
        }

        Event::dispatch(new AdminUserActivityLogEvent(
                "{$blog->title} blog has been added by: " . Auth::user()->name,
                Auth::id(),
                ActivityTypeConstant::BLOG_ADDED,
                $ipAddress)
        );
    }

    private function attachRelatedProducts($blogId, array $relatedProducts)
    {
        foreach ($relatedProducts as $productId) {
            BlogRelatedProduct::create([
                'blog_id' => $blogId,
                'product_id' => $productId,
            ]);
        }
    }


    private function createTypeBlog($data)
    {
        $data = Validator::make($data, [
            'type' => 'required|in:blog,mediaCoverage,report',
            'title' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'description' => 'required|string',
            'publish_date' => 'required|date',
            'status' => 'required|in:draft,published,deleted',
            'read_time' => 'required|integer|min:1',
            'slug' => 'required|alpha_dash|unique:blogs,slug|unique:blog_logs,data',
            'blog_category_id' => 'required|exists:blog_categories,id',
            'admin_user_id' => 'required|exists:admin_users,id',
            'display_homepage' => 'required',
            'display_order' => 'required',
        ])->validate();

        $adminUser = AdminUser::findOrFail($data['admin_user_id']);
        $blogCategory = BlogCategory::findOrFail($data['blog_category_id']);

        $blog = [
            'type' => $data['type'],
            'title' => $data['title'],
            'short_description' => $data['short_description'],
            'description' => $data['description'],
            'publish_date' => $data['publish_date'],
            'status' => $data['status'],
            'read_time' => $data['read_time'],
            'slug' => $data['slug'],
            'blog_category_id' => $blogCategory->id,
            'admin_user_id' =>  $adminUser->id,
            'views_count' => 0,
            'display_homepage' => $data['display_homepage'],
            'display_order' => $data['display_order'],
        ];

        $blog = Blog::create($blog);

        BlogLog::create(
            [
            'type' => 'slug',
            'data' => $blog->slug,
            'blog_id' => $blog->id
        ]);

        BlogLog::create([
            'type' => 'description',
            'data' => $blog->description,
            'blog_id' => $blog->id
        ]);

        return $blog;
    }


    private function createTypeMediaCoverage($data)
    {
        $data = Validator::make($data, [
            'type' => 'required|in:blog,mediaCoverage,report',
            'title' => 'required|string|max:255',
            'media_name' => 'required|max:255',
            'short_description' => 'required|string|max:500',
            'publish_date' => 'required|date',
            'status' => 'required|in:draft,published,deleted',
            'slug' => 'required|unique:blogs,slug|unique:blog_logs,data',
            'display_homepage' => 'required',
            'display_order' => 'required',

        ])->validate();

        $mediaCoverage = [
            'type' => $data['type'],
            'title' => $data['title'],
            'mediaName' => $data['media_name'],
            'short_description' => $data['short_description'],
            'publish_date' => $data['publish_date'],
            'status' => $data['status'],
            'slug' => $data['slug'],
            'views_count' => 0,
            'display_homepage' => $data['display_homepage'],
            'display_order' => $data['display_order'],
        ];

        $blog = Blog::create($mediaCoverage);

        BlogLog::create([
            'type' => 'slug',
            'data' => $blog->slug,
            'blog_id' => $blog->id
        ]);

        return $blog;

    }

    private function createTypeReport($data)
    {
        $data = Validator::make($data, [
            'type' => 'required|in:blog,mediaCoverage,report',
            'title' => 'required|string|max:255',
            'short_description' => 'required|string|max:500',
            'description' => 'required|string',
            'publish_date' => 'required|date',
            'status' => 'required|in:draft,published,deleted',
            'slug' => 'required|alpha_dash|unique:blogs,slug|unique:blog_logs,data',
            'display_homepage' => 'required',
            'display_order' => 'required',
        ])->validate();

        $report = [
            'type' => $data['type'],
            'title' => $data['title'],
            'short_description' => $data['short_description'],
            'description' => $data['description'],
            'publish_date' => $data['publish_date'],
            'status' => $data['status'],
            'slug' => $data['slug'],
            'views_count' => 0,
            'display_homepage' => $data['display_homepage'],
            'display_order' => $data['display_order'],
        ];

        $blog = Blog::create($report);

        BlogLog::create([
            'type' => 'slug',
            'data' => $blog->slug,
            'blog_id' => $blog->id
        ]);

        return $blog;
    }

}
