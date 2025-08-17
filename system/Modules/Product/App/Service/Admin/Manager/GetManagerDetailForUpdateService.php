<?php

namespace Modules\Product\App\Service\Admin\Manager;

use Modules\Product\App\Models\Manager;

class GetManagerDetailForUpdateService
{
    public function getManagerDetailForUpdate(int $id)
    {
        $manager = Manager::find($id);

        if (!$manager) {
            throw new \Exception('Manager Not found');
        }

        return [
            'firstname' => $manager->firstname,
            'lastname' => $manager->lastname,
            'description' => $manager->description,
            'email' => $manager->email,
            'phone_number' => $manager->phone_number
        ];
    }
}
