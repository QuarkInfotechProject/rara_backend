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

class UpdatePopupService
{
    public function updatePopup(array $data, string $ipAddress)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|boolean',
            'files.popupImage' => 'nullable|exists:files,id',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $validated = $validator->validated();

        $popup = Popup::where('slug', $validated['slug'] ?? '')->first();

        if (!$popup) {
            throw ValidationException::withMessages(['slug' => 'Popup not found.']);
        }

        $popup->update([
            'name' => $validated['name'],
            'slug' => $validated['slug'] ?? Str::slug($validated['name']),
            'description' => $validated['description'] ?? $popup->description,
            'status' => $validated['status'] ?? $popup->status,
        ]);

        if (!empty($validated['files']['popupImage'])) {
            $popup->syncFiles([
                'popupImage' => $validated['files']['popupImage']
            ]);
        }

        Event::dispatch(new AdminUserActivityLogEvent(
            "Popup '{$popup->name}' has been updated by: " . Auth::user()->name,
            Auth::id(),
            ActivityTypeConstant::POPUP_UPDATED,
            $ipAddress
        ));

        return $popup;
    }
}
