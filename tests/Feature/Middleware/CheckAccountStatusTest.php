<?php

namespace Tests\Feature\Middleware;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CheckAccountStatusTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    public function test_disabled_account_cannot_access(): void
    {
        $user = User::factory()->admin()->create(['is_active' => false]);
        $user->assignRole('admin');

        $response = $this->actingAs($user)->get('/admin/dashboard');
        $response->assertStatus(403);
    }

    public function test_locked_account_cannot_access(): void
    {
        $user = User::factory()->admin()->create([
            'locked_until' => now()->addMinutes(30),
            'login_attempts' => 5,
        ]);
        $user->assignRole('admin');

        $response = $this->actingAs($user)->get('/admin/dashboard');
        $response->assertStatus(423);
    }

    public function test_active_unlocked_account_can_access(): void
    {
        $user = User::factory()->admin()->create([
            'is_active' => true,
            'locked_until' => null,
        ]);
        $user->assignRole('admin');

        $response = $this->actingAs($user)->get('/admin/dashboard');
        $response->assertStatus(200);
    }
}
