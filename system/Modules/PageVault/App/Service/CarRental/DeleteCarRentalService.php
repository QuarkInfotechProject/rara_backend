<?php

namespace Modules\PageVault\App\Service\CarRental;

use Illuminate\Support\Facades\DB;
use Modules\PageVault\App\Models\CarRental;

class DeleteCarRentalService
{
    public function deleteCarRental($id)
    {
        try {
            DB::beginTransaction();

            $carRental = CarRental::findOrFail($id);
            $carRental->delete();

            DB::commit();

            return [
                'message' => 'Car rental deleted successfully',
                'id' => $id
            ];
        } catch (\Exception $exception) {
            DB::rollBack();
            throw $exception;
        }
    }
}
