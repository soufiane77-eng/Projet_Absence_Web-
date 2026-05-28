<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    public function test_admin_can_register_new_user(): void
    {
        $admin = User::factory()->admin()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->post('/register', [
            'name' => 'New Teacher',
            'email' => 'teacher@example.com',
            'password' => 'Str0ng!Pass#2026',
            'password_confirmation' => 'Str0ng!Pass#2026',
            'role' => 'teacher',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertDatabaseHas('users', [
            'email' => 'teacher@example.com',
            'role' => 'teacher',
        ]);
    }

    public function test_non_admin_cannot_register_users(): void
    {
        $teacher = User::factory()->teacher()->create();
        $teacher->assignRole('teacher');

        $response = $this->actingAs($teacher)->get('/register');
        $response->assertStatus(403);
    }
}
