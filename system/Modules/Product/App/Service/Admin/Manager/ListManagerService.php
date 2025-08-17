<?php

namespace Modules\Product\App\Service\Admin\Manager;

use Modules\Product\App\Models\Manager;

class ListManagerService
{

    public function getManagersList()
    {
        return Manager::select('firstname', 'lastname', 'description', 'id', 'email', 'phone_number')
            ->orderBy('firstname')
            ->get();
    }
}
