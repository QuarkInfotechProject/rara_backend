<?php

namespace Modules\Blog\App\Service\User;

use Carbon\Carbon;
use Modules\AdminUser\App\Models\AdminUser;
use Modules\Blog\App\Models\Blog;
use Modules\Blog\App\Models\BlogCategory;

class PaginateBlogsService
{
    public function getPaginatedBlogs(array $filters, int $perPage = 15)
    {
        $query = Blog::query()
            ->where('status', 'published')
            ->where('publish_date', '<=', Carbon::now()->toDateString())
            ->orderBy('publish_date', 'desc');
//            ->orderBy('display_order');
//            ->orderBy('id', 'desc');

        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);

            if ($filters['type'] === 'blog') {
                $query->where('display_homepage', 0);
            }
        }

        if (isset($filters['categoryId'])) {
            $query->where('blog_category_id', $filters['categoryId']);
        }

        if (!empty($filters['search'])) {
            $searchTerm = $filters['search'];
            $query->where(function($q) use ($searchTerm) {
                $q->where('title', 'like', '%' . $searchTerm . '%')
                    ->orWhere('short_description', 'like', '%' . $searchTerm . '%');
            });
        }

        $blogs = $query->paginate($perPage);

        $blogs->getCollection()->transform(function ($blog) {
            return [
                'id' => $blog->id,
                'title' => $blog->title,
                'short_description' => $blog->short_description,
                'slug' => $blog->slug,
                'publish_date' => $blog->publish_date,
                'author' => $this->getAuthorName($blog->admin_user_id),
                'category' => $this->getCategoryName($blog->blog_category_id),
                'featured_image' => $this->getMediaFiles($blog),
                'mediaName' => $blog->mediaName,
            ];
        });

        return $blogs;
    }

    private function getAuthorName(?int $adminUserId): ?string
    {
        if (!$adminUserId) return null;
        $author = AdminUser::find($adminUserId);
        return $author ? $author->name : null;
    }

    private function getCategoryName(?int $categoryId): ?string
    {
        if (!$categoryId) return null;
        $category = BlogCategory::find($categoryId);
        return $category ? $category->name : null;
    }

    private function getMediaFiles($blog, $type = "featuredImage")
    {
        $baseImageFiles = $blog->filterFiles($type)->get();
        $baseImage = $baseImageFiles->map(function ($file) {
            return $file->path . '/' . $file->temp_filename;
        })->first();

        return $baseImage ?? '';
    }

}
