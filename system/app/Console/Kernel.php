<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Modules\AccessGroup\App\Console\AssignSuperAdminRole;
use Modules\AccessGroup\App\Console\LoadPermissionsFromFile;
use Modules\Menu\App\Console\LoadDefaultMenu;
use Modules\PageVault\App\Console\LoadDefaultPages;
use Modules\AccessGroup\App\Console\SyncModelHasPermissions;

class Kernel extends ConsoleKernel
{
    protected $commands = [
        LoadDefaultMenu::class,
        LoadPermissionsFromFile::class,
        AssignSuperAdminRole::class,
        LoadDefaultPages::class,
        SyncModelHasPermissions::class,
    ];

    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // $schedule->command('inspire')->hourly();
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
