<?php

namespace Modules\PageVault\App\Service\Faq;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\PageVault\App\Models\Faq;
use Modules\PageVault\App\Models\OurTeam;

class EditFaqService
{


    public function editOurFaq($data)
    {
        try {
            DB::beginTransaction();
            $validatedData = Validator::make($data, [
                'id' => 'required',
                'question' => 'required',
                'answer' => 'required|string|max:255',
                'category' => 'required|in:safety,volunteer,partner,host,impact,inquery,cancellation',
                'order' => 'required',
            ])->validate();

            $faq = Faq::findOrFail($validatedData['id']);

            if (!$faq) {
                throw new \Exception('Faq not found');
            }

            $faq->update([
                'question' => $validatedData['question'],
                'answer' => $validatedData['answer'],
                'category' => $validatedData['category'],
                'order' => $validatedData['order'],
            ]);

            DB::commit();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}
