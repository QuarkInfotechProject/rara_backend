<?php

namespace Modules\Blog\App\Service\User;

use Modules\AdminUser\App\Models\AdminUser;
use Modules\Blog\App\Models\Blog;
use Modules\Blog\App\Models\BlogCategory;

class GetBlogDetailService
{
    public function getBlogDetailBySlug(string $slug)
    {
        try {
            $blog = Blog::where('slug', $slug)
                ->where('status', 'published')
                ->with(['author:id,name', 'relatedProducts.product:id,name,slug,type,tagline,location,total_rating,average_rating', 'blogLogs:id,data'])
                ->first();

            if (!$blog) {
                $blog = Blog::whereHas('blogLogs', function ($query) use ($slug) {
                    $query->where('type', 'slug')
                        ->where('data', $slug);
                })
                    ->where('status', 'published')
                    ->with(['author:id,name', 'relatedProducts.product:id,name,tagline,location,total_rating,average_rating', 'blogLogs:id,data'])
                    ->firstOrFail();
            }

            $currentSlug = $blog->slug;
            $slugHistory = $blog->blogLogs()
                ->where('type', 'slug')
                ->pluck('data')
                ->toArray();

            $relatedProducts = $this->transformProducts($blog->relatedProducts->pluck('product'));

            $category = null;
            if ($blog->blog_category_id) {
                $category = BlogCategory::find($blog->blog_category_id);
            }

            $author = null;
            if ($blog->admin_user_id) {
                $author = AdminUser::find($blog->admin_user_id);
            }
            $entityMetadata = $blog->meta()->first();


            return [
                'id' => $blog->id,
                'title' => $blog->title,
                'short_description' => $blog->short_description,
                'description' => $blog->description,
                'mediaName' => $blog->mediaName,
                'type' => $blog->type,
                'publish_date' => $blog->publish_date,
                'read_time' => $blog->read_time,
                'slug' => $currentSlug,
                'slug_history' => $slugHistory,
                'category' => $category ? [
                    'id' => $category->id,
                    'name' => $category->name,
                ] : null,
                'author' => $author ? [
                    'id' => $author->id,
                    'name' => $author->name,
                ] : null,
                'featured_image' => $this->getMediaFiles($blog, 'featuredImage'),
                'related_products' => $relatedProducts,
                'meta' => [
                    'metaTitle' => $entityMetadata->meta_title ?? null,
                    'keywords' => isset($entityMetadata->meta_keywords) ? json_decode($entityMetadata->meta_keywords) : null,
                    'metaDescription' => $entityMetadata->meta_description ?? null,
                ],
            ];

        } catch (\Exception $e) {
            throw $e;
        }
    }

    private function transformProducts($products)
    {
        return $products->map(function ($product) {
            $product->featuredImage = $this->getMediaFiles($product, 'featuredImage');
            $product->featuredImages = $this->getMediaFiles($product, 'featuredImages', true);
            $product->tags = $product->tags->map(function ($tag) {
                return [
                    'id' => $tag->id,
                    'name' => $tag->name,
                    'slug' => $tag->slug,
                ];
            })->values()->toArray();
            $product->prices = $product->prices->map(function ($price) {
                return [
                    'number_of_people' => $price->number_of_people,
                    'original_price_usd' => $price->original_price_usd,
                    'discounted_price_usd' => $price->discounted_price_usd,
                ];
            })->values()->toArray();
            return $product;
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
