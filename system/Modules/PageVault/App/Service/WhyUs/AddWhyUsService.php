<?php

namespace Modules\PageVault\App\Service\WhyUs;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\PageVault\App\Models\Promotion;
use Modules\PageVault\App\Models\WhyUs;

class AddWhyUsService
{

    public function createWhyUs(array $data)
    {
        $validator = Validator::make($data, [
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'link' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        WhyUs::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'link' => $data['link'] ?? null,
            'order' => $data['order'] ?? 0,
            'is_active' => $data['is_active'] ?? true,
        ]);
    }
}
