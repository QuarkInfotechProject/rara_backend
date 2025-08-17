<?php

namespace Modules\PageVault\App\Service\WhyUs;

use Modules\PageVault\App\Models\WhyUs;

class ListWhyUsService
{
    public function getWhyUsList()
    {
        return WhyUs::select('id', 'title', 'order')->get();
    }

}
