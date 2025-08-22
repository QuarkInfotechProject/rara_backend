<?php

namespace Modules\Product\App\Service\Admin\Product\Circuit;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Modules\Product\App\Models\Product;
use Modules\Shared\App\Events\AdminUserActivityLogEvent;
use Modules\Shared\Constant\ActivityTypeConstant;

class UpdateCircuitService
{
    public function updateCircuit($data, $ipAddress, $request)
    {
        $validator = $this->validateUpdateCircuitData($data);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        try {
            DB::beginTransaction();

            $product = Product::findOrFail($data['id']);

            $product->update([
                'name' => $data['name'],
                'slug' => $data['slug'],
                'short_code' => $data['short_code'],
                'type' => $data['type'],
                'category_details' => $data['category_details'],
                'tagline' => $data['tagline'],
                'short_description' => $data['short_description'],
                'description' => $data['description'],
                'display_order' => $data['display_order']?? null,
                'youtube_link' => $data['youtube_link'],
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
                'location' => $data['location'],
                'status' => $data['status'],
                'how_to_get' => $data['how_to_get'],
                'cornerstone' => $data['cornerstone'],
                'is_occupied' => $data['is_occupied'],
                'impact' => $data['impact'] ?? '',
                'display_homepage' => $data['display_homepage'] ?? '0',
                'manager_id' => $data['manager_id']?? null,
            ]);

            $this->updateProductPrices($product, $data['prices']);
            $this->updateFaqs($product, $data['faqs']);
            $this->updateOverview($product, $data['overview']); // Fixed: removed extra parameter
            $this->updateItinerary($product, $data['itinerary']);
            $this->updateIncluded($product, $data['included']);
            $this->updateExcluded($product, $data['excluded']);
            $this->updateWhatToBring($product, $data['what_to_bring']);
            $this->updateRelatedCircuits($product, $data['related_circuit']);
            $this->updateRelatedBlogs($product, $data['related_blogs']);
            $this->updateTags($product, $data['tags']);
            $this->updateDossier($product, $data['dossiers'], $request);
            $this->updateDepartures($product, $data['departures']); // Fixed: removed extra parameter

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        Event::dispatch(new AdminUserActivityLogEvent(
            "{$product->name} circuit has been updated by: " . Auth::user()->name,
            Auth::id(),
            ActivityTypeConstant::CIRCUIT_UPDATED,
            $ipAddress
        ));

        return $product;
    }

    private function updateProductPrices(Product $product, array $prices)
    {
        $existingProductPriceIds = $product->prices->pluck('id')->toArray();
        $updatedProductPriceIds = [];

        foreach ($prices as $priceData) {
            $price = null;
            if (isset($priceData['id'])) {
                $price = $product->prices()->find($priceData['id']);
                if ($price) {
                    $price->update([
                        'number_of_people' => $priceData['number_of_people'] ?? 0,
                        'original_price_usd' => $priceData['original_price_usd'],
                        'discounted_price_usd' => $priceData['discounted_price_usd'],
                    ]);
                    $updatedProductPriceIds[] = $price->id;
                }
            }

            if (!$price) {
                $price = $product->prices()->create([
                    'number_of_people' => $priceData['number_of_people'] ?? 0,
                    'original_price_usd' => $priceData['original_price_usd'],
                    'discounted_price_usd' => $priceData['discounted_price_usd'],
                ]);
                $updatedProductPriceIds[] = $price->id;
            }

            if (isset($priceData['priceFiles']) && is_array($priceData['priceFiles'])) {
                $price->syncFiles($priceData['priceFiles']);
            }
        }

        $pricesToDelete = array_diff($existingProductPriceIds, $updatedProductPriceIds);
        foreach ($pricesToDelete as $priceId) {
            $priceToDelete = $product->prices()->find($priceId);
            if ($priceToDelete) {
                $priceToDelete->delete();
            }
        }
    }

    private function updateFaqs(Product $product, array $faqs)
    {
        $existingFaqIds = $product->faqs->pluck('id')->toArray();
        $updatedFaqIds = [];

        foreach ($faqs as $faqData) {
            $faq = null;
            if (isset($faqData['id'])) {
                $faq = $product->faqs()->find($faqData['id']);
                if ($faq) {
                    $faq->update([
                        'question' => $faqData['question'],
                        'answer' => $faqData['answer'],
                        'order' => $faqData['order'],
                    ]);
                    $updatedFaqIds[] = $faq->id;
                }
            }

            if (!$faq) {
                $faq = $product->faqs()->create([
                    'question' => $faqData['question'],
                    'answer' => $faqData['answer'],
                    'order' => $faqData['order'],
                ]);
                $updatedFaqIds[] = $faq->id;
            }
        }

        $faqsToDelete = array_diff($existingFaqIds, $updatedFaqIds);
        $product->faqs()->whereIn('id', $faqsToDelete)->delete();
    }

    // FIXED: Overview should be handled as a single record, not multiple
    private function updateOverview(Product $product, array $overviewData)
    {
        // Helper method to get overview values from mixed structure
        $getOverviewValue = function($data, $key, $default = '') {
            // First try direct key access
            if (isset($data[$key])) {
                return $data[$key];
            }
            // Then try nested array access
            if (isset($data[0][$key])) {
                return $data[0][$key];
            }
            return $default;
        };

        // Get the existing overview (should be only one)
        $existingOverview = $product->overview()->first();

        $overviewRecord = [
            'name' => $getOverviewValue($overviewData, 'name'),
            'description' => $getOverviewValue($overviewData, 'description'),
            'duration' => $getOverviewValue($overviewData, 'duration'),
            'overview_location' => $getOverviewValue($overviewData, 'overview_location'),
            'trip_grade' => $getOverviewValue($overviewData, 'trip_grade'),
            'max_altitude' => (int)$getOverviewValue($overviewData, 'max_altitude', 0),
            'group_size' => (int)$getOverviewValue($overviewData, 'group_size', 0),
            'activities' => $getOverviewValue($overviewData, 'activities'),
            'best_time' => $getOverviewValue($overviewData, 'best_time'),
            'starts' => $getOverviewValue($overviewData, 'starts'),
        ];

        if ($existingOverview) {
            // Update existing overview
            $existingOverview->update($overviewRecord);
        } else {
            // Create new overview if none exists
            $product->overview()->create(array_merge($overviewRecord, ['product_id' => $product->id]));
        }
    }

    private function updateItinerary(Product $product, array $itinerary)
    {
        $existingItineraryIds = $product->itinerary->pluck('id')->toArray();
        $updatedItineraryIds = [];

        foreach ($itinerary as $itineraryData) {
            $itineraryItem = null;
            if (isset($itineraryData['id'])) {
                $itineraryItem = $product->itinerary()->find($itineraryData['id']);
                if ($itineraryItem) {
                    $itineraryItem->update([
                        'time_window' => $itineraryData['time_window'],
                        'activity' => $itineraryData['activity'],
                        'order' => $itineraryData['order'],
                    ]);
                    $updatedItineraryIds[] = $itineraryItem->id;
                }
            }

            if (!$itineraryItem) {
                $itineraryItem = $product->itinerary()->create([
                    'time_window' => $itineraryData['time_window'],
                    'activity' => $itineraryData['activity'],
                    'order' => $itineraryData['order'],
                ]);
                $updatedItineraryIds[] = $itineraryItem->id;
            }
        }

        $itineraryToDelete = array_diff($existingItineraryIds, $updatedItineraryIds);
        $product->itinerary()->whereIn('id', $itineraryToDelete)->delete();
    }

    private function updateDossier(Product $product, array $dossierData, $request)
    {
        $pdfFile = $request->file('dossiers')['pdf_file'] ?? null;
        $pdfPath = null;

        // Handle file upload if present
        if ($pdfFile && $pdfFile->isValid()) {
            $pdfPath = 'storage/' . $pdfFile->store('dossiers', 'public');
        } else {
            $pdfPath = $dossierData['pdf_path'] ?? null; // keep existing if not replaced
        }

        // Check if dossier already exists
        $existingDossier = $product->dossiers()->first();

        if ($existingDossier) {
            $existingDossier->update([
                'content' => $dossierData['content'],
                'pdf_path' => $pdfPath,
            ]);
        } else {
            $product->dossiers()->create([
                'content' => $dossierData['content'],
                'pdf_path' => $pdfPath,
            ]);
        }
    }

    private function updateIncluded(Product $product, array $includedIds)
    {
        $product->included()->sync($includedIds);
    }

    private function updateExcluded(Product $product, array $excludedIds)
    {
        $product->excluded()->sync($excludedIds);
    }

    private function updateWhatToBring(Product $product, array $whatToBringIds)
    {
        $product->whatToBring()->sync($whatToBringIds);
    }

    private function updateRelatedCircuits(Product $product, array $circuits)
    {
        $product->relatedCircuits()->detach();
        if (!empty($circuits)) {
            foreach ($circuits as $id) {
                $product->relatedCircuits()->attach($id, ['relation_type' => 'related_circuit']);
            }
        }
    }

    private function updateRelatedBlogs(Product $product, array $blogIds)
    {
        $product->relatedBlogs()->sync($blogIds);
    }

    private function updateTags(Product $product, array $tagIds)
    {
        $product->tags()->sync($tagIds);
    }

    // FIXED: Removed extra $request parameter

    private function updateDepartures(Product $product, array $departures)
    {
        $existingDepartureIds = $product->departures->pluck('id')->toArray();
        $updatedDepartureIds = [];

        foreach ($departures as $departureData) {
            if (!empty($departureData['id'])) {
                $departure = $product->departures()->find($departureData['id']);
                if ($departure) {
                    $departure->update([
                        'departure_from' => $departureData['departure_from'],
                        'departure_to' => $departureData['departure_to'],
                        'departure_per_price' => $departureData['departure_per_price'],
                    ]);
                    $updatedDepartureIds[] = $departure->id;
                    continue; // ✅ prevents creating duplicate
                }
            }

            // create new if no valid id
            $departure = $product->departures()->create([
                'departure_from' => $departureData['departure_from'],
                'departure_to' => $departureData['departure_to'],
                'departure_per_price' => $departureData['departure_per_price'],
            ]);
            $updatedDepartureIds[] = $departure->id;
        }

        // Delete removed ones
        $departuresToDelete = array_diff($existingDepartureIds, $updatedDepartureIds);
        $product->departures()->whereIn('id', $departuresToDelete)->delete();
    }

    public function validateUpdateCircuitData(array $data)
    {
        return Validator::make($data, [
            'id' => 'required|exists:products,id',
            'name' => 'required|string|max:150',
            'slug' => [
                'required',
                'string',
                'max:200',
                Rule::unique('products')->ignore($data['id']),
            ],
            'type' => 'required|string',
            'short_code' => 'required|string|max:50',
            'tagline' => 'nullable|string|max:255',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'prices' => 'required|array|min:1',
            'prices.*.id' => 'nullable|exists:product_prices,id',
            'prices.*.number_of_people' => 'required|integer|min:0',
            'prices.*.original_price_usd' => 'required|numeric|min:0',
            'prices.*.discounted_price_usd' => 'required|numeric|min:0',
            'variable_prices' => 'required_if:has_variable_pricing,true|array',
            'variable_prices.*.number_of_people' => 'required_if:has_variable_pricing,true|integer|min:1',
            'variable_prices.*.price' => 'required_if:has_variable_pricing,true|numeric|min:0',
            'display_order' => 'nullable|integer',
            'youtube_link' => 'nullable|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'location' => 'required|string',
            'status' => 'required|string|in:draft,published,archived',
            'how_to_get' => 'nullable|string',
            'cornerstone' => 'boolean',
            'is_occupied' => 'boolean',
            'impact' => 'nullable|string',

            'faqs' => 'nullable|array',
            'faqs.*.id' => 'nullable|exists:product_faqs,id',
            'faqs.*.question' => 'required|string',
            'faqs.*.answer' => 'required|string',
            'faqs.*.order' => 'required|integer',

            // FIXED: Overview validation for single record with mixed structure support
            'overview' => 'required|array',
            'overview.0.name' => 'nullable|string',        // Support nested structure
            'overview.0.description' => 'nullable|string', // Support nested structure
            'overview.name' => 'nullable|string',           // Support flat structure
            'overview.description' => 'nullable|string',    // Support flat structure
            'overview.duration' => 'required|string',
            'overview.overview_location' => 'required|string',
            'overview.trip_grade' => 'required|string',
            'overview.max_altitude' => 'required|integer',
            'overview.group_size' => 'required|integer',
            'overview.activities' => 'required|string',
            'overview.best_time' => 'required|string',
            'overview.starts' => 'required|string',

            'itinerary' => 'required|array',
            'itinerary.*.id' => 'nullable|exists:product_itineraries,id',
            'itinerary.*.time_window' => 'required|string',
            'itinerary.*.activity' => 'required|string',
            'itinerary.*.order' => 'required|integer',

            'included' => 'required|array',
            'included.*' => 'exists:amenities,id',

            'excluded' => 'required|array',
            'excluded.*' => 'exists:amenities,id',

            'what_to_bring' => 'required|array',
            'what_to_bring.*' => 'exists:amenities,id',

            'related_circuit' => 'nullable|array',
            'related_circuit.*' => 'exists:products,id',

            'related_blogs' => 'required|array',
            'related_blogs.*' => 'exists:blogs,id',

            'tags' => 'required|array',
            'tags.*' => 'exists:tags,id',

            'meta' => 'required|array',
            'meta.metaTitle' => 'required|string|max:60',
            'meta.keywords' => 'required|array',
            'meta.keywords.*' => 'string',
            'meta.metaDescription' => 'required|string|max:160',

            'files' => 'required|array',
            'files.featuredImages' => 'required|array',
            'files.featuredImages.*' => 'exists:files,id',
            'files.galleryImages' => 'required|array',
            'files.galleryImages.*' => 'exists:files,id',
            'files.faqImages.*' => 'exists:files,id',
            'files.locationCover' => 'required|exists:files,id',
            'files.howToGet' => 'nullable|exists:files,id',

            'dossiers' => 'nullable|array',
            'dossiers.content' => 'nullable|string',
            'dossiers.pdf_path' => 'nullable|string', // Changed from file validation since it's optional on update

            'departures' => 'required|array',
            'departures.*.id' => 'nullable|exists:product_departures,id',
            'departures.*.departure_from' => 'required|string|max:255',
            'departures.*.departure_to' => 'required|string|max:255',
            'departures.*.departure_per_price' => 'required|string|max:255',
        ]);
    }
}
