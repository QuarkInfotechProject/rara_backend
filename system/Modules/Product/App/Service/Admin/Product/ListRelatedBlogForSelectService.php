<?php

namespace Modules\Product\App\Service\Admin\Product;

use Modules\Blog\App\Models\Blog;

class ListRelatedBlogForSelectService
{

    public function getPublishedBlogsForSelect()
    {
        return Blog::where('status', 'published')
            ->where('type', 'blog')
            ->select('id', 'title', 'created_at')
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($blog) {
                return [
                    'id' => $blog->id,
                    'name' => $blog->title
                ];
            });
    }

}
