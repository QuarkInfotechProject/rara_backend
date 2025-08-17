<?php

namespace Modules\Blog\App\Service\User;

use Carbon\Carbon;
use Modules\Blog\App\Models\Blog;

class ListBlogsForHomepageService
{

    public function execute(int $limit = 8)
    {
        $now = Carbon::now();

        $mediaCoverageBlogs = Blog::select('id', 'title', 'slug', 'publish_date')
            ->where('type', 'blog')
            ->where('status', 'published')
            ->where('display_homepage', '1')
            ->where('publish_date', '<=', $now->toDateString())
            ->select('id', 'title', 'slug', 'publish_date')
            ->orderByDesc('display_order')
            ->limit($limit)
            ->get();

        return $mediaCoverageBlogs->map(function ($blog) {
            return [
                'title' => $blog->title,
                'slug' => $blog->slug,
                'publish_date' => $blog->publish_date,
                'featured_image' => $this->getMediaFiles($blog, 'featuredImage'),
            ];
        });
    }

    private function getMediaFiles($mediaCoverage, $type)
    {
        $baseImageFiles = $mediaCoverage->filterFiles($type)->get();
        $baseImage = $baseImageFiles->map(function ($file) {
            return $file->path . '/' . $file->temp_filename;
        })->first();

        return $baseImage ?? '';
    }


}
