<?php

namespace Modules\Product\App\Service\User;

use Illuminate\Support\Facades\Auth;
use Modules\Product\App\Models\SavedProduct;

class GetSavedProductService
{
    public function getSavedProducts()
    {
        $user = Auth::user();
        return SavedProduct::where('user_id', $user->id)
            ->with('product:id,name,slug,type,tagline,location,average_rating,total_comment,total_rating,status')
            ->with('product.prices')
            ->get()
            ->map(function ($savedProduct) {
                $featuredImage = $this->getMediaFiles($savedProduct->product, 'featuredImage');
                $product = $savedProduct->product;

                $product->prices = $product->prices->map(function ($price) {
                    return [
                        'number_of_people' => $price->number_of_people,
                        'original_price_usd' => $price->original_price_usd,
                        'discounted_price_usd' => $price->discounted_price_usd,
                    ];
                })->values()->toArray();

                return [
                    'id' => $product->id,
                    'name' => $product->name,
                    'slug' => $product->slug,
                    'type' => $product->type,
                    'tagline' => $product->tagline,
                    'location' => $product->location,
                    'average_rating' => $product->average_rating,
                    'total_comment' => $product->total_comment,
                    'total_rating' => $product->total_rating,
                    'status' => $product->status,
                    'featuredImage' => $featuredImage,
                    'prices' => $product->prices,
                ];
            });
    }

    private function getMediaFiles($post, $type, $multiple = false)
    {
        $baseImageFiles = $post->filterFiles($type)->get();

        if ($multiple) {
            return $baseImageFiles->map(function ($file) {
                return
                    $file->path . '/' . $file->temp_filename ?? '';
            })->toArray();
        } else {
            $baseImage = $baseImageFiles->map(function ($file) {
                return
                    $file->path . '/' . $file->temp_filename ?? ''
                    ;
            })->first();

            return $baseImage ?? '';
        }
    }
}
