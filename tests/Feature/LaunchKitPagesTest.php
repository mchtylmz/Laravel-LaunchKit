<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LaunchKitPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_pages_render_successfully(): void
    {
        $this->get('/')->assertOk()->assertSee('Laravel LaunchKit');
        $this->get('/login')->assertOk()->assertSee('Login');
        $this->get('/register')->assertOk()->assertSee('Register');
    }

    public function test_admin_pages_render_successfully(): void
    {
        $this->seed(DatabaseSeeder::class);

        $admin = User::query()->where('email', 'superadmin@example.com')->firstOrFail();

        $this->actingAs($admin)->get('/dashboard')->assertOk()->assertSee('Dashboard');
        $this->actingAs($admin)->get('/profile')->assertOk()->assertSee('Profile information');
        $this->actingAs($admin)->get('/settings')->assertOk()->assertSee('Application settings');
        $this->actingAs($admin)->get('/users')->assertOk()->assertSee('Create user');
        $this->actingAs($admin)->get('/roles')->assertOk()->assertSee('Create role');
        $this->actingAs($admin)->get('/activity-logs')->assertOk()->assertSee('Activity logs');
        $this->actingAs($admin)->get('/file-manager')->assertOk()->assertSee('Upload file');
    }
}
