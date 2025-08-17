<?php

namespace Modules\Product\App\Service\Admin\Product;

use Illuminate\Support\Facades\DB;
use Modules\Product\App\Models\Manager;

class ListManagerForSelectService
{

    public function listAllManagerForSelect()
    {
        return Manager::where('status', 1)
            ->select('id', DB::raw("CONCAT(firstname, ' ', lastname) as fullname"))
            ->get()
            ->map(function ($manager) {
                return [
                    'id' => $manager->id,
                    'fullname' => $manager->fullname
                ];
            });
    }

}
