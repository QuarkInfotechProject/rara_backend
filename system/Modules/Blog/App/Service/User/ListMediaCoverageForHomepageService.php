<?php

namespace Modules\Blog\App\Service\User;

use Modules\Blog\App\Models\Blog;

class ListMediaCoverageForHomepageService
{

    public function execute()
    {
        $mediaCoverageBlogs = Blog::select('id','title', 'short_description', 'slug', 'mediaName')
            ->where('display_homepage', true)
            ->where('type', 'mediaCoverage')
            ->where('status', 'published')
            ->orderBy('display_order')
            ->get();

        return $mediaCoverageBlogs->map(function ($blog) {
            return [
                'title' => $blog->title,
                'short_description' => $blog->short_description,
                'link' => $blog->slug,
                'media_name' => $blog->mediaName,
                'featured_image' => $this->getMediaFiles($blog, 'featuredImage'),
                'media_image' => $this->getMediaFiles($blog, 'mediaImage'),
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
