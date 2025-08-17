<?php

namespace Modules\Sales\App\Http\Service\Admin\Booking;

use Illuminate\Support\Facades\DB;
use Modules\Product\App\Models\Product;

class ListAllProductForSelectService
{

    public function getAllProductListForSelect(): array
    {
        try {
            return Product::select(['id', 'name'])
                ->get()
                ->toArray();

        } catch (\Exception $e) {
            throw $e;
        }
    }
}
