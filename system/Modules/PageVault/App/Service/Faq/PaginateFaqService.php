<?php

namespace Modules\PageVault\App\Service\Faq;

use Modules\PageVault\App\Models\Faq;


class PaginateFaqService
{
    public function listOurTeam($filters)
    {
        $query = Faq::query()
            ->select('id', 'question', 'answer', 'category', 'order')
            ->orderBy('order', 'asc');

        if (isset($filters['category'])) {
            $query->where('category', $filters['category']);
        }

        return $query->paginate(15);
    }

}
