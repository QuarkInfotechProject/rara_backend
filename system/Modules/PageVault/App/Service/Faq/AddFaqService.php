<?php

namespace Modules\PageVault\App\Service\Faq;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\PageVault\App\Models\Faq;
use phpDocumentor\Reflection\Types\Boolean;

class AddFaqService
{
    public function addFaq($data)
    {
        try {
            DB::beginTransaction();

            $validatedData = Validator::make($data, [
                'question' => 'required',
                'answer' => 'required|string|max:255',
                'category' => 'required|in:safety,volunteer,partner,host,impact,inquery,cancellation',
                'order' => 'required',
            ])->validate();

            $validatedData = [
                "question" => $validatedData['question'],
                "answer" => $validatedData['answer'],
                "order" => $validatedData['order'],
                "category" => $validatedData['category'],
                "is_active" => 1
            ];

            Faq::create($validatedData);

            DB::commit();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

}
