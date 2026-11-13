<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    private function createAdmin(): array
    {
        $company = Company::create([
            'name' => 'Qsoftware',
        ]);

        $admin = User::factory()->create([
            'company_id' => $company->id,
            'role' => 'admin',
            'is_active' => true,
        ]);

        Sanctum::actingAs($admin);

        return [$company, $admin];
    }

    public function test_admin_can_register_company_user(): void
    {
        [$company] = $this->createAdmin();

        $response = $this->postJson('/api/users', [
            'name' => 'Support Agent',
            'email' => 'agent@example.com',
            'password' => 'password123',
            'role' => 'agent',
        ]);

        $response->assertCreated();

        $this->assertDatabaseHas('users', [
            'email' => 'agent@example.com',
            'company_id' => $company->id,
            'role' => 'agent',
            'is_active' => true,
        ]);
    }

    public function test_admin_can_list_company_users(): void
    {
        [$company, $admin] = $this->createAdmin();

        User::factory()->create([
            'company_id' => $company->id,
        ]);

        $response = $this->getJson('/api/users');

        $response->assertOk()
            ->assertJsonCount(2, 'users');
    }

    public function test_admin_can_view_company_user(): void
    {
        [$company] = $this->createAdmin();

        $user = User::factory()->create([
            'company_id' => $company->id,
        ]);

        $response = $this->getJson("/api/users/{$user->id}");

        $response->assertOk()
            ->assertJsonPath('user.id', $user->id);
    }

    public function test_admin_can_update_company_user(): void
    {
        [$company] = $this->createAdmin();

        $user = User::factory()->create([
            'company_id' => $company->id,
        ]);

        $response = $this->putJson("/api/users/{$user->id}", [
            'name' => 'Updated Agent',
            'role' => 'admin',
        ]);

        $response->assertOk()
            ->assertJsonPath('user.name', 'Updated Agent');

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'name' => 'Updated Agent',
            'role' => 'admin',
        ]);
    }

    public function test_admin_can_toggle_user_status(): void
    {
        [$company] = $this->createAdmin();

        $user = User::factory()->create([
            'company_id' => $company->id,
            'is_active' => true,
        ]);

        $response = $this->patchJson("/api/users/{$user->id}/status");

        $response->assertOk()
            ->assertJsonPath('user.is_active', false);
    }

    public function test_admin_cannot_manage_users_from_another_company(): void
    {
        $this->createAdmin();

        $otherCompany = Company::create([
            'name' => 'Other Company',
        ]);

        $otherUser = User::factory()->create([
            'company_id' => $otherCompany->id,
        ]);

        $response = $this->getJson("/api/users/{$otherUser->id}");

        $response->assertNotFound();
    }

    public function test_agent_cannot_manage_company_users(): void
    {
        $company = Company::create([
            'name' => 'Qsoftware',
        ]);

        $agent = User::factory()->create([
            'company_id' => $company->id,
            'role' => 'agent',
            'is_active' => true,
        ]);

        Sanctum::actingAs($agent);

        $response = $this->getJson('/api/users');

        $response->assertForbidden();
    }

    public function test_unauthenticated_user_cannot_manage_company_users(): void
    {
        $response = $this->getJson('/api/users');

        $response->assertUnauthorized();
    }
}