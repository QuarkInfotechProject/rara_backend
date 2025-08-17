<?php

namespace Modules\PageVault\App\Service\OurTeam;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Modules\PageVault\App\Models\OurTeam;

class EditOurTeamService
{

    public function updateOurTeam($data)
    {
        try {
            DB::beginTransaction();
            $validatedData = Validator::make($data, [
                'id' => 'required',
                'name' => 'required',
                'position' => 'required|string|max:255',
                'linkedIn_link' => 'required|string|max:500',
                'order' => 'required',
                'is_active' => 'required',
                'bio' => 'nullable',
            ])->validate();

            $ourTeam = OurTeam::findOrFail($validatedData['id']);

            if (!$ourTeam) {
                throw new \Exception('Our Team Not found');
            }

            $ourTeam->update([
                'name' => $validatedData['name'],
                'position' => $validatedData['position'],
                'linkedIn_link' => $validatedData['linkedIn_link'],
                'order' => $validatedData['order'],
                'is_active' => $validatedData['is_active'],
                'bio' => $validatedData['bio'],
            ]);

            DB::commit();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}
