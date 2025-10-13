<?php

namespace Modules\PageVault\App\Service\CarRental;

use Modules\PageVault\App\Models\CarRental;

class GetCarRentalDetailService
{
    public function getDetailByIdService($id)
    {
        $carRental = CarRental::where('id', $id)->first();

        if (!$carRental) {
            throw new \Exception('Car rental not found');
        }

        return [
            'user_name' => $carRental->user_name,
            'email' => $carRental->email,
            'contact' => $carRental->contact,
            'max_people' => $carRental->max_people,
            'pickup_address' => $carRental->pickup_address,
            'destination_address' => $carRental->destination_address,
            'pickup_time' => $carRental->pickup_time,
            'message' => $carRental->message,
            'status' => $carRental->status,
            'type' => $carRental->type
        ];
    }
}
