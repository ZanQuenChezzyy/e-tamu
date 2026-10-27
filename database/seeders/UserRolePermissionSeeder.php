<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserRolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Permissions for users
        $permissions = [
            'View Any User',
            'View User',
            'Create User',
            'Update User',
            'Delete User',
            'Restore User',
            'Force Delete User',
            'View Any Role',
            'View Role',
            'Create Role',
            'Update Role',
            'Delete Role',
            'Restore Role',
            'Force Delete Role',
            'View Any Permission',
            'View Permission',
            'Create Permission',
            'Update Permission',
            'Delete Permission',
            'Restore Permission',
            'Force Delete Permission',
            // E-Tamu specific permissions
            'View Any Department',
            'View Department',
            'Create Department',
            'Update Department',
            'Delete Department',
            'Restore Department',
            'Force Delete Department',
            'View Any Guest',
            'View Guest',
            'Create Guest',
            'Update Guest',
            'Delete Guest',
            'Restore Guest',
            'Force Delete Guest',
            'View Any Visit',
            'View Visit',
            'Create Visit',
            'Update Visit',
            'Delete Visit',
            'Restore Visit',
            'Force Delete Visit',
        ];

        // Create permissions
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles
        $roles = [
            'Administrator' => $permissions,
            'Security' => [
                'View Any Visit',
                'View Visit',
                'Update Visit',
            ],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::firstOrCreate(['name' => $roleName]);
            if (!empty($rolePermissions)) {
                $role->givePermissionTo($rolePermissions);
            }
        }

        // Create users and assign roles
        $users = [
            [
                'name' => 'Administrator',
                'email' => 'admin@etamu.com',
                'password' => Hash::make('password'),
                'role' => 'Administrator',
            ],
            [
                'name' => 'Security Guard',
                'email' => 'security@etamu.com',
                'password' => Hash::make('password'),
                'role' => 'Security',
            ],
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => $userData['password'],
                ]
            );

            $user->assignRole($userData['role']);
        }

        $this->command->info('Roles, Permissions, dan Users E-Tamu telah berhasil dibuat!');
    }
}
