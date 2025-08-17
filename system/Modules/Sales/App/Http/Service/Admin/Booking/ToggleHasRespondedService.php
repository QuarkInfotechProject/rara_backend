<?php

namespace Modules\Sales\App\Http\Service\Admin\Booking;

use Modules\Sales\App\Models\Booking;

class ToggleHasRespondedService
{
    public function toggleHasResponded(int $bookingId): bool
    {
        $booking = Booking::find($bookingId);

        if ($booking) {
            $booking->has_responded = !$booking->has_responded;
            return $booking->save();
        }
        return false;
    }


}
