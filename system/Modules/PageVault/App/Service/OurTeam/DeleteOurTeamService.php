<?php

namespace Modules\PageVault\App\Service\OurTeam;

use Illuminate\Support\Facades\DB;
use Modules\PageVault\App\Models\OurTeam;

class DeleteOurTeamService
{


    public function deleteTeam($id)
    {
        try {
            DB::beginTransaction();
            $ourTeam = OurTeam::find($id);

            if (!$ourTeam) {
                throw new \Exception('Our Team Not found');
            }

            DB::table('model_files')
                ->where('model_type', get_class($ourTeam))
                ->where('model_id', $ourTeam->id)
                ->delete();

            $ourTeam->delete();

            DB::commit();
        } catch (\Exception $exception) {
            throw $exception;
        }
    }
}
