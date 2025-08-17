<?php

namespace Modules\Product\App\Service\User;

use Illuminate\Support\Facades\Auth;
use Modules\Product\App\Models\SavedProduct;

class WishlistProductService
{

    public function toggleSavedProduct($productId)
    {
        $user = Auth::user();

        $savedProduct = SavedProduct::where('user_id', $user->id)
            ->where('product_id', $productId)
            ->first();

        if ($savedProduct) {
            $savedProduct->delete();
            return 'Product has been removed from wishlist.' ;
        } else {
            SavedProduct::create([
                'user_id' => $user->id,
                'product_id' => $productId,
            ]);
            return 'Product has been added to wishlist successfully.';
        }
    }
}
