<?php

namespace Modules\PageVault\App\Service\CarRental;

use Modules\PageVault\App\Models\CarRental;

class UpdateCarRentalService
{
    public function updateCarRental(array $data)
    {
        // Fetch the record by ID
        $carRental = CarRental::find($data['id'] ?? null);

        if (!$carRental) {
            throw new \Exception('Car rental not found');
        }

        // Update the fields
        $carRental->update([
            'user_name' => $data['user_name'] ?? $carRental->user_name,
            'email' => $data['email'] ?? $carRental->email,
            'contact' => $data['contact'] ?? $carRental->contact,
            'max_people' => $data['max_people'] ?? $carRental->max_people,
            'pickup_address' => $data['pickup_address'] ?? $carRental->pickup_address,
            'destination_address' => $data['destination_address'] ?? $carRental->destination_address,
            'pickup_time' => $data['pickup_time'] ?? $carRental->pickup_time,
            'message' => $data['message'] ?? $carRental->message,
            'type' => $data['car_type'] ?? $carRental->type, // note your JSON has 'car_type'
            'status' => $data['status'] ?? $carRental->status,
        ]);

        return $carRental;
    }
}
