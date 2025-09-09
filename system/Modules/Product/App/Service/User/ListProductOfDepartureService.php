<?php

namespace Modules\Product\App\Service\User;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Modules\Product\App\Models\Product;
use Carbon\Carbon;

class ListProductOfDepartureService
{
    public function getProductsByDeparture(?string $search = null)
    {
        try {
            $query = $this->getBaseQuery();

            $today = Carbon::today();

            $query->whereHas('departures', function ($q) use ($today) {
                $q->whereDate('departure_from', '>=', $today);
            });

            if (!empty($search)) {
                $query->where('products.name', 'like', '%' . $search . '%');
            }

            $products = $query->get();

            return $this->transformProducts($products);

        } catch (\Exception $exception) {
            throw $exception;
        }
    }
    private function getBaseQuery(): Builder
    {
        $query = Product::query()
            ->select([
                'products.id',
                'products.name',
                'products.slug',
                'products.type',
                'products.max_occupant',
            ])
            ->where('products.status', 'published')
            ->where('products.is_occupied', false)
//            ->where('products.display_homepage', false)
            ->orderByRaw('CAST(products.display_order AS SIGNED) ASC')
            ->with([
                'tags:id,name,slug',
                'prices:product_id,number_of_people,original_price_usd,discounted_price_usd',
                'departures:id,product_id,departure_from,departure_to,departure_per_price,max_team_members',
            ]);


        $user = Auth::guard('user')->user();

        if ($user) {
            $userId = $user->id;
            $query->leftJoin('saved_products', function ($join) use ($userId) {
                $join->on('products.id', '=', 'saved_products.product_id')
                    ->where('saved_products.user_id', '=', $userId);
            })
                ->addSelect(DB::raw('CASE WHEN saved_products.id IS NOT NULL THEN TRUE ELSE FALSE END AS wishlist'));
        } else {
            $query->addSelect(DB::raw('FALSE AS wishlist'));
        }

        return $query;
    }

    private function transformProducts($products)
    {
        $today = \Carbon\Carbon::today();

        return $products->map(function ($product) use ($today) {

            $product->tags = $product->tags->map(function ($tag) {
                return [
                    'id' => $tag->id,
                    'name' => $tag->name,
                    'slug' => $tag->slug,
                ];
            })->values()->toArray();

            $product->prices = $product->prices->map(function ($price) {
                return [
                    'number_of_people' => $price->number_of_people,
                    'original_price_usd' => $price->original_price_usd,
                    'discounted_price_usd' => $price->discounted_price_usd,
                ];
            })->values()->toArray();
            $product->departures = ($product->departures ?? collect())
                ->filter(fn($dep) => \Carbon\Carbon::parse($dep->departure_from)->greaterThanOrEqualTo($today))
                ->map(fn($dep) => [
                    'id'    => $dep->id,
                    'from'  => $dep->departure_from,
                    'to'    => $dep->departure_to,
                    'price' => $dep->departure_per_price,
                    'max_team_members' => $dep->max_team_members,
                ])
                ->values()
                ->toArray();

            return $product;
        });
    }

    private function getMediaFiles($product, $type, $multiple = false)
    {
        $baseImageFiles = $product->filterFiles($type)->get();

        if ($multiple) {
            return $baseImageFiles->map(function ($file) {
                return $file->path . '/' . $file->temp_filename;
            })->toArray();
        } else {
            $baseImage = $baseImageFiles->map(function ($file) {
                return $file->path . '/' . $file->temp_filename;
            })->first();

            return $baseImage ?? '';
        }
    }
}
