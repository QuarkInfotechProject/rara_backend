<?php

namespace Modules\Product\App\Service\Admin\Product\Homestay;

use Modules\Product\App\Models\Product;

class FetchHomestayDetailService
{
    public function getHomestayDetails($id)
    {
        try {
            $product = Product::with([
                'faqs',
                'highlights',
                'included',
                'nearbyHomestays',
                'relatedBlogs',
                'tags',
                'prices'
            ])->findOrFail($id);

            $data = $product->toArray();

            $data['hosts'] = $product->homestayHosts->map(function ($host) {
                return [
                    'id' => $host->id,
                    'hostFiles' => [
                        'profileImage' => $host->files->first()->id ?? null
                    ],
                    'fullname' => $host->fullname,
                    'description' => $host->description,

                ];
            })->toArray();

            $data['faqs'] = $product->faqs->map(function ($faq) {
                return [
                    'id' => $faq->id,
                    'question' => $faq->question,
                    'answer' => $faq->answer,
                    'order' => $faq->order,
                ];
            })->toArray();

            $data['highlights'] = $product->highlights->map(function ($highlight) {
                return [
                    'id' => $highlight->id,
                    'title' => $highlight->title,
                    'description' => $highlight->description,
                    'order' => $highlight->order,
                    'highlightFiles' => [
                        'highlightImage' => $highlight->files->first()->id ?? null
                    ],
                ];
            })->toArray();

            $data['prices'] = $product->prices->map(function ($prices) {
                return [
                    'id' => $prices->id,
                    'number_of_people' => $prices->number_of_people,
                    'original_price_usd' => $prices->original_price_usd,
                    'discounted_price_usd' =>  $prices->discounted_price_usd
                ];
            })->toArray();

            $data['files'] = [
                'featuredImage' => $this->getMediaFiles($product, 'featuredImage'),
                'featuredImages' => $this->getMediaFiles($product, 'featuredImages', true),
                'galleryImages' => $this->getMediaFiles($product, 'galleryImages', true),
                'locationCover' => $this->getMediaFiles($product, 'locationCover'),
                'hostCover' => $this->getMediaFiles($product, 'hostCover'),
                'howToGet' => $this->getMediaFiles($product, 'howToGet'),
            ];

            $data['amenity'] = $product->amenities->pluck('id')->toArray();
            $data['included'] = $product->included->pluck('id')->toArray();
            $data['nearby_homestays'] = $product->nearbyHomestays->pluck('id')->toArray();
            $data['related_blogs'] = $product->relatedBlogs->pluck('id')->toArray();
            $data['tags'] = $product->tags->pluck('id')->toArray();

            $entityMetadata = $product->meta()->first();

            $data['meta'] = [
                'metaTitle' => $entityMetadata->meta_title ?? '',
                'keywords' => json_decode($entityMetadata->meta_keywords) ?? '',
                'metaDescription' => $entityMetadata->meta_description ?? '',
            ];
            return $data;

        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    private function getMediaFiles($post, $type, $multiple = false)
    {
        $baseImageFiles = $post->filterFiles($type)->get();

        if ($multiple) {
            return $baseImageFiles->map(function ($file) {
                return [
                    $file->id
                ];
            })->toArray();
        } else {
            $baseImage = $baseImageFiles->map(function ($file) {
                return [
                    $file->id
                ];
            })->first();

            return $baseImage ?? '';
        }
    }
}
