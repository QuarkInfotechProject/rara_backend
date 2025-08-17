<?php

namespace Modules\PageVault\App\Service\Cta;

use Modules\PageVault\App\Models\Cta;

class GetCTADetailService
{

    public function getDetailByIdService($id)
    {
        $cta = Cta::where('id', $id)->first();

        if (!$cta) {
            throw new \Exception('Cta Not found');
        }

        return [
            'fullname' => $cta->fullname,
            'email' => $cta->email,
            'phone_number' => $cta->phone_number,
            'description' => $cta->description,
            'status' => $cta->status,
            'type' => $cta->type
        ];
    }


}
