<?php

namespace Modules\Sales\App\Http\Service\Admin\Dashboard;

use Illuminate\Support\Facades\DB;
use Modules\Sales\App\Models\Booking;

class TopCountriesService
{
    public function getTopCountries(?string $startDate = null, ?string $endDate = null): array
    {
        $query = Booking::select('country', DB::raw('count(*) as total'))
            ->groupBy('country')
            ->orderByDesc('total');

        if ($startDate && $endDate) {
            $query->whereBetween('created_at', [$startDate, $endDate]);
        }

        return $query->limit(10)->get()->toArray();
    }
}
