<?php

namespace Modules\Media\Service\Popup;

use Modules\Media\App\Models\Popup;
use Modules\Shared\App\Events\AdminUserActivityLogEvent;
use Modules\Shared\Constant\ActivityTypeConstant;
use Illuminate\Support\Facades\Auth;
use Modules\Shared\Exception\Exception;
use Modules\Shared\StatusCode\ErrorCode;

class DestroyPopupService
{
    public function destroy(string $slug, string $ipAddress)
    {
        $popup = Popup::where('slug', $slug)->first();

        if (!$popup) {
            throw new Exception('Popup not found.', ErrorCode::NOT_FOUND);
        }

        $popup->delete();

        event(new AdminUserActivityLogEvent(
            "Popup '{$popup->name}' has been soft deleted by: " . Auth::user()->name,
            Auth::id(),
            ActivityTypeConstant::POPUP_DESTROYED,
            $ipAddress
        ));
    }
}
