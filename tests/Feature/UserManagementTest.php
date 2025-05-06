<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RoleAndPermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Seed roles and permissions
        $this->seed(RoleAndPermissionSeeder::class);
    }

    public function test_super_admin_can_access_admin_panel(): void
    {
        // Arrange: Create super admin user
        $user = User::factory()->create([
            'is_active' => true,
        ]);
        $user->assignRole('super_admin');

        // Act & Assert: Check if user can access admin panel
        $this->actingAs($user)
            ->get('/admin')
            ->assertStatus(200);
    }

    public function test_inactive_admin_cannot_access_admin_panel(): void
    {
        // Arrange: Create inactive admin user
        $user = User::factory()->create([
            'is_active' => false,
        ]);
        $user->assignRole('admin');

        // Act & Assert: Check if user cannot access admin panel
        $this->actingAs($user)
            ->get('/admin')
            ->assertStatus(403);
    }

    public function test_dosen_can_access_admin_panel(): void
    {
        // Arrange: Create dosen user
        $user = User::factory()->create([
            'is_active' => true,
        ]);
        $user->assignRole('dosen');

        // Act & Assert: Check if dosen can access admin panel
        $this->actingAs($user)
            ->get('/admin')
            ->assertStatus(200);
    }

    public function test_mahasiswa_cannot_access_admin_panel(): void
    {
        // Arrange: Create mahasiswa user
        $user = User::factory()->create([
            'is_active' => true,
        ]);
        $user->assignRole('mahasiswa');

        // Act & Assert: Check if mahasiswa cannot access admin panel
        $this->actingAs($user)
            ->get('/admin')
            ->assertStatus(403);
    }

    public function test_admin_can_create_user(): void
    {
        // Arrange: Create admin user and login
        $admin = User::factory()->create([
            'is_active' => true,
        ]);
        $admin->assignRole('admin');

        // Act & Assert: Admin can access user creation page
        $this->actingAs($admin);
        $response = $this->get('/admin/users/create');
        $response->assertStatus(200);
    }

    public function test_admin_can_edit_user(): void
    {
        // Arrange: Create admin user and regular user
        $admin = User::factory()->create([
            'is_active' => true,
        ]);
        $admin->assignRole('admin');

        $user = User::factory()->create();

        // Act & Assert: Admin can access user edit page
        $this->actingAs($admin);
        $response = $this->get("/admin/users/{$user->id}/edit");
        $response->assertStatus(200);
    }
}
