<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CompanyRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_register_a_company(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/companies', [
            'name' => 'Qsoftware',
        ]);

        $response->assertCreated()
            ->assertJsonPath('company.name', 'Qsoftware');

        $company = Company::where('name', 'Qsoftware')->first();

        $this->assertNotNull($company);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'company_id' => $company->id,
        ]);
    }

    public function test_unauthenticated_user_cannot_register_a_company(): void
    {
        $response = $this->postJson('/api/companies', [
            'name' => 'Qsoftware',
        ]);

        $response->assertUnauthorized();
    }

    public function test_company_name_is_required(): void
    {
        $user = User::factory()->create();

        Sanctum::actingAs($user);

        $response = $this->postJson('/api/companies', []);

        $response->assertUnprocessable()
            ->assertJsonValidationErrors(['name']);
    }
}