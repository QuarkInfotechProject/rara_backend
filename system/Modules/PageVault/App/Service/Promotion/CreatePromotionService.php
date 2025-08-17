<?php

namespace Modules\PageVault\App\Service\Promotion;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\PageVault\App\Models\Promotion;
use Modules\PageVault\App\Models\WhyUs;

class CreatePromotionService
{

    public function createWhyUs(array $data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'link' => 'nullable|string|max:255',
            'placement_place' => 'required|string',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        Promotion::create([
            'name' => $data['name'],
            'description' => $data['description'],
            'link' => $data['link'] ?? null,
            'order' => $data['order'] ?? 0,
            'is_active' => $data['is_active'] ?? true,
        ]);
    }

}
