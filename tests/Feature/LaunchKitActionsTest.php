<?php

namespace Tests\Feature;

use App\Models\ManagedFile;
use App\Models\Setting;
use App\Models\SettingAudit;
use App\Models\User;
use Database\Seeders\DatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class LaunchKitActionsTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_register_and_receive_default_role(): void
    {
        $this->seed(DatabaseSeeder::class);

        $this->post('/register', [
            'name' => 'New Member',
            'email' => 'member@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])->assertRedirect('/dashboard');

        $this->assertAuthenticated();
        $this->assertTrue(User::query()->where('email', 'member@example.com')->firstOrFail()->hasRole('user'));
    }

    public function test_admin_can_update_settings(): void
    {
        $this->seed(DatabaseSeeder::class);

        $admin = User::query()->where('email', 'superadmin@example.com')->firstOrFail();

        $this->actingAs($admin)->put('/settings', [
            'site_name' => 'Updated LaunchKit',
            'site_description' => 'Updated starter kit',
            'default_theme' => 'dark',
            'registration_enabled' => '1',
            'max_upload_size' => '2048',
        ])->assertRedirect();

        $this->assertSame('Updated LaunchKit', Setting::value('site_name'));
        $this->assertSame('dark', Setting::value('default_theme'));
        $this->assertTrue(SettingAudit::query()->where('key', 'site_name')->where('new_value', 'Updated LaunchKit')->exists());
    }

    public function test_admin_can_update_user_role(): void
    {
        $this->seed(DatabaseSeeder::class);

        $admin = User::query()->where('email', 'superadmin@example.com')->firstOrFail();
        $user = User::query()->where('email', 'user@example.com')->firstOrFail();

        $this->actingAs($admin)->put("/users/{$user->id}/role", [
            'role' => 'admin',
        ])->assertRedirect();

        $this->assertTrue($user->fresh()->hasRole('admin'));
    }

    public function test_file_manager_uploads_and_deletes_files(): void
    {
        Storage::fake('public');
        $this->seed(DatabaseSeeder::class);

        $admin = User::query()->where('email', 'superadmin@example.com')->firstOrFail();

        $this->actingAs($admin)->post('/file-manager', [
            'file' => UploadedFile::fake()->create('notes.pdf', 24, 'application/pdf'),
        ])->assertRedirect();

        $file = ManagedFile::query()->firstOrFail();
        Storage::disk('public')->assertExists($file->path);

        $this->actingAs($admin)->delete("/file-manager/{$file->id}")->assertRedirect();

        Storage::disk('public')->assertMissing($file->path);
        $this->assertDatabaseMissing('managed_files', ['id' => $file->id]);
    }

    public function test_notifications_can_be_marked_as_read(): void
    {
        $this->seed(DatabaseSeeder::class);

        $admin = User::query()->where('email', 'superadmin@example.com')->firstOrFail();
        $notification = $admin->appNotifications()->firstOrFail();

        $this->actingAs($admin)->post("/notifications/{$notification->id}/read")->assertRedirect();

        $this->assertNotNull($notification->fresh()->read_at);
    }

    public function test_activity_logs_can_be_filtered_by_search(): void
    {
        $this->seed(DatabaseSeeder::class);

        $admin = User::query()->where('email', 'superadmin@example.com')->firstOrFail();

        $this->actingAs($admin)
            ->get('/activity-logs?q=Dashboard')
            ->assertOk()
            ->assertSee('Activity logs');
    }

    public function test_two_factor_challenge_logs_user_in_with_valid_code(): void
    {
        $this->seed(DatabaseSeeder::class);

        $admin = User::query()->where('email', 'superadmin@example.com')->firstOrFail();
        $admin->forceFill([
            'two_factor_enabled' => true,
            'two_factor_code_hash' => Hash::make('123456'),
            'two_factor_expires_at' => now()->addMinutes(10),
        ])->save();

        $this->withSession([
            'two_factor_user_id' => $admin->id,
            'two_factor_remember' => false,
        ])->post('/two-factor-challenge', [
            'code' => '123456',
        ])->assertRedirect('/dashboard');

        $this->assertAuthenticatedAs($admin);
        $this->assertNull($admin->fresh()->two_factor_code_hash);
    }
}
