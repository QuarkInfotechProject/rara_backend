<?php

namespace Modules\PageVault\App\Service\Cta;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\PageVault\App\Models\Cta;

class AddCtaService
{

    public function addCta($data)
    {
        try {
            DB::beginTransaction();

            $validatedData = Validator::make($data, [
                'fullname' => 'required|string',
                'email' => 'required|string|max:255',
                'phone_number' => 'string',
                'description' => 'required',
                'type' => 'required|in:contact,volunteer,partner,host',
            ])->validate();

            $validatedData = [
                "fullname" => $validatedData['fullname'],
                "email" => $validatedData['email'],
                "description" => $validatedData['description'],
                "phone_number" => $validatedData['phone_number'],
                "type" => $validatedData['type'],
                "status" => 'new',
            ];

            Cta::create($validatedData);

            DB::commit();
        } catch (\Exception $exception) {
            throw $exception;
        }

    }

}
