<?php

namespace Modules\AccessGroup\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Modules\AdminUser\App\Models\AdminUser;
use Modules\Menu\App\Models\Menu;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class AdminUserPermissionsSeeder extends Seeder
{
    public function run(): void
    {
        $guardName = 'admin';
        $now = Carbon::now();

        $basePermissions = [
            'view_dashboard',
            'view_review',
            'view_booking',
            'view_agent',
            'view_activity_logs',
            'view_admin_users',
            'view_user',
            'view_media_center',
            'view_blog',
            'view_pages',
            'view_why_us',
            'view_promotion',
            'view_our_team',
            'view_faqs',
            'view_cta',
            'view_product',
            'view_system_configurations',
            'view_email_templates',
            'view_setting_configurations',
            'view_roles',
            'view_newsletter'
        ];

        // Create base permissions
        foreach ($basePermissions as $permissionName) {
            Permission::firstOrCreate(
                ['name' => $permissionName, 'guard_name' => $guardName],
                ['created_at' => $now, 'updated_at' => $now]
            );
        }

        // Create or get Super Admin role and assign all permissions
        $superAdminRole = Role::firstOrCreate(
            ['name' => 'Super Admin', 'guard_name' => $guardName],
            ['created_at' => $now, 'updated_at' => $now]
        );

        $allPermissions = Permission::where('guard_name', $guardName)->get();
        $superAdminRole->syncPermissions($allPermissions);

        // Assign role to admin user
        $adminEmail = 'admin@squarebx.com';
        $admin = AdminUser::where('email', $adminEmail)->first();

        if ($admin) {
            $admin->assignRole($superAdminRole);
            $this->command->info("Assigned 'Super Admin' role to admin: {$admin->email}");
        } else {
            $this->command->warn("Admin user with email {$adminEmail} not found.");
        }

        // Sync menu permissions
        $menus = Menu::all();

        foreach ($menus as $menu) {
            $permissionName = trim($menu->permission_name ?? '');

            $this->command->info("Processing menu ID {$menu->id} with permission '{$permissionName}'");

            if (empty($permissionName)) {
                $this->command->warn("Skipping menu ID {$menu->id} because permission is empty.");
                continue;
            }

            $permission = Permission::firstOrCreate(
                ['name' => $permissionName, 'guard_name' => $guardName],
                ['created_at' => $now, 'updated_at' => $now]
            );

            DB::table('model_has_permissions')->updateOrInsert(
                [
                    'permission_id' => $permission->id,
                    'model_type'    => Menu::class,
                    'model_id'      => $menu->id,
                ],
                []
            );

            DB::table('permission_menu')->updateOrInsert(
                [
                    'menu_id'       => $menu->id,
                    'permission_id' => $permission->id,
                ],
                [
                    'group_id' => 1,
                ]
            );

            $adminRole = Role::where('name', 'Admin')->first();
            if ($adminRole) {
                DB::table('role_has_permissions')->updateOrInsert(
                    [
                        'permission_id' => $permission->id,
                        'role_id'       => $adminRole->id,
                    ],
                    [
                        'isIndex' => 0,
                    ]
                );
            }

            $this->command->info("Synced permission '{$permissionName}' with menu ID {$menu->id}");
        }

        $this->command->info('✅ Seeder completed: Roles, permissions, and menu permissions synced successfully.');
    }
}
