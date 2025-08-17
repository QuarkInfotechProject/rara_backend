<?php

namespace Modules\PageVault\App\Service\Cta;

use Illuminate\Support\Facades\DB;
use Modules\PageVault\App\Models\Cta;
use Modules\PageVault\App\Models\Faq;

class DeleteCtaService
{

    public function deleteCta($id)
    {
        try {
            DB::beginTransaction();
            $faqs = Cta::find($id);

            if (!$faqs) {
                throw new \Exception('Ctas Not found');
            }
            $faqs->delete();

            DB::commit();
        } catch (\Exception $exception) {
            throw $exception;
        }

    }

}
