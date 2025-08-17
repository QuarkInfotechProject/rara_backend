<?php

namespace Modules\Blog\App\Service\User;

use Modules\Blog\App\Models\Blog;

class ListAllBlogSlugService
{

    public function getAllSlugs()
    {
        $blogSlugs = Blog::where('status', 'published')
            ->where('type', 'blog')
            ->select('slug', 'updated_at')
            ->get()
            ->map(function ($blog) {
                return [
                    'slug' => $blog->slug,
                    'updatedAt' => $blog->updated_at
                ];
            });

        return $blogSlugs;
    }
}
