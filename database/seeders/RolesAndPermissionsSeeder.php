<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use App\Models\Admin;
use App\Models\User;

class RolesAndPermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if permission tables exist
        if (!\Schema::hasTable('permissions')) {
            $this->command->error('Permission tables do not exist. Please run: php artisan migrate');
            return;
        }

        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // ============================================
        // Create Permissions (skip if already exists)
        // ============================================

        $permissions = [
            // Dashboard Permissions
            ['name' => 'view dashboard', 'guard_name' => 'admin'],
            ['name' => 'view dashboard', 'guard_name' => 'web'],

            // User Management Permissions
            ['name' => 'view users', 'guard_name' => 'admin'],
            ['name' => 'create users', 'guard_name' => 'admin'],
            ['name' => 'edit users', 'guard_name' => 'admin'],
            ['name' => 'delete users', 'guard_name' => 'admin'],
            ['name' => 'block users', 'guard_name' => 'admin'],

            // Admin Management Permissions
            ['name' => 'view admins', 'guard_name' => 'admin'],
            ['name' => 'create admins', 'guard_name' => 'admin'],
            ['name' => 'edit admins', 'guard_name' => 'admin'],
            ['name' => 'delete admins', 'guard_name' => 'admin'],

            // Role & Permission Management
            ['name' => 'view roles', 'guard_name' => 'admin'],
            ['name' => 'create roles', 'guard_name' => 'admin'],
            ['name' => 'edit roles', 'guard_name' => 'admin'],
            ['name' => 'delete roles', 'guard_name' => 'admin'],
            ['name' => 'view permissions', 'guard_name' => 'admin'],
            ['name' => 'assign permissions', 'guard_name' => 'admin'],
            ['name' => 'assign roles', 'guard_name' => 'admin'],

            // Fashion Styles Permissions
            ['name' => 'view fashion_styles', 'guard_name' => 'admin'],
            ['name' => 'create fashion_styles', 'guard_name' => 'admin'],
            ['name' => 'edit fashion_styles', 'guard_name' => 'admin'],
            ['name' => 'delete fashion_styles', 'guard_name' => 'admin'],

            // Banners Permissions
            ['name' => 'view banners', 'guard_name' => 'admin'],
            ['name' => 'create banners', 'guard_name' => 'admin'],
            ['name' => 'edit banners', 'guard_name' => 'admin'],
            ['name' => 'delete banners', 'guard_name' => 'admin'],

            // Stylists Permissions
            ['name' => 'view stylists', 'guard_name' => 'admin'],
            ['name' => 'create stylists', 'guard_name' => 'admin'],
            ['name' => 'edit stylists', 'guard_name' => 'admin'],
            ['name' => 'delete stylists', 'guard_name' => 'admin'],

            // Stylist Features Permissions
            ['name' => 'view stylist_features', 'guard_name' => 'admin'],
            ['name' => 'create stylist_features', 'guard_name' => 'admin'],
            ['name' => 'edit stylist_features', 'guard_name' => 'admin'],
            ['name' => 'delete stylist_features', 'guard_name' => 'admin'],

            // Stylist Images Permissions
            ['name' => 'view stylist_images', 'guard_name' => 'admin'],
            ['name' => 'create stylist_images', 'guard_name' => 'admin'],
            ['name' => 'edit stylist_images', 'guard_name' => 'admin'],
            ['name' => 'delete stylist_images', 'guard_name' => 'admin'],

            // Stylist Services Permissions
            ['name' => 'view stylist_services', 'guard_name' => 'admin'],
            ['name' => 'create stylist_services', 'guard_name' => 'admin'],
            ['name' => 'edit stylist_services', 'guard_name' => 'admin'],
            ['name' => 'delete stylist_services', 'guard_name' => 'admin'],

            // Stylist Reviews Permissions
            ['name' => 'view stylist_reviews', 'guard_name' => 'admin'],
            ['name' => 'create stylist_reviews', 'guard_name' => 'admin'],
            ['name' => 'edit stylist_reviews', 'guard_name' => 'admin'],
            ['name' => 'delete stylist_reviews', 'guard_name' => 'admin'],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate($permission);
        }

        // ============================================
        // Create Roles and Assign Permissions
        // ============================================

        // Super Admin - Has all permissions
        $superAdminRole = Role::firstOrCreate(['name' => 'Super Admin', 'guard_name' => 'admin']);
        $superAdminRole->syncPermissions(Permission::where('guard_name', 'admin')->get());

        // Admin - Has most permissions except admin management
        $adminRole = Role::firstOrCreate(['name' => 'Admin', 'guard_name' => 'admin']);
        $adminRole->syncPermissions([
            'view dashboard',
            'view users', 'create users', 'edit users', 'delete users', 'block users',
            'view fashion_styles', 'create fashion_styles', 'edit fashion_styles', 'delete fashion_styles',
            'view banners', 'create banners', 'edit banners', 'delete banners',
            'view stylists', 'create stylists', 'edit stylists', 'delete stylists',
            'view stylist_features', 'create stylist_features', 'edit stylist_features', 'delete stylist_features',
            'view stylist_images', 'create stylist_images', 'edit stylist_images', 'delete stylist_images',
            'view stylist_services', 'create stylist_services', 'edit stylist_services', 'delete stylist_services',
            'view stylist_reviews', 'create stylist_reviews', 'edit stylist_reviews', 'delete stylist_reviews',
        ]);

        // Manager - Can view and edit but not delete
        $managerRole = Role::firstOrCreate(['name' => 'Manager', 'guard_name' => 'admin']);
        $managerRole->syncPermissions([
            'view dashboard',
            'view users', 'create users', 'edit users', 'block users',
            'view fashion_styles', 'create fashion_styles', 'edit fashion_styles',
            'view banners', 'create banners', 'edit banners',
            'view stylists', 'create stylists', 'edit stylists',
            'view stylist_features', 'create stylist_features', 'edit stylist_features',
            'view stylist_images', 'create stylist_images', 'edit stylist_images',
            'view stylist_services', 'create stylist_services', 'edit stylist_services',
            'view stylist_reviews', 'create stylist_reviews', 'edit stylist_reviews',
        ]);

        // Editor - Can only view and edit content
        $editorRole = Role::firstOrCreate(['name' => 'Editor', 'guard_name' => 'admin']);
        $editorRole->syncPermissions([
            'view dashboard',
            'view users', 'edit users',
            'view fashion_styles', 'edit fashion_styles',
            'view banners', 'edit banners',
            'view stylists', 'edit stylists',
            'view stylist_features', 'edit stylist_features',
            'view stylist_images', 'edit stylist_images',
            'view stylist_services', 'edit stylist_services',
            'view stylist_reviews', 'edit stylist_reviews',
        ]);

        // Viewer - Read-only access
        $viewerRole = Role::firstOrCreate(['name' => 'Viewer', 'guard_name' => 'admin']);
        $viewerRole->syncPermissions([
            'view dashboard',
            'view users',
            'view fashion_styles',
            'view banners',
            'view stylists',
            'view stylist_features',
            'view stylist_images',
            'view stylist_services',
            'view stylist_reviews',
        ]);

        // ============================================
        // Web/User Roles (for frontend)
        // ============================================

        // Regular User
        $userRole = Role::firstOrCreate(['name' => 'User', 'guard_name' => 'web']);
        $userRole->syncPermissions(['view dashboard']);

        // Premium User
        $premiumRole = Role::firstOrCreate(['name' => 'Premium User', 'guard_name' => 'web']);
        $premiumRole->syncPermissions(['view dashboard']);

        // Stylist Role (for frontend)
        $stylistRole = Role::firstOrCreate(['name' => 'Stylist', 'guard_name' => 'web']);
        $stylistRole->syncPermissions(['view dashboard']);

        // ============================================
        // Assign Super Admin role to default admin
        // ============================================
        $admin = Admin::where('email', 'admin@admin.com')->first();
        if ($admin) {
            $admin->assignRole('Super Admin');
        }

        $this->command->info('Roles and Permissions seeded successfully!');
        $this->command->info('Default admin (admin@admin.com) has been assigned Super Admin role.');
    }
}
