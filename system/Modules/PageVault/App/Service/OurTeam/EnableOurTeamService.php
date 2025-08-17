<?php

namespace Modules\PageVault\App\Service\OurTeam;

use Illuminate\Support\Facades\DB;
use Modules\PageVault\App\Models\OurTeam;

class EnableOurTeamService
{

    public function enableTeam($id)
    {
        try {
            DB::beginTransaction();
            $ourTeam = OurTeam::findOrFail($id);

            if (!$ourTeam) {
                throw new \Exception('Our Team Not found');
            }

            $ourTeam->update([
                'is_active' => 1,
            ]);

            DB::commit();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}
