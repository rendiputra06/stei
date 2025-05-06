<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleAndPermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Reset cached roles and permissions
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // Create permissions
        $permissions = [
            // User management
            'user_management_access',
            'user_create',
            'user_edit',
            'user_delete',
            'user_show',

            // Role management
            'role_management_access',
            'role_create',
            'role_edit',
            'role_delete',
            'role_show',

            // Permission management
            'permission_management_access',
            'permission_create',
            'permission_edit',
            'permission_delete',
            'permission_show',

            // Akademik permissions
            'mata_kuliah_access',
            'mata_kuliah_create',
            'mata_kuliah_edit',
            'mata_kuliah_delete',
            'mata_kuliah_show',

            'nilai_access',
            'nilai_create',
            'nilai_edit',
            'nilai_delete',
            'nilai_show',

            'jadwal_access',
            'jadwal_create',
            'jadwal_edit',
            'jadwal_delete',
            'jadwal_show',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // Create roles and assign permissions
        // Super Admin Role
        $superAdminRole = Role::create(['name' => 'super_admin']);
        $superAdminRole->givePermissionTo(Permission::all());

        // Admin Role
        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo([
            'user_management_access',
            'user_create',
            'user_edit',
            'user_show',
            'role_show',
            'permission_show',
            'mata_kuliah_access',
            'mata_kuliah_show',
            'nilai_access',
            'nilai_show',
            'jadwal_access',
            'jadwal_show',
        ]);

        // Dosen Role
        $dosenRole = Role::create(['name' => 'dosen']);
        $dosenRole->givePermissionTo([
            'user_show',
            'mata_kuliah_access',
            'mata_kuliah_show',
            'nilai_access',
            'nilai_create',
            'nilai_edit',
            'nilai_show',
            'jadwal_access',
            'jadwal_show',
        ]);

        // Mahasiswa Role
        $mahasiswaRole = Role::create(['name' => 'mahasiswa']);
        $mahasiswaRole->givePermissionTo([
            'user_show',
            'mata_kuliah_show',
            'nilai_show',
            'jadwal_show',
        ]);

        // Create demo users
        // Super Admin User
        $user = User::create([
            'name' => 'Super Admin',
            'email' => 'superadmin@example.com',
            'password' => bcrypt('password'),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $user->assignRole('super_admin');

        // Admin User
        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $user->assignRole('admin');

        // Dosen User
        $user = User::create([
            'name' => 'Dosen STEI',
            'email' => 'dosen@example.com',
            'password' => bcrypt('password'),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $user->assignRole('dosen');

        // Mahasiswa User
        $user = User::create([
            'name' => 'Mahasiswa STEI',
            'email' => 'mahasiswa@example.com',
            'password' => bcrypt('password'),
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
        $user->assignRole('mahasiswa');
    }
}
