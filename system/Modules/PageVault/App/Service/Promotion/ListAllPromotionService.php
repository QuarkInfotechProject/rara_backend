<?php

namespace Modules\PageVault\App\Service\Promotion;

use Modules\PageVault\App\Models\Promotion;

class ListAllPromotionService
{

    public function getPromotionList()
    {
        return Promotion::select('id','name', 'is_active', 'placement_place')->get();
    }
}
