<?php

namespace Modules\Product\App\Service\Admin\Product;

use Illuminate\Http\Request;
use Modules\Product\App\Models\Product;

class ProductDropdownService 
{
    public function handle(Request $request) {
        $search = $request->query('search');

        return Product::query()
            ->select('id', 'name')
            ->where('status', 'published')
            ->when($search, function($query) use($search) {
                $query->where('name', 'like', '%' . $search . '%');
            })
            ->orderBy('name')
            ->get();
    }
}