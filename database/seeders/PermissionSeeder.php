<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Define all admin resource permissions
        $permissions = [
            'view_blogs',
            'view_packages',
            'view_destinations',
            'view_media',
            'view_galleries',
            'view_testimonials',
            'view_banners',
            'view_categories',
            'view_navigation_menus',
            'view_users',
            'view_roles',
            'view_pages',
        ];

        // Create permissions if they don't exist
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(
                ['name' => $permission],
                ['guard_name' => 'web']
            );
        }

        $this->command->info('Permissions created successfully!');
    }
}
