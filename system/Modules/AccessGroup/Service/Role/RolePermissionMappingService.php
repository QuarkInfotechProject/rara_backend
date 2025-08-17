<?php

namespace Modules\AccessGroup\Service\Role;

use Modules\AccessGroup\Trait\RolePermissionExistenceTrait;

class RolePermissionMappingService
{
    use RolePermissionExistenceTrait;

    function index(int $groupId)
    {
        $role = $this->checkRoleExistence($groupId);

        return $role->permissions
            ->pluck('id');
    }
}
