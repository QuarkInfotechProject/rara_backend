<?php

namespace Modules\PageVault\App\Service\Dashboard;

use Illuminate\Support\Facades\DB;
use Modules\PageVault\App\Models\Cta;

class GetCtaStatsService
{
    public function getCtaStats(): array
    {
        $stats = DB::table('ctas')
            ->select(
                DB::raw('COUNT(*) as total_ctas'),
                DB::raw('SUM(CASE WHEN status = "' . Cta::STATUS_NEW . '" THEN 1 ELSE 0 END) as new_count'),
                DB::raw('SUM(CASE WHEN status = "' . Cta::STATUS_PROCESSING . '" THEN 1 ELSE 0 END) as processing_count'),
                DB::raw('SUM(CASE WHEN status = "' . Cta::STATUS_CONTACTED . '" THEN 1 ELSE 0 END) as contacted_count'),
                DB::raw('SUM(CASE WHEN status = "' . Cta::STATUS_COMPLETED . '" THEN 1 ELSE 0 END) as completed_count'),
                DB::raw('SUM(CASE WHEN status = "' . Cta::STATUS_ONHOLD . '" THEN 1 ELSE 0 END) as onhold_count'),
                DB::raw('SUM(CASE WHEN status = "' . Cta::STATUS_CANCELLED . '" THEN 1 ELSE 0 END) as cancelled_count'),

                DB::raw('SUM(CASE WHEN type = "' . Cta::TYPE_CONTACT . '" THEN 1 ELSE 0 END) as contact_count'),
                DB::raw('SUM(CASE WHEN type = "' . Cta::TYPE_VOLUNTEER . '" THEN 1 ELSE 0 END) as volunteer_count'),
                DB::raw('SUM(CASE WHEN type = "' . Cta::TYPE_PARTNER . '" THEN 1 ELSE 0 END) as partner_count'),
                DB::raw('SUM(CASE WHEN type = "' . Cta::TYPE_HOST . '" THEN 1 ELSE 0 END) as host_count'),

                DB::raw('COUNT(DISTINCT email) as unique_contacts')
            )
            ->first();

        return [
            'total' => [
                'all_ctas' => $stats->total_ctas,
                'unique_contacts' => $stats->unique_contacts
            ],
            'status_counts' => [
                'new' => $stats->new_count,
                'processing' => $stats->processing_count,
                'contacted' => $stats->contacted_count,
                'completed' => $stats->completed_count,
                'onhold' => $stats->onhold_count,
                'cancelled' => $stats->cancelled_count
            ],
            'type_counts' => [
                'contact' => $stats->contact_count,
                'volunteer' => $stats->volunteer_count,
                'partner' => $stats->partner_count,
                'host' => $stats->host_count
            ]
        ];
    }

}
