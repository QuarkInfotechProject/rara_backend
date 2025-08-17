<?php

namespace Modules\PageVault\App\Service\Promotion;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\PageVault\App\Models\Promotion;

class UpdatePromotionService
{
    public function updatePromotion(array $data)
    {
        $promotion = Promotion::findOrFail($data['id']);

        $validator = Validator::make($data, [
            'name' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'link' => 'nullable|string|max:255',
            'placement_place' => 'nullable|string|max:255',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $promotion->update([
            'name' => $data['name'] ?? $promotion->name,
            'description' => $data['description'] ?? $promotion->description,
            'link' => $data['link'] ?? $promotion->link,
            'placement_place' => $data['placement_place'] ?? $promotion->placement_place,
            'is_active' => $data['is_active'] ?? $promotion->is_active,
        ]);
    }
}
