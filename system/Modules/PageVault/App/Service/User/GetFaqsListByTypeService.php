<?php

namespace Modules\PageVault\App\Service\User;

use Modules\PageVault\App\Models\Faq;

class GetFaqsListByTypeService
{

    public function listFaqs($category)
    {
        $query = Faq::query()
            ->select('question', 'answer', 'category')
            ->orderBy('order', 'asc');

        if (isset($category)) {
            $query->where('category', $category);
        }

        $faqs = $query->get();

        return $faqs->toArray();
    }

}
