<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\RoleSeeder::class);
    }

    public function test_admin_dashboard_renders(): void
    {
        $admin = User::factory()->admin()->create();
        $admin->assignRole('admin');

        $response = $this->actingAs($admin)->get('/admin/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Admin Dashboard');
    }

    public function test_teacher_dashboard_renders(): void
    {
        $teacher = User::factory()->teacher()->create();
        $teacher->assignRole('teacher');

        $response = $this->actingAs($teacher)->get('/teacher/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Teacher Dashboard');
    }

    public function test_student_dashboard_renders(): void
    {
        $student = User::factory()->create(['role' => 'student']);
        $student->assignRole('student');

        $response = $this->actingAs($student)->get('/student/dashboard');
        $response->assertStatus(200);
        $response->assertSee('Student Dashboard');
    }
}
