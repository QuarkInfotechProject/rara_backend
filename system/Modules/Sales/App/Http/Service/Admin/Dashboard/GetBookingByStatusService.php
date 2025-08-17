<?php

namespace Modules\Sales\App\Http\Service\Admin\Dashboard;

use Carbon\Carbon;
use Modules\Sales\App\Models\Booking;

class GetBookingByStatusService
{

    public function getBookingsByStatus(string $status = 'pending', ?string $startDate = null, ?string $endDate = null): array
    {
        $query = Booking::query();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        $totalBookings = $query->where('status', $status)->count();
        $todayBookings = $query->whereDate('created_at', Carbon::today())->count();
        $weeklyBookings = $query->whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count();
        $monthlyBookings = $query->whereMonth('created_at', Carbon::now()->month)->count();

        return [
            'total' => $totalBookings,
            'today' => $todayBookings,
            'weekly' => $weeklyBookings,
            'monthly' => $monthlyBookings,
            'status' => $status
        ];
    }
}
