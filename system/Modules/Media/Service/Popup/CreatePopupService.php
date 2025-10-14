<?php

namespace Modules\Media\Service\Popup;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Validator;
use Modules\Media\App\Models\Popup;
use Modules\Shared\App\Events\AdminUserActivityLogEvent;
use Modules\Shared\Constant\ActivityTypeConstant;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;

class CreatePopupService
{
    public function createPopup(array $data, string $ipAddress)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255|unique:popups,slug',
            'description' => 'nullable|string',
            'status' => 'nullable|boolean',
            'files.popupImage' => 'nullable|exists:files,id',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $validated = $validator->validated();

        $popup = Popup::create([
            'name' => $validated['name'],
            'slug' => $data['slug'] ?? Str::slug($data['name']),
            'description' => $validated['description'] ?? null,
            'status' => $validated['status'] ?? false,
        ]);

        if (!empty($validated['files']['popupImage'])) {
            $popup->syncFiles([
                'popupImage' => $validated['files']['popupImage']
            ]);
        }

        Event::dispatch(new AdminUserActivityLogEvent(
            "Popup '{$popup->name}' has been created by: " . Auth::user()->name,
            Auth::id(),
            ActivityTypeConstant::POPUP_CREATED,
            $ipAddress
        ));

        return $popup;
    }
}
