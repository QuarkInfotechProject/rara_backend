<?php

namespace Modules\PageVault\App\Service\Cta;

use Modules\PageVault\App\Models\Cta;

class PaginateCtaByCategoryService
{

    public function paginateFaq($filters)
    {
        $query = Cta::query()
            ->select('id', 'fullname', 'email', 'phone_number', 'status', 'type')
            ->orderBy('created_at', 'desc');

        if (isset($filters['type'])) {
            $query->where('type', $filters['type']);
        }

        return $query->paginate();
    }

}
