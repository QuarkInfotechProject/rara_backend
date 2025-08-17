<?php

namespace Modules\Sales\App\Http\Service\Admin\Booking;

use Illuminate\Support\Facades\DB;
use Modules\Sales\App\Models\Agent;

class ListAgentForSelectService
{

    public function getAgentListForSelect(): array
    {
        try {
            return Agent::select(['id', DB::raw("CONCAT(firstname, ' ', lastname) AS name")])
                ->get()
                ->toArray();

        } catch (\Exception $e) {
            throw $e;
        }
    }
}
