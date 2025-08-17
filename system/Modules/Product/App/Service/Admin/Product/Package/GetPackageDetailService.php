<?php

namespace Modules\Product\App\Service\Admin\Product\Package;

use Modules\Product\App\Models\Product;

class GetPackageDetailService
{
    public function getPackageDetails($id)
    {
        try {
            $product = Product::with([
                'faqs',
                'itinerary',
                'included',
                'excluded',
                'whatToBring',
                'relatedBlogs',
                'highlights',
                'tags',
                'prices'
            ])->findOrFail($id);

            $data = $product->toArray();

            $data['faqs'] = $product->faqs->map(function ($faq) {
                return [
                    'id' => $faq->id,
                    'question' => $faq->question,
                    'answer' => $faq->answer,
                    'order' => $faq->order,
                ];
            })->toArray();

            $data['itinerary'] = $product->itinerary->map(function ($item) {
                return [
                    'id' => $item->id,
                    'time_window' => $item->time_window,
                    'activity' => $item->activity,
                    'order' => $item->order,
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

            $data['files'] = [
                'featuredImage' => $this->getMediaFiles($product, 'featuredImage'),
                'featuredImages' => $this->getMediaFiles($product, 'featuredImages', true),
                'galleryImages' => $this->getMediaFiles($product, 'galleryImages', true),
                'locationCover' => $this->getMediaFiles($product, 'locationCover'),
                'howToGet' => $this->getMediaFiles($product, 'howToGet'),
            ];

            $data['included'] = $product->included->pluck('id')->toArray();
            $data['excluded'] = $product->excluded->pluck('id')->toArray();
            $data['what_to_bring'] = $product->whatToBring->pluck('id')->toArray();
            $data['related_package'] = $product->relatedPackages->pluck('id')->toArray();
            $data['related_blogs'] = $product->relatedBlogs->pluck('id')->toArray();
            $data['tags'] = $product->tags->pluck('id')->toArray();

            $entityMetadata = $product->meta()->first();
            $data['meta'] = [
                'metaTitle' => $entityMetadata->meta_title ?? '',
                'keywords' => isset($entityMetadata->meta_keywords) ? json_decode($entityMetadata->meta_keywords) : null,
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
