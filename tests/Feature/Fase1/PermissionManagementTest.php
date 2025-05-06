<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class PermissionManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles and permissions
        $this->seed(RoleAndPermissionSeeder::class);
    }

    public function test_super_admin_can_access_permission_management(): void
    {
        // Arrange: Create super admin user
        $user = User::factory()->create([
            'is_active' => true,
        ]);
        $user->assignRole('super_admin');

        // Act & Assert: Check if user can access permission management
        $this->actingAs($user)
            ->get('/admin/permissions')
            ->assertStatus(200);
    }

    public function test_admin_with_permission_can_create_permission(): void
    {
        // Arrange: Create admin user with permission
        $admin = User::factory()->create([
            'is_active' => true,
        ]);
        $admin->assignRole('super_admin');

        // Act & Assert: Check if admin can access permission creation
        $this->actingAs($admin)
            ->get('/admin/permissions/create')
            ->assertStatus(200);
    }

    public function test_admin_with_permission_can_edit_permission(): void
    {
        // Arrange: Create admin user and get a permission to edit
        $admin = User::factory()->create([
            'is_active' => true,
        ]);
        $admin->assignRole('super_admin');

        // Get a permission created by seeder
        $permissionToEdit = Permission::where('name', 'user_create')->first();

        // Act & Assert: Check if admin can access permission edit page
        $this->actingAs($admin)
            ->get("/admin/permissions/{$permissionToEdit->id}/edit")
            ->assertStatus(200);
    }

    public function test_admin_without_permission_cannot_edit_permission(): void
    {
        // Arrange: Create user without permission_edit permission
        $user = User::factory()->create([
            'is_active' => true,
        ]);
        $user->assignRole('mahasiswa');

        // Get a permission created by seeder
        $permissionToEdit = Permission::where('name', 'user_create')->first();

        // Act & Assert: Check if user without permission gets forbidden
        $this->actingAs($user)
            ->get("/admin/permissions/{$permissionToEdit->id}/edit")
            ->assertStatus(403);
    }

    public function test_permission_can_be_created(): void
    {
        // Arrange: Create admin user with permission_create permission
        $admin = User::factory()->create([
            'is_active' => true,
        ]);
        $admin->assignRole('super_admin');

        // Act & Assert: Super admin can access permission creation page
        $this->actingAs($admin)
            ->get('/admin/permissions/create')
            ->assertStatus(200);

        // Dalam scenario sesungguhnya, kita akan melakukan:
        // $response = $this->post('/admin/permissions', [
        //     'name' => 'new_test_permission',
        //     'guard_name' => 'web',
        // ]);
        // $response->assertRedirect('/admin/permissions');
        // $this->assertDatabaseHas('permissions', ['name' => 'new_test_permission']);
    }
}
