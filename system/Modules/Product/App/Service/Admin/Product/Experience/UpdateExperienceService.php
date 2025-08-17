<?php

namespace Modules\Product\App\Service\Admin\Product\Experience;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Modules\Product\App\Models\Product;
use Modules\Shared\App\Events\AdminUserActivityLogEvent;
use Modules\Shared\Constant\ActivityTypeConstant;

class UpdateExperienceService
{
    public function updateExperience($data, $ipAddress)
    {
        $validator = $this->validateUpdateExperienceData($data);

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
                'tagline' => $data['tagline'],
                'short_description' => $data['short_description'],
                'description' => $data['description'],
                'display_order' => $data['display_order'],
                'youtube_link' => $data['youtube_link'],
                'latitude' => $data['latitude'],
                'longitude' => $data['longitude'],
                'location' => $data['location'],
                'status' => $data['status'],
                'how_to_get' => $data['how_to_get'],
                'cornerstone' => $data['cornerstone'],
                'is_occupied' => $data['is_occupied'],
                'impact' => $data['impact'],
                'display_homepage' => $data['display_homepage'],
            ]);

            $this->updateFaqs($product, $data['faqs']);
            $this->updateProductPrices($product, $data['prices']);
            $this->updateItinerary($product, $data['itinerary']);
            $this->updateIncluded($product, $data['included']);
            $this->updateWhatToBring($product, $data['what_to_bring']);
            $this->updateRelatedHomestays($product, $data['related_homestay']);
            $this->updateRelatedBlogs($product, $data['related_blogs']);
            $this->updateTags($product, $data['tags']);
            $this->updateOverview($product, $data['overview']);
            $this->attachRelatedExperience($product, $data['related_experience']);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        Event::dispatch(new AdminUserActivityLogEvent(
            "{$product->name} experience has been updated by: " . Auth::user()->name,
            Auth::id(),
            ActivityTypeConstant::EXPERIENCE_UPDATED,
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
                        'number_of_people' => $priceData['number_of_people'],
                        'original_price_usd' => $priceData['original_price_usd'],
                        'discounted_price_usd' => $priceData['discounted_price_usd'],
                    ]);
                    $updatedProductPriceIds[] = $price->id;
                }
            }

            if (!$price) {
                $price = $product->prices()->create([
                    'number_of_people' => $priceData['number_of_people'],
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
        foreach ($faqsToDelete as $faqId) {
            $faqToDelete = $product->faqs()->find($faqId);
            if ($faqToDelete) {
                $faqToDelete->delete();
            }
        }
    }

    private function updateOverview(Product $product, array $overview)
    {
        $existingOverviewIds = $product->overview->pluck('id')->toArray();
        $updatedOverviewIds = [];

        foreach ($overview as $overviewData) {
            $overviewItem = null;
            if (isset($overviewData['id'])) {
                $overviewItem = $product->overview()->find($overviewData['id']);
                if ($overviewItem) {
                    $overviewItem->update([
                        'name' => $overviewData['name'],
                        'description' => $overviewData['description'],
                    ]);
                    $updatedOverviewIds[] = $overviewItem->id;
                }
            }

            if (!$overviewItem) {
                $overviewItem = $product->overview()->create([
                    'name' => $overviewData['name'],
                    'description' => $overviewData['description'],
                ]);
                $updatedOverviewIds[] = $overviewItem->id;
            }
        }

        $overviewToDelete = array_diff($existingOverviewIds, $updatedOverviewIds);
        $product->overview()->whereIn('id', $overviewToDelete)->delete();
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
        foreach ($itineraryToDelete as $itineraryId) {
            $itineraryToDelete = $product->itinerary()->find($itineraryId);
            if ($itineraryToDelete) {
                $itineraryToDelete->delete();
            }
        }
    }

    private function updateIncluded(Product $product, array $includedIds)
    {
        $product->included()->sync($includedIds);
    }

    private function updateWhatToBring(Product $product, array $whatToBringIds)
    {
        $product->whatToBring()->sync($whatToBringIds);
    }

    private function updateRelatedHomestays(Product $product, array $homestayIds)
    {
        $product->relatedHomestaysForExperience()->detach();
        foreach ($homestayIds as $id) {
            $product->relatedHomestaysForExperience()->attach($id, ['relation_type' => 'related_homestay']);
        }
    }

    private function attachRelatedExperience(Product $product, array $homestayIds)
    {
        $product->relatedExperience()->detach();
        foreach ($homestayIds as $id) {
            $product->relatedExperience()->attach($id, ['relation_type' => 'related_experience']);
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

    public function validateUpdateExperienceData(array $data)
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
            'type' => 'required|string|max:20',
            'short_code' => 'required|string|max:50',
            'tagline' => 'nullable|string|max:255',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'prices' => 'required|array|min:1',
            'prices.*.number_of_people' => 'required|integer|min:1',
            'prices.*.original_price_usd' => 'required|numeric|min:0',
            'prices.*.discounted_price_usd' => 'required|numeric|min:0',
            'variable_prices' => 'required_if:has_variable_pricing,true|array',
            'variable_prices.*.number_of_people' => 'required_if:has_variable_pricing,true|integer|min:1',
            'variable_prices.*.price' => 'required_if:has_variable_pricing,true|numeric|min:0',
            'display_order' => 'nullable|integer',
            'youtube_link' => 'nullable',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'location' => 'nullable|string',
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

            'itinerary' => 'nullable|array',
            'itinerary.*.id' => 'nullable|exists:product_itineraries,id',
            'itinerary.*.time_window' => 'required|string',
            'itinerary.*.activity' => 'required|string',
            'itinerary.*.order' => 'required|integer',

            'included' => 'required|array',
            'included.*' => 'exists:amenities,id',

            'what_to_bring' => 'required|array',
            'what_to_bring.*' => 'exists:amenities,id',

            'related_homestay' => 'nullable|array',
            'related_homestay.*' => 'exists:products,id',

            'related_blogs' => 'required|array',
            'related_blogs.*' => 'exists:blogs,id',

            'tags' => 'required|array',
            'tags.*' => 'exists:tags,id',

            'overview' => 'required|array',
            'overview.*.id' => 'nullable|exists:product_overviews,id',
            'overview.*.name' => 'required|string',
            'overview.*.description' => 'required|string',

            'meta' => 'nullable|array',
            'meta.metaTitle' => 'required|string|max:60',
            'meta.keywords' => 'required|array',
            'meta.keywords.*' => 'string',
            'meta.metaDescription' => 'required|string|max:160',

            'files' => 'nullable|array',
            'files.featuredImages' => 'required|array',
            'files.featuredImages.*' => 'exists:files,id',
            'files.galleryImages' => 'required|array',
            'files.galleryImages.*' => 'exists:files,id',
            'files.locationCover' => 'required|exists:files,id',
            'files.howToGet' => 'nullable|exists:files,id',
        ]);
    }




}
