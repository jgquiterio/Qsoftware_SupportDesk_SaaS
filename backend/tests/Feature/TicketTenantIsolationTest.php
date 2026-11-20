<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketTenantIsolationTest extends TestCase
{
    use RefreshDatabase;

    public function test_company_user_can_only_access_own_tickets(): void
    {
        $companyA = Company::create([
            'name' => 'Company A',
        ]);

        $companyB = Company::create([
            'name' => 'Company B',
        ]);

        $userA = User::factory()->create([
            'company_id' => $companyA->id,
        ]);

        $this->actingAs($userA);

        Ticket::create([
            'title' => 'Company A Ticket',
        ]);

        auth()->logout();

        $userB = User::factory()->create([
            'company_id' => $companyB->id,
        ]);

        $this->actingAs($userB);

        $ticketB = Ticket::create([
            'title' => 'Company B Ticket',
        ]);

        $tickets = Ticket::all();

        $this->assertCount(1, $tickets);
        $this->assertEquals($ticketB->id, $tickets->first()->id);
        $this->assertEquals($companyB->id, $tickets->first()->company_id);
        $this->assertEquals('Company B Ticket', $tickets->first()->title);
    }
}
