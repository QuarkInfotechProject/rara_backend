<?php

namespace Modules\PageVault\App\Service\Cta;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\PageVault\App\Models\Cta;

class ChangeStatusCtaService
{
    public function changeStatus($data)
    {
        try {
            DB::beginTransaction();
            $validatedData = Validator::make($data, [
                'id' => 'required',
                'status' => 'required|in:new,processing,contacted,completed,on-hold,cancelled',
            ])->validate();

            $cta = Cta::findOrFail($validatedData['id']);

            if (!$cta) {
                throw new \Exception('Faq not found');
            }

            $cta->update([
                'status' => $validatedData['status'],
            ]);

            DB::commit();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}
