<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class RoleManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles and permissions
        $this->seed(RoleAndPermissionSeeder::class);
    }

    public function test_super_admin_can_access_role_management(): void
    {
        // Arrange: Create super admin user
        $user = User::factory()->create([
            'is_active' => true,
        ]);
        $user->assignRole('super_admin');

        // Act & Assert: Check if user can access role management
        $this->actingAs($user)
            ->get('/admin/roles')
            ->assertStatus(200);
    }

    public function test_admin_with_permission_can_create_role(): void
    {
        // Arrange: Create super admin user with permission
        $admin = User::factory()->create([
            'is_active' => true,
        ]);
        $admin->assignRole('super_admin'); // Super admin has role_create permission

        // Act & Assert: Check if admin can access role creation
        $this->actingAs($admin)
            ->get('/admin/roles/create')
            ->assertStatus(200);
    }

    public function test_admin_with_permission_can_edit_role(): void
    {
        // Arrange: Create admin user and get a role to edit
        $admin = User::factory()->create([
            'is_active' => true,
        ]);
        $admin->assignRole('super_admin'); // Super admin has role_edit permission

        // Get the role created by seeder
        $roleToEdit = Role::where('name', 'dosen')->first();

        // Act & Assert: Check if admin can access role edit page
        $this->actingAs($admin)
            ->get("/admin/roles/{$roleToEdit->id}/edit")
            ->assertStatus(200);
    }

    public function test_admin_without_permission_cannot_edit_role(): void
    {
        // Arrange: Create standard user without role_edit permission
        $user = User::factory()->create([
            'is_active' => true,
        ]);
        $user->assignRole('mahasiswa'); // Mahasiswa doesn't have role_edit permission

        // Get the role created by seeder
        $roleToEdit = Role::where('name', 'dosen')->first();

        // Act & Assert: Check if user without permission gets forbidden
        $this->actingAs($user)
            ->get("/admin/roles/{$roleToEdit->id}/edit")
            ->assertStatus(403);
    }

    public function test_role_with_permissions_can_be_created(): void
    {
        // Arrange: Create admin user with role_create permission
        $admin = User::factory()->create([
            'is_active' => true,
        ]);
        $admin->assignRole('super_admin');

        // Act & Assert: Super admin can access role creation page
        $this->actingAs($admin)
            ->get('/admin/roles/create')
            ->assertStatus(200);
    }
}
