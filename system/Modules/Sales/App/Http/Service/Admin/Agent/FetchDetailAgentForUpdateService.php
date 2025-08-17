<?php

namespace Modules\Sales\App\Http\Service\Admin\Agent;

use Modules\Sales\App\Models\Agent;

class FetchDetailAgentForUpdateService
{

    public function getAgentDetailById($id)
    {
        return Agent::select([
            'id',
            'firstname',
            'lastname',
            'email',
            'phone',
            'company',
            'website',
            'homestay_margin',
            'experience_margin',
            'package_margin',
            'pan_no',
            'address',
            'city',
            'country',
            'postal_code',
            'contract_start_date',
            'contract_end_date',
            'bank_name',
            'bank_account_number',
            'bank_ifsc_code',
            'notes',
            'is_active',
            'created_at',
            'updated_at'
        ])->find($id);
    }
}
