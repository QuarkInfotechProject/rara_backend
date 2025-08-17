<?php

namespace Modules\Product\App\Service\Admin\Product\Homestay;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\Product\App\Models\Product;
use Modules\Product\App\Models\ProductFaq;
use Modules\Product\App\Models\ProductHighlight;
use Modules\Product\App\Models\ProductHomestayHost;
use Modules\Shared\App\Events\AdminUserActivityLogEvent;
use Modules\Shared\Constant\ActivityTypeConstant;

class CreateHomestayService
{
    public function createHomestay($data, $ipAddress)
    {
        $validator = $this->validateProductData($data);

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
                'manager_id' => $data['manager_id'],
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
                'region' => $data['region'],
                'is_occupied' => $data['is_occupied'],
                'max_occupant' => $data['max_occupant'],
                'impact' => $data['impact'],
                'display_homepage' => $data['display_homepage'],
            ]);
            $this->createProductPrices($product, $data['prices']);
            $this->createHosts($product, $data['hosts']);
            $this->createFaqs($product, $data['faqs']);
            $this->createHighlights($product, $data['highlights']);
            $this->attachAmenities($product, $data['amenity']);
            $this->attachIncluded($product, $data['included']);
            $this->attachNearbyHomestays($product, $data['nearby_homestay']);
            $this->attachRelatedBlogs($product, $data['related_blogs']);
            $this->attachTags($product, $data['tags']);

            DB::commit();
        } catch (\Exception $exception) {

            DB::rollBack();
            throw $exception;
        }

        Event::dispatch(new AdminUserActivityLogEvent(
                "{$product->name} homestay has been added by: " . Auth::user()->name,
                Auth::id(),
                ActivityTypeConstant::HOMESTAY_ADDED,
                $ipAddress)
        );
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

    private function createHosts(Product $product, array $hosts)
    {
        foreach ($hosts as $hostData) {
            $host = ProductHomestayHost::create([
                'product_id' => $product->id,
                'fullname' => $hostData['fullname'],
                'description' => $hostData['description'],
            ]);

            if (isset($hostData['hostFiles']) && is_array($hostData['hostFiles'])) {
                $host->syncFiles($hostData['hostFiles']);
            }
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

    private function attachAmenities(Product $product, array $amenityIds)
    {
        $product->amenities()->attach($amenityIds);
    }

    private function attachIncluded(Product $product, array $includedIds)
    {
        $product->included()->attach($includedIds);
    }

    private function attachNearbyHomestays(Product $product, array $homestayIds)
    {
        $product->relatedProducts()->attach($homestayIds, ['relation_type' => 'nearby_homestay']);
    }

    private function attachRelatedBlogs(Product $product, array $blogIds)
    {
        $product->relatedBlogs()->attach($blogIds);
    }

    private function attachTags(Product $product, array $tagIds)
    {
        $product->tags()->attach($tagIds);
    }

    public function validateProductData(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:150',
            'slug' => 'required|string|max:200|unique:products',
            'short_code' => 'required|string|max:50',
            'type' => 'required|string|max:20',
            'tagline' => 'nullable|string|max:255',
            'manager_id' => 'nullable|exists:managers,id',
            'short_description' => 'nullable|string',
            'description' => 'nullable|string',
            'prices' => 'required|array|min:1',
            'prices.*.number_of_people' => 'required|integer|min:1',
            'prices.*.original_price_usd' => 'required|numeric|min:0',
            'prices.*.discounted_price_usd' => 'required|numeric|min:0',
            'variable_prices' => 'required_if:has_variable_pricing,true|array',
            'variable_prices.*.number_of_people' => 'required_if:has_variable_pricing,true|integer|min:1',
            'variable_prices.*.price' => 'required_if:has_variable_pricing,true|numeric|min:0',
            'display_order' => 'nullable|string',
            'youtube_link' => 'nullable|string',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'location' => 'nullable|string',
            'status' => 'required|string',
            'how_to_get' => 'nullable|string',
            'cornerstone' => 'boolean',
            'region' => 'required|string',
            'impact' => 'nullable|string',
            'is_occupied' => 'boolean',
            'max_occupant' => 'integer|min:0',
            'display_homepage' => 'boolean',

            'hosts' => 'required|array',
            'hosts.*.fullname' => 'required|string',
            'hosts.*.description' => 'required|string',
            'hosts.*.hostFiles' => 'nullable|array',

            'faqs' => 'required|array',
            'faqs.*.question' => 'required|string',
            'faqs.*.answer' => 'required|string',
            'faqs.*.order' => 'required|integer',

            'highlights' => 'required|array',
            'highlights.*.title' => 'required|string',
            'highlights.*.description' => 'required|string',
            'highlights.*.order' => 'required|integer',
            'highlights.*.highlightFiles' => 'nullable|array',

            'amenity' => 'required|array',
            'amenity.*' => 'exists:amenities,id',

            'included' => 'required|array',
            'included.*' => 'exists:amenities,id',

            'nearby_homestay' => 'nullable|array',
            'nearby_homestay.*' => 'exists:products,id',

            'related_blogs' => 'required|array',
            'related_blogs.*' => 'exists:blogs,id',

            'tags' => 'required|array',
            'tags.*' => 'exists:tags,id',
        ]);
    }


}
