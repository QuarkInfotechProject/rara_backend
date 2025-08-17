<?php

namespace Modules\SystemConfiguration\Service\Setting;

use Illuminate\Support\Str;
use Modules\SystemConfiguration\App\Models\SystemConfig;

class SettingCreateService
{
    function create(array $systemConfig)
    {
        SystemConfig::firstOrCreate(
            ['name' => $systemConfig['name']],
            [
                'uuid' => Str::uuid()->toString(),
                'title' => $systemConfig['title'],
                'value' => $systemConfig['value'],
                'section' => $systemConfig['section']
            ]
        );
    }
}
