<?php

namespace Modules\PageVault\App\Service\Faq;

use Illuminate\Support\Facades\DB;
use Modules\PageVault\App\Models\Faq;

class DeleteFaqService
{


    public function deleteFaq($id)
    {
        try {
            DB::beginTransaction();
            $faqs = Faq::find($id);

            if (!$faqs) {
                throw new \Exception('Faqs Not found');
            }
            $faqs->delete();

            DB::commit();
        } catch (\Exception $exception) {
            throw $exception;
        }

    }

}
