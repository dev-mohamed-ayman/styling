<?php

namespace App\Console\Commands;

use App\Models\Admin;
use Illuminate\Console\Command;
use Spatie\Permission\Models\Role;

class MakeSuperAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'admin:super {email? : The email of the admin to make Super Admin}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Assign Super Admin role to an admin user';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        // Check if permission tables exist
        if (!\Schema::hasTable('roles')) {
            $this->error("Permission tables do not exist!");
            $this->info("Please run these commands in order:");
            $this->info("  1. php artisan migrate");
            $this->info("  2. php artisan db:seed --class=RolesAndPermissionsSeeder");
            $this->info("  3. php artisan admin:super");
            return 1;
        }

        $email = $this->argument('email') ?? 'admin@admin.com';

        $admin = Admin::where('email', $email)->first();

        if (!$admin) {
            $this->error("Admin with email '{$email}' not found!");
            return 1;
        }

        // Check if Super Admin role exists
        $superAdminRole = Role::where('name', 'Super Admin')->where('guard_name', 'admin')->first();

        if (!$superAdminRole) {
            $this->error("Super Admin role not found! Please run: php artisan db:seed --class=RolesAndPermissionsSeeder");
            return 1;
        }

        // Assign Super Admin role
        $admin->assignRole('Super Admin');

        $this->info("✅ Success! Admin '{$admin->name}' ({$admin->email}) is now a Super Admin.");
        $this->info("All permissions have been granted.");

        return 0;
    }
}
