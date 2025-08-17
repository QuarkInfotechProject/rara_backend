<?php

namespace Modules\Menu\Service;

use Modules\Menu\App\Models\Menu;
use Modules\Shared\Exception\Exception;
use Modules\Shared\StatusCode\ErrorCode;

class MenuChangeStatusService
{
    function changeStatus(int $id)
    {
        $menu = Menu::find($id);

        if (!$menu) {
            throw new Exception('Menu not found.', ErrorCode::NOT_FOUND);
        }

        $menu->update(['status' => !$menu->status]);
    }
}
