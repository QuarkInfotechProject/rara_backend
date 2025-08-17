<?php

namespace Modules\PageVault\App\Service\WhyUs;

use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Modules\PageVault\App\Models\WhyUs;

class EditWhyUsService
{

    public function editWhyUs(array $data)
    {
        $whyUs = WhyUs::findOrFail($data['id']);

        $validator = Validator::make($data, [
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'link' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            throw new ValidationException($validator);
        }

        $whyUs->update([
            'title' => $data['title'],
            'description' => $data['description'],
            'link' => $data['link'] ?? null,
            'order' => $data['order'] ?? 0,
            'is_active' => $data['is_active'] ?? true,
        ]);

    }

}
