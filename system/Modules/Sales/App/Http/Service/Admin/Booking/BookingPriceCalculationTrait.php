<?php

namespace Modules\Sales\App\Http\Service\Admin\Booking;

trait BookingPriceCalculationTrait
{

    private function calculateTotalAmount($basePrice, $additionalProductsAmount, $additionalCharges, $discounts, $taxes, $totalPeople)
    {
        $subtotal = $basePrice + $additionalProductsAmount + $additionalCharges - $discounts;
        $total = $subtotal + ($subtotal * ($taxes / 100));

        return round($total, 2);
    }

}
