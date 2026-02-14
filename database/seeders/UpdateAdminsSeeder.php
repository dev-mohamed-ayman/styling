<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;

class UpdateAdminsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Update all existing admins to have default role
        Admin::whereNull('role')->orWhere('role', '')->update([
            'role' => 'admin',
            'is_super_admin' => false,
        ]);

        // Mark the default admin as super admin
        Admin::where('email', 'admin@admin.com')->update([
            'role' => 'admin',
            'is_super_admin' => true,
        ]);

        $this->command->info('Admin roles updated successfully!');
    }
}