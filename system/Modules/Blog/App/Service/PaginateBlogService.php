<?php

namespace Modules\Blog\App\Service;

use Modules\Blog\App\Models\Blog;

class PaginateBlogService
{
    public function paginateBlog(array $filters, $perPage = 15)
    {
        $query = Blog::query();

        $query->leftJoin('blog_categories', 'blogs.blog_category_id', '=', 'blog_categories.id')
            ->leftJoin('admin_users', 'blogs.admin_user_id', '=', 'admin_users.id');

        if (isset($filters['title'])) {
            $query->where('blogs.title', 'like', "%{$filters['title']}%");
        }

        if (isset($filters['category'])) {
            $query->where('blogs.blog_category_id', 'like', "%{$filters['category']}%");
        }

        if (isset($filters['status'])) {
            $query->where('blogs.status', 'like', "%{$filters['status']}%");
        } else {
            $query->whereIn('blogs.status', ['published', 'draft']);
        }

        if (isset($filters['author'])) {
            $query->where('blogs.admin_user_id', $filters['author']);
        }

        $query->orderBy('blogs.created_at', 'desc');
        $query->select('blogs.id', 'blogs.status', 'blogs.type', 'blogs.display_order', 'blogs.display_homepage', 'blogs.title', 'blogs.slug', 'blog_categories.name as category_name', 'admin_users.name as admin_user_name', 'blogs.created_at');

        return $query->paginate($perPage);
    }

}
