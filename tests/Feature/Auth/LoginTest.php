<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Models\User;
use App\Models\LoginHistory;
use App\Models\ActivityLog;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'username' => 'testuser',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);
        $user->assignRole('admin');

        $response = $this->post('/login', [
            'username' => 'testuser',
            'password' => 'password',
        ]);

        $response->assertRedirect('/admin/dashboard');
        $this->assertAuthenticated();
    }

    public function test_user_cannot_login_with_invalid_password(): void
    {
        $user = User::factory()->create([
            'username' => 'testuser',
            'password' => bcrypt('password'),
        ]);

        $response = $this->post('/login', [
            'username' => 'testuser',
            'password' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    public function test_login_attempts_increment_on_failure(): void
    {
        $user = User::factory()->create([
            'username' => 'testuser',
            'password' => bcrypt('password'),
            'login_attempts' => 0,
        ]);

        $this->post('/login', [
            'username' => 'testuser',
            'password' => 'wrong',
        ]);

        $this->assertEquals(1, $user->fresh()->login_attempts);
    }

    public function test_account_locks_after_5_failed_attempts(): void
    {
        $user = User::factory()->create([
            'username' => 'testuser',
            'password' => bcrypt('password'),
            'login_attempts' => 4,
        ]);

        $this->post('/login', [
            'username' => 'testuser',
            'password' => 'wrong',
        ]);

        $user->refresh();
        $this->assertEquals(5, $user->login_attempts);
        $this->assertNotNull($user->locked_until);
    }

    public function test_disabled_account_cannot_login(): void
    {
        $user = User::factory()->create([
            'username' => 'testuser',
            'password' => bcrypt('password'),
            'is_active' => false,
        ]);

        $response = $this->post('/login', [
            'username' => 'testuser',
            'password' => 'password',
        ]);

        $response->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    public function test_login_creates_history_record(): void
    {
        $user = User::factory()->create([
            'username' => 'testuser',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);
        $user->assignRole('admin');

        $this->post('/login', [
            'username' => 'testuser',
            'password' => 'password',
        ]);

        $this->assertDatabaseHas('login_histories', [
            'user_id' => $user->id,
            'login_successful' => true,
        ]);
    }

    public function test_login_creates_activity_log(): void
    {
        $user = User::factory()->create([
            'username' => 'testuser',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);
        $user->assignRole('admin');

        $this->post('/login', [
            'username' => 'testuser',
            'password' => 'password',
        ]);

        $this->assertDatabaseHas('activity_logs', [
            'action' => 'user.login',
        ]);
    }

    public function test_user_can_logout(): void
    {
        $user = User::factory()->create(['role' => 'admin']);
        $user->assignRole('admin');

        $response = $this->actingAs($user)->post('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }
}
