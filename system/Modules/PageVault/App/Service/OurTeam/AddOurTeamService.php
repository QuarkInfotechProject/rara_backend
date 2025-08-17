<?php

namespace Modules\PageVault\App\Service\OurTeam;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\PageVault\App\Models\OurTeam;

class AddOurTeamService
{

    public function addOurTeam($data)
    {
        try {
            DB::beginTransaction();

            $validatedData = Validator::make($data, [
                'name' => 'required',
                'position' => 'required|string|max:255',
                'linkedIn_link' => 'required|string|max:500',
                'order' => 'required',
                'bio' => 'nullable',
                'is_active' => 'required'
            ])->validate();

            $validatedData = [
                "name" => $validatedData['name'],
                "position" => $validatedData['position'],
                "linkedIn_link" => $validatedData['linkedIn_link'],
                "order" => $validatedData['order'],
                "is_active" => $validatedData['is_active'],
                "bio" => $validatedData['bio']
            ];

            OurTeam::create($validatedData);

            DB::commit();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }

}
