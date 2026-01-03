<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Create Roles
        $adminRole = Role::create(['name' => 'admin']);
        $userRole = Role::create(['name' => 'user']);
        // You can add 'enforcer' here later if needed

        // 3. Create the Default Admin User
        $adminUser = User::create([
            'name' => 'Super Admin',
            'email' => 'admin@admin.com', // Using a valid email format
            'password' => Hash::make('admin123'),
        ]);

        // 4. Assign the 'admin' role to this user
        $adminUser->assignRole($adminRole);
        
        // Optional: Create a test 'Normal User' just to see the difference
        $normalUser = User::create([
            'name' => 'Test User',
            'email' => 'user@user.com',
            'password' => Hash::make('password'),
        ]);
        $normalUser->assignRole($userRole);
    }
}