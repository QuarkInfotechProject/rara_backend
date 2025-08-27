<?php

namespace Modules\Product\App\Service\Admin\Product\Circuit;

use Modules\Product\App\Models\Product;

class FetchCircuitDetailService
{
    public function getCircuitDetails($id)
    {
        try {
            $product = Product::with([
                'faqs',
                'overview',
                'itinerary',
                'included',
                'excluded',
                'whatToBring',
                'relatedBlogs',
                'tags',
                'prices',
                'dossiers'
            ])->findOrFail($id);

            $data = $product->toArray();

            $fieldsToRemove = ['manager_id', 'cancellation_policy', 'region', 'created_at', 'updated_at'];
            foreach ($fieldsToRemove as $field) {
                unset($data[$field]);
            }

            $data['faqs'] = $product->faqs->map(function ($faq) {
                return [
                    'id' => $faq->id,
                    'question' => $faq->question,
                    'answer' => $faq->answer,
                    'order' => $faq->order,
                ];
            })->toArray();

            $data['overview'] = $product->overview->map(function ($item) {
                return [
                    'id' => $item->id,
                    'name' => $item->name,
                    'description' => $item->description,
                    'duration' => $item->duration ?? null,
                    'overview_location' => $item->overview_location ?? null,
                    'trip_grade' => $item->trip_grade ?? null,
                    'max_altitude' => $item->max_altitude ?? null,
                    'best_time' => $item->best_time ?? null,
                    'group_size' => $item->group_size ?? null,
                    'activities' => $item->activities ?? null,
                    'starts' => $item->starts ?? null,
                ];
            })->toArray();

            $data['departures'] = $product->departures->map(function ($departure) {
                return [
                    'id' => $departure->id,
                    'departure_from' => $departure->departure_from,
                    'departure_to' => $departure->departure_to,
                    'departure_per_price' => $departure->departure_per_price,
                    'max_team_members' => $departure->max_team_members,
                ];
            })->toArray();

            $data['prices'] = $product->prices->map(function ($prices) {
                return [
                    'id' => $prices->id,
                    'number_of_people' => $prices->number_of_people,
                    'original_price_usd' => $prices->original_price_usd,
                    'discounted_price_usd' =>  $prices->discounted_price_usd,
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

            $data['included'] = $product->included->pluck('id')->toArray();
            $data['excluded'] = $product->excluded->pluck('id')->toArray();
            $data['what_to_bring'] = $product->whatToBring->pluck('id')->toArray();

            $data['related_circuit'] = $product->relatedCircuits->map(function ($homestay) {
                return [
                    'id' => $homestay->id,
                    'name' => $homestay->name,
                ];
            })->toArray();

            $data['related_blogs'] = $product->relatedBlogs->pluck('id')->toArray();
            $data['tags'] = $product->tags->pluck('id')->toArray();

            $data['files'] = [
                'featuredImages' => $this->getMediaFiles($product, 'featuredImages', true),
                'galleryImages' => $this->getMediaFiles($product, 'galleryImages', true),
                'locationCover' => $this->getMediaFiles($product, 'locationCover'),
                'howToGet' => $this->getMediaFiles($product, 'howToGet'),
                'featuredImage' => $this->getMediaFiles($product, 'featuredImage'),
                'faqImages' => $this->getMediaFiles($product, 'faqImages'),
            ];

            $entityMetadata = $product->meta()->first();

            $data['meta'] = [
                'metaTitle' => $entityMetadata->meta_title ?? '',
                'keywords' => isset($entityMetadata->meta_keywords) ? json_decode($entityMetadata->meta_keywords) : null,
                'metaDescription' => $entityMetadata->meta_description ?? '',
            ];

            $firstDossier = $product->dossiers->first();

            $data['dossiers'] = $firstDossier ? [
                'id' => $firstDossier->id,
                'content' => $firstDossier->content,
                'pdf_file' => $firstDossier->pdf_path ? asset($firstDossier->pdf_path) : null,
            ] : null;

            return $data;
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

    private function getMediaFiles($product, $type, $multiple = false)
    {
        $baseImageFiles = $product->filterFiles($type)->get();
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
