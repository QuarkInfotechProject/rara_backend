<?php

namespace Modules\Product\App\Service\Admin\Product\Activities;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\Product\App\Models\Product;
use Modules\Product\App\Models\ProductFaq;
use Modules\Product\App\Models\ProductItinerary;
use Modules\Product\App\Models\ProductOverview;
use Modules\Shared\App\Events\AdminUserActivityLogEvent;
use Modules\Shared\Constant\ActivityTypeConstant;
use Modules\Product\App\Models\Dossier;

class CreateActivitiesService
{
    public function createActivities($data, $ipAddress, $request)
    {
        $validator = $this->validateCircuitData($data);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        try {
            DB::beginTransaction();

            $product = Product::create([
                'name' => $data['name'],
                'slug' => $data['slug'],
                'short_code' => $data['short_code'],
                'type' => $data['type'],
                'category_details' => $data['category_details'],
                'tagline' => $data['tagline'],
                'short_description' => $data['short_description'],
                'description' => $data['description'],
                'display_order' => $data['display_order']?? null,
                'youtube_link' => $data['youtube_link']?? null,
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
                'location' => $data['location'],
                'status' => $data['status'],
                'how_to_get' => $data['how_to_get'],
                'cornerstone' => $data['cornerstone'] ?? '0',
                'is_occupied' => $data['is_occupied'],
                'impact' => $data['impact'] ?? '',
                'display_homepage' => $data['display_homepage'] ?? '0',
                'manager_id' => $data['manager_id']?? null,
            ]);

            $this->createProductPrices($product, $data['prices']);
            $this->createFaqs($product, $data['faqs']);
            $this->createOverview($product, $data['overview'],$request);
            $this->createItinerary($product, $data['itinerary']);
            $this->attachIncluded($product, $data['included']);
            $this->attachExcluded($product, $data['excluded']);
            $this->attachWhatToBring($product, $data['what_to_bring']);
            $this->attachRelatedBlogs($product, $data['related_blogs']);
            $this->attachTags($product, $data['tags'] ?? []);
            $this->createDossier($product, $data['dossiers'], $request);
            $this->createDepartures($product, $data['departures']);

            if (!empty($data['related_circuit']) && is_array($data['related_circuit'])) {
                $this->attachRelatedCircuits($product, $data['related_circuit']);
            }

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        Event::dispatch(new AdminUserActivityLogEvent(
                "{$product->name} activities has been added by: " . Auth::user()->name,
                Auth::id(),
                ActivityTypeConstant::ACTIVITIES_ADDED,
                $ipAddress)
        );

        return $product;
    }

    private function createProductPrices(Product $product, array $prices)
    {
        foreach ($prices as $priceData) {
            $product->prices()->create([
                'number_of_people' => $priceData['number_of_people'] ?? 0,
                'original_price_usd' => $priceData['original_price_usd'],
                'discounted_price_usd' => $priceData['discounted_price_usd'],
            ]);
        }
    }

    private function createFaqs(Product $product, array $faqs)
    {
        foreach ($faqs as $faqData) {
            ProductFaq::create([
                'product_id' => $product->id,
                'question' => $faqData['question'],
                'answer' => $faqData['answer'],
                'order' => $faqData['order'],
            ]);
        }
    }

    private function createOverview(Product $product, array $overviewData)
    {
        $overviewRecord = [
            'product_id' => $product->id,
            'name' => $this->getOverviewValue($overviewData, 'name')?? null,
            'description' => $this->getOverviewValue($overviewData, 'description')?? null,
            'duration' => $this->getOverviewValue($overviewData, 'duration'),
            'overview_location' => $this->getOverviewValue($overviewData, 'overview_location'),
            'trip_grade' => $this->getOverviewValue($overviewData, 'trip_grade'),
            'max_altitude' => (int)$this->getOverviewValue($overviewData, 'max_altitude', 0),
            'group_size' => (int)$this->getOverviewValue($overviewData, 'group_size', 0),
            'activities' => $this->getOverviewValue($overviewData, 'activities'),
            'best_time' => $this->getOverviewValue($overviewData, 'best_time'),
            'starts' => $this->getOverviewValue($overviewData, 'starts'),
        ];

        try {
            $created = ProductOverview::create($overviewRecord);
            \Log::info('Successfully created overview with ID:', ['id' => $created->id]);
        } catch (\Exception $e) {
            \Log::error('Failed to create overview:', ['error' => $e->getMessage()]);
            throw $e;
        }
    }

    private function getOverviewValue(array $data, string $key, $default = '')
    {
        if (isset($data[$key])) {
            return $data[$key];
        }

        if (isset($data[0][$key])) {
            return $data[0][$key];
        }

        return $default;
    }


    private function createItinerary(Product $product, array $itineraryItems)
    {
        foreach ($itineraryItems as $item) {
            ProductItinerary::create([
                'product_id' => $product->id,
                'time_window' => $item['time_window'],
                'activity' => $item['activity'],
                'order' => $item['order'],
                'duration' => $item['duration'],
                'location' => $item['location'],
                'max_altitude' => $item['max_altitude'],
                'activities' => $item['activities'],
                'accommodation' => $item['accommodation'],
                'meal' => $item['meal'],
            ]);
        }
    }

    private function attachIncluded(Product $product, array $includedIds)
    {
        $product->included()->attach($includedIds);
    }

    private function attachExcluded(Product $product, array $excludedIds)
    {
        $product->excluded()->attach($excludedIds);
    }

    private function attachWhatToBring(Product $product, array $whatToBringIds)
    {
        if (empty($whatToBringIds)) {
            return;
        }
        $cleanIds = array_filter($whatToBringIds, fn($id) => !is_null($id) && $id !== '');

        if (!empty($cleanIds)) {
            $product->whatToBring()->attach($cleanIds);
        }
    }

    private function attachRelatedCircuits(Product $product, array $circuitIds)
    {
        $product->relatedCircuits()->attach($circuitIds, ['relation_type' => 'related_circuit']);
    }

    private function attachRelatedBlogs(Product $product, array $blogIds)
    {
        $product->relatedBlogs()->attach($blogIds);
    }

    private function attachTags(Product $product, ?array $tagIds)
    {
        if (empty($tagIds)) {
            return;
        }

        $cleanIds = array_filter($tagIds, fn($id) => !is_null($id) && $id !== '');

        if (!empty($cleanIds)) {
            $product->tags()->attach($cleanIds);
        }
    }
    private function createDepartures(Product $product, array $departures)
    {
        foreach ($departures as $departureData) {
            if (isset($departureData['id']) && $departureData['id']) {
                $product->departures()->where('id', $departureData['id'])->update([
                    'departure_from' => $departureData['departure_from'] ?? null,
                    'departure_to' => $departureData['departure_to'] ?? null,
                    'departure_per_price' => $departureData['departure_per_price'] ?? null,
                    'max_team_members' => $departureData['max_team_members'] ?? null,

                ]);
            } else {
                $product->departures()->create([
                    'departure_from' => $departureData['departure_from'] ?? null,
                    'departure_to' => $departureData['departure_to'] ?? null,
                    'departure_per_price' => $departureData['departure_per_price'] ?? null,
                    'max_team_members' => $departureData['max_team_members'] ?? null,
                ]);
            }
        }
    }

    private function createDossier(Product $product, array $dossiers, $request)
    {
        $pdfFile = $request->file('dossiers')['pdf_file'] ?? null;

        $pdfPath = null;
        if ($pdfFile && $pdfFile->isValid()) {
            $storedPath = $pdfFile->store('dossiers', 'public');
            $pdfPath = 'storage/' . $storedPath;
        }

        Dossier::create([
            'product_id' => $product->id,
            'content' => $dossiers['content']?? null,
            'pdf_path' => $pdfPath,
        ]);

    }

    public function validateCircuitData(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:150',
            'slug' => 'required|string|max:200|unique:products',
            'short_code' => 'required|string|max:50',
            'type' => 'required|string',
            'manager_id' => 'nullable|integer',
            'tagline' => 'nullable|string|max:255',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'prices' => 'required|array|min:1',
            'prices.*.number_of_people' => 'required|integer|min:0',
            'prices.*.original_price_usd' => 'required|numeric|min:0',
            'prices.*.discounted_price_usd' => 'required|numeric|min:0',
            'variable_prices' => 'required_if:has_variable_pricing,true|array',
            'variable_prices.*.number_of_people' => 'required_if:has_variable_pricing,true|integer|min:0',
            'variable_prices.*.price' => 'required_if:has_variable_pricing,true|numeric|min:0',
            'display_order' => 'nullable|integer',
            'youtube_link' => 'nullable|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'location' => 'required|string',
            'status' => 'required|string|in:draft,published',
            'how_to_get' => 'nullable|string',
            'cornerstone' => 'nullable|in:0,1',
            'is_occupied' => 'nullable|boolean',
            'impact' => 'nullable|string',

            'faqs' => 'required|array',
            'faqs.*.question' => 'required|string',
            'faqs.*.answer' => 'required|string',
            'faqs.*.order' => 'required|integer',

            'overview' => 'required|array',
            'overview.0.name' => 'nullable|string',
            'overview.0.description' => 'nullable|string',
            'overview.name' => 'nullable|string',
            'overview.description' => 'nullable|string',
            'overview.duration' => 'required|string',
            'overview.overview_location' => 'required|string',
            'overview.trip_grade' => 'required|string',
            'overview.max_altitude' => 'required|integer',
            'overview.group_size' => 'required|integer',
            'overview.activities' => 'required|string',
            'overview.best_time' => 'required|string',
            'overview.starts' => 'required|string',

            'itinerary' => 'required|array',
            'itinerary.*.time_window' => 'required|string',
            'itinerary.*.activity' => 'required|string',
            'itinerary.*.order' => 'required|integer',

            'included' => 'nullable|array',
            'included.*' => 'exists:amenities,id',

            'excluded' => 'nullable|array',
            'excluded.*' => 'exists:amenities,id',

            'what_to_bring' => 'nullable|array',

            'related_circuit' => 'nullable|array',
            'related_circuit.*' => 'exists:products,id',

            'related_blogs' => 'required|array',
            'related_blogs.*' => 'exists:blogs,id',

            'tags' => 'nullable|array',
//            'tags.*' => 'exists:tags,id',

            'meta' => 'required|array',
            'meta.metaTitle' => 'required|string',
            'meta.keywords' => 'required|array',
            'meta.metaDescription' => 'required|string|max:200',

            'files' => 'required|array',
            'files.featuredImages' => 'nullable|array',
            'files.featuredImages.*' => 'exists:files,id',
            'files.galleryImages' => 'nullable|array',
            'files.galleryImages.*' => 'exists:files,id',
            'files.faqImages' => 'required|array',
            'files.faqImages.*' => 'exists:files,id',
            'files.locationCover' => 'nullable|exists:files,id',
            'files.altitudeChart' => 'nullable|exists:files,id',

            'dossiers' => 'required|array',
            'dossiers.content' => 'nullable|string',
            'dossiers.pdf_file' => 'required|file|mimes:pdf|max:5120',

            'departures' => 'required|array',
            'departures.*.id' => 'nullable|exists:product_departures,id',
            'departures.*.departure_from' => 'required|string|max:255',
            'departures.*.departure_to' => 'required|string|max:255',
            'departures.*.departure_per_price' => 'required|string|max:255',
        ]);
    }
}
