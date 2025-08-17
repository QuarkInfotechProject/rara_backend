<?php

namespace Modules\Product\App\Service\Admin\Product\Package;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\Product\App\Models\Product;
use Modules\Product\App\Models\ProductFaq;
use Modules\Product\App\Models\ProductHighlight;
use Modules\Product\App\Models\ProductItinerary;
use Modules\Shared\App\Events\AdminUserActivityLogEvent;
use Modules\Shared\Constant\ActivityTypeConstant;

class CreatePackageService
{
    public function createPackage($data, $ipAddress)
    {
        $validator = $this->validatePackageData($data);

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

            $this->createProductPrices($product, $data['prices']);
            $this->createFaqs($product, $data['faqs']);
            $this->createItinerary($product, $data['itinerary']);
            $this->attachIncluded($product, $data['included']);
            $this->attachExcluded($product, $data['excluded']);
            $this->attachWhatToBring($product, $data['what_to_bring']);
            $this->attachRelatedBlogs($product, $data['related_blogs']);
            $this->attachTags($product, $data['tags']);
            $this->createHighlights($product, $data['highlights']);

            if (!empty($data['related_package']) && is_array($data['related_package'])) {
                $this->attachRelatedPackages($product, $data['related_package']);
            }

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        Event::dispatch(new AdminUserActivityLogEvent(
                "{$product->name} package has been added by: " . Auth::user()->name,
                Auth::id(),
                ActivityTypeConstant::PACKAGE_ADDED,
                $ipAddress)
        );

        return $product;
    }

    private function createProductPrices(Product $product, array $prices)
    {
        foreach ($prices as $priceData) {
            $product->prices()->create([
                'number_of_people' => $priceData['number_of_people'],
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

    private function createItinerary(Product $product, array $itineraryItems)
    {
        foreach ($itineraryItems as $item) {
            ProductItinerary::create([
                'product_id' => $product->id,
                'time_window' => $item['time_window'],
                'activity' => $item['activity'],
                'order' => $item['order'],
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
        $product->whatToBring()->attach($whatToBringIds);
    }

    private function attachRelatedPackages(Product $product, array $packageIds)
    {
        $product->relatedPackages()->attach($packageIds, ['relation_type' => 'related_package']);
    }

    private function attachRelatedBlogs(Product $product, array $blogIds)
    {
        $product->relatedBlogs()->attach($blogIds);
    }

    private function attachTags(Product $product, array $tagIds)
    {
        $product->tags()->attach($tagIds);
    }

    private function createHighlights(Product $product, array $highlights)
    {
        foreach ($highlights as $highlightData) {
            $highlight = ProductHighlight::create([
                'product_id' => $product->id,
                'title' => $highlightData['title'],
                'description' => $highlightData['description'],
                'order' => $highlightData['order'],
            ]);

            if (isset($highlightData['highlightFiles']) && is_array($highlightData['highlightFiles'])) {
                $highlight->syncFiles($highlightData['highlightFiles']);
            }
        }
    }

    public function validatePackageData(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:150',
            'slug' => 'required|string|max:200|unique:products',
            'short_code' => 'required|string|max:50',
            'type' => 'required|string|in:package',
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
            'night' => 'required|integer',

            'faqs' => 'required|array',
            'faqs.*.question' => 'required|string',
            'faqs.*.answer' => 'required|string',
            'faqs.*.order' => 'required|integer',

            'itinerary' => 'required|array',
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
            'meta.metaTitle' => 'required|string',
            'meta.keywords' => 'required|array',
            'meta.metaDescription' => 'required|string',

            'files' => 'required|array',
            'files.featuredImages' => 'nullable|array',
            'files.featuredImages.*' => 'exists:files,id',
            'files.galleryImages' => 'nullable|array',
            'files.galleryImages.*' => 'exists:files,id',
            'files.locationCover' => 'nullable|exists:files,id',
            'files.howToGet' => 'nullable|exists:files,id',
        ]);
    }
}
