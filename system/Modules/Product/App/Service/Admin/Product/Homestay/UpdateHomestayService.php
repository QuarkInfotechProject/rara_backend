<?php

namespace Modules\Product\App\Service\Admin\Product\Homestay;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Modules\Product\App\Models\Product;
use Modules\Shared\App\Events\AdminUserActivityLogEvent;
use Modules\Shared\Constant\ActivityTypeConstant;

class UpdateHomestayService
{
    public function updateHomestay($data, $ipAddress)
    {
        $validator = $this->validateUpdateHomestayData($data);

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
                'display_homepage' => $data['display_homepage'],
            ]);

            $this->updateHosts($product, $data['hosts']);
            $this->updateProductPrices($product, $data['prices']);
            $this->updateFaqs($product, $data['faqs']);
            $this->updateHighlights($product, $data['highlights']);
            $this->updateAmenities($product, $data['amenity']);
            $this->updateIncluded($product, $data['included']);
            $this->updateNearbyHomestays($product, $data['nearby_homestay']);
            $this->updateRelatedBlogs($product, $data['related_blogs']);
            $this->updateTags($product, $data['tags']);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }

        Event::dispatch(new AdminUserActivityLogEvent(
            "{$product->name} homestay has been updated by: " . Auth::user()->name,
            Auth::id(),
            ActivityTypeConstant::HOMESTAY_UPDATED,
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

    private function updateHosts(Product $product, array $hosts)
    {
        $existingHostIds = $product->homestayHosts->pluck('id')->toArray();
        $updatedHostIds = [];

        foreach ($hosts as $hostData) {
            $host = null;
            if (isset($hostData['id'])) {
                $host = $product->homestayHosts()->find($hostData['id']);
                if ($host) {
                    $host->update([
                        'fullname' => $hostData['fullname'],
                        'description' => $hostData['description'],
                    ]);
                    $updatedHostIds[] = $host->id;
                }
            }

            if (!$host) {
                $host = $product->homestayHosts()->create([
                    'fullname' => $hostData['fullname'],
                    'description' => $hostData['description'],
                ]);
                $updatedHostIds[] = $host->id;
            }

            if (isset($hostData['hostFiles']) && is_array($hostData['hostFiles'])) {
                $host->syncFiles($hostData['hostFiles']);
            }
        }

        $hostsToDelete = array_diff($existingHostIds, $updatedHostIds);
        foreach ($hostsToDelete as $hostId) {
            $hostToDelete = $product->homestayHosts()->find($hostId);
            if ($hostToDelete) {
                $hostToDelete->delete();
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

    private function updateAmenities(Product $product, array $amenityIds)
    {
        $product->amenities()->sync($amenityIds);
    }

    private function updateIncluded(Product $product, array $includedIds)
    {
        $product->included()->sync($includedIds);
    }

    private function updateNearbyHomestays(Product $product, array $homestayIds)
    {
        $product->nearbyHomestays()->detach();
        foreach ($homestayIds as $id) {
            $product->nearbyHomestays()->attach($id, ['relation_type' => 'nearby_homestay']);
        }
    }

    private function updateRelatedBlogs(Product $product, array $blogIds)
    {
        $product->relatedBlogs()
            ->sync($blogIds)
        ;
    }

    private function updateTags(Product $product, array $tagIds)
    {
        $product->tags()->sync($tagIds);
    }

    public function validateUpdateHomestayData(array $data)
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
            'display_order' => 'nullable|integer',
            'youtube_link' => 'nullable',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'location' => 'nullable|string',
            'status' => 'required|string|in:draft,published,archived',
            'how_to_get' => 'nullable|string',
            'cornerstone' => 'boolean',
            'region' => 'nullable|string',
            'is_occupied' => 'boolean',
            'max_occupant' => 'integer|min:0',

            'hosts' => 'nullable|array',
            'hosts.*.id' => 'nullable|exists:product_homestay_hosts,id',
            'hosts.*.fullname' => 'required|string',
            'hosts.*.description' => 'required|string',
            'hosts.*.hostFiles' => 'nullable|array',
            'hosts.*.hostFiles.profileImage' => 'nullable|exists:files,id',

            'faqs' => 'nullable|array',
            'faqs.*.id' => 'nullable|exists:product_faqs,id',
            'faqs.*.question' => 'required|string',
            'faqs.*.answer' => 'required|string',
            'faqs.*.order' => 'required|integer',

            'highlights' => 'nullable|array',
            'highlights.*.id' => 'nullable|exists:product_highlights,id',
            'highlights.*.title' => 'required|string',
            'highlights.*.description' => 'required|string',
            'highlights.*.order' => 'required|integer',
            'highlights.*.highlightFiles' => 'nullable|array',
            'highlights.*.highlightFiles.highlightImage' => 'nullable|exists:files,id',

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
            'files.hostCover' => 'nullable|exists:files,id',
            'files.howToGet' => 'nullable|exists:files,id',
        ]);
    }
}
