<?php

namespace Modules\PageVault\App\Service\Faq;

use Modules\PageVault\App\Models\Faq;

class GetDetailFaqService
{

    public function getFaqDetailById($id)
    {
        $faq = Faq::where('id', $id)->first();

        if (!$faq) {
            throw new \Exception('Faq Not found');
        }

        return [
            'question' => $faq->question,
            'answer' => $faq->answer,
            'category' => $faq->category,
            'order' => $faq->order,
            'is_active' => $faq->is_active
        ];


    }
}
