<?php

namespace Modules\Blog\App\Service;

use Modules\Blog\App\Models\Blog;

class GetBlogDetailService
{
    public function getBlogDetail(int $id)
    {
        $post = Blog::find($id);

        if (!$post) {
            throw new \Exception('Blog Not found');
        }

        $entityMetadata = $post->meta()->first();


        $reportFiles = $this->getReportFiles($post);

        $relatedProducts = $this->getRelatedProducts($post);

        return [
            'title' => $post->title ?? '',
            'slug' => $post->slug ?? '',
            'type' => $post->type ?? '',
            'short_description' => $post->short_description ?? '',
            'description' => $post->description ?? '',
            'media_name' => $post->mediaName ?? '',
            'publish_date' => $post->publish_date ?? '',
            'status' => $post->status ?? '',
            'read_time' => $post->read_time ?? '',
            'views_count' => $post->views_count ?? '',
            'display_order' => $post->display_order ?? '',
            'display_homepage' => $post->display_homepage ?? '',
            'blog_category' => $post->blog_category_id ?? '',
            'admin_user' => $post->admin_user_id ?? '',
            'created_at' => $post->created_at ?? '',
            'updated_at' => $post->updated_at ?? '',
            'meta' => [
                'metaTitle' => $entityMetadata->meta_title ?? '',
                'keywords' => json_decode($entityMetadata->meta_keywords) ?? '',
                'metaDescription' => $entityMetadata->meta_description ?? '',
            ],
            'featured_image' => $this->getMediaFiles($post) ?? '',
            'media_image' => $this->getMediaFiles($post, 'mediaImage') ?? '',
            'report' => $reportFiles ?? '',
            'related_products' => $relatedProducts ?? [],
        ];

    }

    private function getMediaFiles($post, $type = 'featuredImage' )
    {
        $baseImageFiles = $post->filterFiles($type)->get();

        $baseImage = $baseImageFiles->map(function ($file) {
            return [
                'id' => $file->id ?? '',
                'baseImageUrl' => $file->path . '/' . $file->temp_filename ?? '',
            ];
        })->first();

        return $baseImage ?? '';
    }

    private function getReportFiles($post)
    {
        $baseImageFiles = $post->filterFiles('report')->get();

        $baseImage = $baseImageFiles->map(function ($file) {
            return [
                'id' => $file->id ?? '',
                'baseImageUrl' => $file->path . '/' . $file->temp_filename ?? '',
            ];
        })->first();

        return $baseImage ?? '';
    }

    private function getRelatedProducts($post)
    {
        return $post->relatedProducts()->pluck('product_id')->toArray();
    }
}
