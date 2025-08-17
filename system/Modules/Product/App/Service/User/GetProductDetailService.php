<?php

namespace Modules\Product\App\Service\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Modules\Product\App\Models\Product;
use Modules\Product\App\Models\SavedProduct;

class GetProductDetailService
{
    public function getHomestayDetails($slug)
    {
        try {
            $user = Auth::guard('user')->user();

            $query = Product::with([
                'faqs',
                'highlights',
                'included',
                'nearbyHomestays',
                'relatedBlogs',
                'tags',
                'prices',
                'whatToBring',
                'itinerary',
                'overview',
                'dossiers'
            ])->where('slug', $slug);

            if ($user) {
                $userId = $user->id;
                $query->addSelect([
                    'wishlist' => SavedProduct::select(DB::raw('TRUE'))
                        ->whereColumn('product_id', 'products.id')
                        ->where('user_id', $userId)
                        ->limit(1)
                ]);
            }

            $product = $query->firstOrFail();

            $data = $product->toArray();

            $data['prices'] = $product->prices->map(function ($price) {
                return [
                    'number_of_people' => $price->number_of_people,
                    'original_price_usd' => $price->original_price_usd,
                    'discounted_price_usd' => $price->discounted_price_usd,
                ];
            })->sortBy('number_of_people')->values()->toArray();


            $data['what_to_bring'] = $product->whatToBring->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'icon' => $item->icon,
                    'description' => $item->description,
                ];
            })->toArray();

            $data['overview'] = $product->overview->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'description' => $item->description,
                ];
            })->toArray();

            $data['hosts'] = $product->homestayHosts->map(function ($host) {
                return [
                    'id' => $host->id,
                    'profileImage' => $host->files->first()->path . '/' . $host->files->first()->temp_filename ?: null,
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
                    'highlightImage' => $highlight->files->first()->path . '/' . $highlight->files->first()->temp_filename ?: null
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

            $data['itinerary'] = $product->itinerary->map(function ($item) {
                return [
                    'id' => $item->id,
                    'time_window' => $item->time_window,
                    'activity' => $item->activity,
                    'order' => $item->order,
                ];
            })->toArray();

            $data['amenities'] = $product->amenities->map(function ($amenity) {
                return [
                    'id' => $amenity->id,
                    'name' => $amenity->name,
                    'icon' => $amenity->icon,
                    'description' => $amenity->description,
                ];
            })->toArray();

            $data['included'] = $product->included->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'icon' => $item->icon,
                    'description' => $item->description,
                ];
            })->toArray();

            $data['nearby_homestays'] = $product->nearbyHomestays->map(function ($homestay) {
                return [
                    'id' => $homestay->id,
                    'name' => $homestay->name,
                    'featuredImage' => $this->getMediaFiles($homestay, 'featuredImage'),
                    'featuredImages' => $this->getMediaFiles($homestay, 'featuredImages', true),
                    'prices' => $homestay->prices->map(function ($price) {
                        return [
                            'number_of_people' => $price->number_of_people,
                            'original_price_usd' => $price->original_price_usd,
                            'discounted_price_usd' => $price->discounted_price_usd,
                        ];
                    })->sortBy('number_of_people')->values()->toArray(),
                    'location' => $homestay->location,
                    'slug' => $homestay->slug,
                    'type' => $homestay->type,
                    'total_rating' => $homestay->total_rating,
                    'average_rating' => $homestay->average_rating,
                    'tagline' => $homestay->tagline,
                    'tags' => $homestay->tags->map(function ($tag) {
                        return [
                            'id' => $tag->id,
                            'name' => $tag->name,
                            'slug' => $tag->slug,
                            'description' => $tag->description,
                        ];
                    })->toArray(),
                ];
            })->toArray();

            $data['related_homestays'] = $product->relatedHomestaysForExperience->map(function ($homestay) {
                return [
                    'id' => $homestay->id,
                    'name' => $homestay->name,
                    'featuredImage' => $this->getMediaFiles($homestay, 'featuredImage'),
                    'featuredImages' => $this->getMediaFiles($homestay, 'featuredImages', true),
                    'prices' => $homestay->prices->map(function ($price) {
                        return [
                            'number_of_people' => $price->number_of_people,
                            'original_price_usd' => $price->original_price_usd,
                            'discounted_price_usd' => $price->discounted_price_usd,
                        ];
                    })->sortBy('number_of_people')->values()->toArray(),
                    'location' => $homestay->location,
                    'slug' => $homestay->slug,
                    'type' => $homestay->type,
                    'total_rating' => $homestay->total_rating,
                    'average_rating' => $homestay->average_rating,
                    'tagline' => $homestay->tagline,
                    'tags' => $homestay->tags->map(function ($tag) {
                        return [
                            'id' => $tag->id,
                            'name' => $tag->name,
                            'slug' => $tag->slug,
                            'description' => $tag->description,
                        ];
                    })->toArray(),
                ];
            })->toArray();

            $data['related_experiences'] = $product->relatedExperience->map(function ($experiences) {
                return [
                    'id' => $experiences->id,
                    'name' => $experiences->name,
                    'featuredImage' => $this->getMediaFiles($experiences, 'featuredImage'),
                    'featuredImages' => $this->getMediaFiles($experiences, 'featuredImages', true),
                    'prices' => $experiences->prices->map(function ($price) {
                        return [
                            'number_of_people' => $price->number_of_people,
                            'original_price_usd' => $price->original_price_usd,
                            'discounted_price_usd' => $price->discounted_price_usd,
                        ];
                    })->sortBy('number_of_people')->values()->toArray(),
                    'location' => $experiences->location,
                    'slug' => $experiences->slug,
                    'type' => $experiences->type,
                    'total_rating' => $experiences->total_rating,
                    'average_rating' => $experiences->average_rating,
                    'tagline' => $experiences->tagline,
                    'tags' => $experiences->tags->map(function ($tag) {
                        return [
                            'id' => $tag->id,
                            'name' => $tag->name,
                            'slug' => $tag->slug,
                            'description' => $tag->description,
                        ];
                    })->toArray(),
                ];
            })->toArray();

            $data['related_circuit'] = $product->relatedCircuits->map(function ($circuit) {
                return [
                    'id' => $circuit->id,
                    'name' => $circuit->name,
                    'featuredImage' => $this->getMediaFiles($circuit, 'featuredImage'),
                    'featuredImages' => $this->getMediaFiles($circuit, 'featuredImages', true),
                    'prices' => $circuit->prices->map(function ($price) {
                        return [
                            'number_of_people' => $price->number_of_people,
                            'original_price_usd' => $price->original_price_usd,
                            'discounted_price_usd' => $price->discounted_price_usd,
                        ];
                    })->sortBy('number_of_people')->values()->toArray(),
                    'location' => $circuit->location,
                    'slug' => $circuit->slug,
                    'type' => $circuit->type,
                    'total_rating' => $circuit->total_rating,
                    'average_rating' => $circuit->average_rating,
                    'tagline' => $circuit->tagline,
                    'tags' => $circuit->tags->map(function ($tag) {
                        return [
                            'id' => $tag->id,
                            'name' => $tag->name,
                            'slug' => $tag->slug,
                            'description' => $tag->description,
                        ];
                    })->toArray(),
                ];
            })->toArray();

            $data['related_package'] = $product->relatedPackages->map(function ($packages) {
                return [
                    'id' => $packages->id,
                    'name' => $packages->name,
                    'featuredImage' => $this->getMediaFiles($packages, 'featuredImage'),
                    'featuredImages' => $this->getMediaFiles($packages, 'featuredImages', true),
                    'prices' => $packages->prices->map(function ($price) {
                        return [
                            'number_of_people' => $price->number_of_people,
                            'original_price_usd' => $price->original_price_usd,
                            'discounted_price_usd' => $price->discounted_price_usd,
                        ];
                    })->sortBy('number_of_people')->values()->toArray(),
                    'location' => $packages->location,
                    'slug' => $packages->slug,
                    'type' => $packages->type,
                    'total_rating' => $packages->total_rating,
                    'average_rating' => $packages->average_rating,
                    'tagline' => $packages->tagline,
                    'tags' => $packages->tags->map(function ($tag) {
                        return [
                            'id' => $tag->id,
                            'name' => $tag->name,
                            'slug' => $tag->slug,
                            'description' => $tag->description,
                        ];
                    })->toArray(),
                ];
            })->toArray();

            $data['related_blogs'] = $product->relatedBlogs->map(function ($blog) {
                return [
                    'id' => $blog->id,
                    'title' => $blog->title,
                    'slug' => $blog->slug,
                    'type' => $blog->type,
                    'publish_date' => $blog->publish_date,
                    'featuredImage' => $this->getMediaFiles($blog, 'featuredImage'),
                ];
            })->toArray();

            $data['tags'] = $product->tags->map(function ($tag) {
                return [
                    'id' => $tag->id,
                    'name' => $tag->name,
                    'slug' => $tag->slug,
                    'description' => $tag->description,
                ];
            })->toArray();

            $data['dossiers'] = $product->dossiers->map(function ($dossier) {
                return [
                    'id' => $dossier->id,
                    'content' => $dossier->content,
                    'pdf_file' => $dossier->pdf_path ? asset($dossier->pdf_path) : null
                ];
            });

            $entityMetadata = $product->meta()->first();

            $data['meta'] = [
                'metaTitle' => $entityMetadata->meta_title ?? null,
                'keywords' => isset($entityMetadata->meta_keywords) ? json_decode($entityMetadata->meta_keywords) : null,
                'metaDescription' => $entityMetadata->meta_description ?? null,
            ];

            if ($product->type === 'homestay') {
                $data['related_experiences'] = $this->formatRelatedExperiences($product->relatedExperiencesForHomestay);
            }

            return $data;

        } catch (\Exception $exception) {

            throw $exception;
        }
    }

    private function formatRelatedExperiences($relatedExperiences)
    {
        return $relatedExperiences->map(function ($experience) {
            return [
                'id' => $experience->id,
                'name' => $experience->name,
                'slug' => $experience->slug,
                'tagline' => $experience->tagline,
                'featuredImage' => $this->getMediaFiles($experience, 'featuredImage'),
                'featuredImages' => $this->getMediaFiles($experience, 'featuredImages', true),
                'prices' => $this->formatPrices($experience->prices),
                'location' => $experience->location,
                'tags' => $this->formatTags($experience->tags),
            ];
        })->toArray();
    }

    private function formatPrices($prices)
    {
        return $prices->map(function ($price) {
            return [
                'number_of_people' => $price->number_of_people,
                'original_price_usd' => $price->original_price_usd,
                'discounted_price_usd' => $price->discounted_price_usd,
            ];
        })->sortBy('number_of_people')->values()->toArray();
    }

    private function formatTags($tags)
    {
        return $tags->map(function ($tag) {
            return [
                'id' => $tag->id,
                'name' => $tag->name,
                'slug' => $tag->slug,
                'description' => $tag->description,
            ];
        })->toArray();
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
