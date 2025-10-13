<?php

namespace Modules\PageVault\App\Service\CarRental;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\PageVault\App\Models\CarRental;

class ChangeStatusCarRentalService
{
    public function changeStatus($data)
    {
        try {
            DB::beginTransaction();
            $validatedData = Validator::make($data, [
                'id' => 'required',
                'status' => 'required|in:new,processing,contacted,completed,on-hold,cancelled',
            ])->validate();

            $cta = CarRental::findOrFail($validatedData['id']);

            if (!$cta) {
                throw new \Exception('Car Rental not found');
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
