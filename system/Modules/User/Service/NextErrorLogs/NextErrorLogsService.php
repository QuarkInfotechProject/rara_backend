<?php

namespace Modules\User\Service\NextErrorLogs;

use Modules\User\App\Models\NextErrorLog;

class NextErrorLogsService
{

    public function addNextErrorLogs($data)
    {
        $logData = [
            'name' => $data['name'] ?? null,
            'stack' => $data['stack'] ?? null,
            'message' => $data['message'] ?? null,
            'source' => $data['source'] ?? null,
        ];

        NextErrorLog::create($logData);
    }

}
