<?php

namespace Database\Seeders;

use Database\Seeders\Actions\ActionsSeeder;
use Database\Seeders\Permissions\PermissionSeeder;
use Database\Seeders\Role\RoleSeeder;
use Database\Seeders\RolePermission\RolePermissionSeeder;
use Database\Seeders\shifts\shiftSeeder;
use Database\Seeders\Status_events\events_status;
use Database\Seeders\Status_reason\reason_status;
use Database\Seeders\Status_rooms\events_rooms;
use Database\Seeders\Status_user\status_user;
use Database\Seeders\TypeDocument\typedocumentseeders;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
            shiftSeeder::class,
            events_status::class,
            reason_status::class,
            events_rooms::class,
            status_user::class,
            ActionsSeeder::class,
            typedocumentseeders::class,
        ]);

    }
}
