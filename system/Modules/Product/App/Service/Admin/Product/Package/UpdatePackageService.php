<?php

namespace Modules\Product\App\Service\Admin\Product\Package;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Modules\Product\App\Models\Product;
use Modules\Shared\App\Events\AdminUserActivityLogEvent;
use Modules\Shared\Constant\ActivityTypeConstant;

class UpdatePackageService
{
    public function updatePackage($data, $ipAddress)
    {
        $validator = $this->validateUpdatePackageData($data);

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
                'night' => $data['night'],
                'display_homepage' => $data['display_homepage'],
            ]);

            $this->updateFaqs($product, $data['faqs']);
            $this->updateProductPrices($product, $data['prices']);
            $this->updateItinerary($product, $data['itinerary']);
            $this->updateIncluded($product, $data['included']);
            $this->updateExcluded($product, $data['excluded']);
            $this->updateWhatToBring($product, $data['what_to_bring']);
            $this->updateRelatedPackages($product, $data['related_package']);
            $this->updateRelatedBlogs($product, $data['related_blogs']);
            $this->updateTags($product, $data['tags']);
            $this->updateHighlights($product, $data['highlights']);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        Event::dispatch(new AdminUserActivityLogEvent(
            "{$product->name} package has been updated by: " . Auth::user()->name,
            Auth::id(),
            ActivityTypeConstant::PACKAGE_UPDATED,
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
        $product->faqs()->whereIn('id', $faqsToDelete)->delete();
    }

    private function updateItinerary(Product $product, array $itineraryItems)
    {
        $existingItemIds = $product->itinerary->pluck('id')->toArray();
        $updatedItemIds = [];

        foreach ($itineraryItems as $itemData) {
            $item = null;
            if (isset($itemData['id'])) {
                $item = $product->itinerary()->find($itemData['id']);
                if ($item) {
                    $item->update([
                        'time_window' => $itemData['time_window'],
                        'activity' => $itemData['activity'],
                        'order' => $itemData['order'],
                    ]);
                    $updatedItemIds[] = $item->id;
                }
            }

            if (!$item) {
                $item = $product->itinerary()->create([
                    'time_window' => $itemData['time_window'],
                    'activity' => $itemData['activity'],
                    'order' => $itemData['order'],
                ]);
                $updatedItemIds[] = $item->id;
            }
        }

        $itemsToDelete = array_diff($existingItemIds, $updatedItemIds);
        $product->itinerary()->whereIn('id', $itemsToDelete)->delete();
    }

    private function updateHighlights(Product $product, array $highlights)
    {
        $existingHighlightIds = $product->highlights->pluck('id')->toArray();
        $updatedHighlightIds = [];

        foreach ($highlights as $highlightData) {
            $highlight = null;
            if (isset($highlightData['id'])) {
                $highlight = $product->highlights()->find($highlightData['id']);
                if ($highlight) {
                    $highlight->update([
                        'title' => $highlightData['title'],
                        'description' => $highlightData['description'],
                        'order' => $highlightData['order'],
                    ]);
                    $updatedHighlightIds[] = $highlight->id;
                }
            }

            if (!$highlight) {
                $highlight = $product->highlights()->create([
                    'title' => $highlightData['title'],
                    'description' => $highlightData['description'],
                    'order' => $highlightData['order'],
                ]);
                $updatedHighlightIds[] = $highlight->id;
            }

            if (isset($highlightData['highlightFiles']) && is_array($highlightData['highlightFiles'])) {
                $highlight->syncFiles($highlightData['highlightFiles']);
            }
        }

        $highlightsToDelete = array_diff($existingHighlightIds, $updatedHighlightIds);
        foreach ($highlightsToDelete as $highlightId) {
            $highlightToDelete = $product->highlights()->find($highlightId);
            if ($highlightToDelete) {
                $highlightToDelete->delete();
            }
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

    private function updateRelatedPackages(Product $product, array $packageIds)
    {
        $product->relatedPackages()->detach();
        foreach ($packageIds as $id) {
            $product->relatedPackages()->attach($id, ['relation_type' => 'related_package']);
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

    public function validateUpdatePackageData(array $data)
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
            'type' => 'required|string|in:package',
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
            'youtube_link' => 'nullable|string',
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'location' => 'required|string',
            'status' => 'required|string|in:draft,published',
            'how_to_get' => 'nullable|string',
            'cornerstone' => 'required|boolean',
            'is_occupied' => 'required|boolean',
            'impact' => 'nullable|string',
            'night' => 'required|integer|min:1',

            'faqs' => 'required|array',
            'faqs.*.id' => 'nullable|exists:product_faqs,id',
            'faqs.*.question' => 'required|string',
            'faqs.*.answer' => 'required|string',
            'faqs.*.order' => 'required|integer',

            'itinerary' => 'required|array',
            'itinerary.*.id' => 'nullable|exists:product_itinerary,id',
            'itinerary.*.time_window' => 'required|string',
            'itinerary.*.activity' => 'required|string',
            'itinerary.*.order' => 'required|integer',

            'included' => 'required|array',
            'included.*' => 'exists:amenities,id',

            'excluded' => 'required|array',
            'excluded.*' => 'exists:amenities,id',

            'what_to_bring' => 'required|array',

            'related_package' => 'nullable|array',
            'related_package.*' => 'exists:products,id',

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
            'files.locationCover' => 'required|exists:files,id',
            'files.howToGet' => 'nullable|exists:files,id',
        ]);
    }
}
