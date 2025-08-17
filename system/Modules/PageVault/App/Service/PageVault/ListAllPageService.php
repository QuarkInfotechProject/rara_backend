<?php

namespace Modules\PageVault\App\Service\PageVault;

use Modules\PageVault\App\Models\PageVault;

class ListAllPageService
{
    public function listAllPages()
    {
        return PageVault::query()
            ->select('type', 'is_active', 'title', 'slug')
            ->orderBy('created_at', 'desc')
            ->get();
    }

}
