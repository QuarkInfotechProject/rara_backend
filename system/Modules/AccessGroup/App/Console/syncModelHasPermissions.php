<?php

namespace Modules\AccessGroup\App\Console;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Modules\Menu\App\Models\Menu;
use Spatie\Permission\Models\Permission;

class SyncModelHasPermissions extends Command
{
    protected $signature = 'sync:model_has_permissions';
    protected $description = 'Sync model_has_permissions table based on menu permissions';

    public function handle()
    {
        $menus = Menu::all();

        foreach ($menus as $menu) {
            if (!$menu->permission) {
                continue;
            }

            $permission = Permission::where('name', $menu->permission)->first();
            if (!$permission) {
                $this->warn("Permission '{$menu->permission}' not found for menu ID {$menu->id}");
                continue;
            }

            DB::table('model_has_permissions')->updateOrInsert([
                'permission_id' => $permission->id,
                'model_type' => Menu::class,
                'model_id' => $menu->id,
            ]);

            $this->info("Synced permission '{$menu->permission}' with menu ID {$menu->id}");
        }

        $this->info('model_has_permissions sync completed!');
    }
}
