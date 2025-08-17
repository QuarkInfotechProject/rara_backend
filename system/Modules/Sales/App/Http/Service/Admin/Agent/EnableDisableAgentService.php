<?php

namespace Modules\Sales\App\Http\Service\Admin\Agent;

use Modules\Sales\App\Models\Agent;

class EnableDisableAgentService
{

    public function disableAgentById(int $id): bool
    {
        $agent = Agent::find($id);

        if (!$agent)
        {
            throw new \Exception('Agent Not Found');
        }

            $agent->is_active = false;
            return $agent->save();
    }
}
