<?php

namespace Modules\Sales\App\Http\Service\Admin\Dashboard;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Modules\Sales\App\Models\Booking;

class BookingInsightsService
{

    public function getInsights(?string $startDate = null, ?string $endDate = null): array
    {
        $query = Booking::query();

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        // Product performance
        $topProducts = $query->select('product_name', DB::raw('count(*) as total'))
            ->groupBy('product_name')
            ->orderByDesc('total')
            ->limit(5)
            ->get();


        // Agent performance
        $topAgents = DB::table('bookings as b')
            ->join('agents as a', 'b.agent_id', '=', 'a.id')
            ->select(
                'a.id',
                'a.firstname',
                DB::raw('COUNT(*) as total_bookings'),
                DB::raw('COUNT(CASE WHEN b.status = "completed" THEN 1 END) as completed_bookings'),
            )
            ->whereNotNull('b.agent_id')
            ->when($startDate && $endDate, function($query) use ($startDate, $endDate) {
                return $query->whereBetween('b.created_at', [$startDate, $endDate]);
            })
            ->groupBy('a.id', 'a.firstname')
            ->orderByDesc('total_bookings')
            ->limit(5)
            ->get();


        // Seasonal trends
        $monthlyTrends = DB::table('bookings')
            ->select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month_number'),
                DB::raw('COUNT(*) as total')
            )
            ->when($startDate && $endDate, function($query) use ($startDate, $endDate) {
                return $query->whereBetween('created_at', [$startDate, $endDate]);
            })
            ->groupBy('year', 'month_number')
            ->orderBy('year', 'desc')
            ->orderBy('month_number', 'desc')
            ->get()
            ->map(function($item) {
                return [
                    'year' => $item->year,
                    'month' => $this->getMonthName($item->month_number),
                    'month_number' => $item->month_number,
                    'total' => $item->total,
                    'label' => $this->getMonthName($item->month_number) . ' ' . $item->year
                ];
            })
            ->values();


        return [
            'top_products' => $topProducts,
            'top_agents' => $topAgents,
            'monthly_trends' => $monthlyTrends
        ];
    }

    private function getMonthName(int $month): string
    {
        return Carbon::create()->month($month)->format('M');
    }

}
