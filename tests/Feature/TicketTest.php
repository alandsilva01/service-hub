<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Company;
use App\Models\Project;
use App\Models\Ticket;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TicketTest extends TestCase
{
    use RefreshDatabase;

    public function test_ticket_belongs_to_project(): void
    {
        $company = Company::factory()->create();
        $project = Project::factory()->create(['company_id' => $company->id]);
        $user = User::factory()->create();
        $ticket = Ticket::factory()->create(['project_id' => $project->id, 'user_id' => $user->id]);

        $this->assertInstanceOf(Project::class, $ticket->project);
    }

    public function test_ticket_belongs_to_user(): void
    {
        $user = User::factory()->create();
        $company = Company::factory()->create();
        $project = Project::factory()->create(['company_id' => $company->id]);
        $ticket = Ticket::factory()->create(['project_id' => $project->id, 'user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $ticket->user);
    }

    public function test_authenticated_user_can_create_ticket(): void
    {
        $user = User::factory()->create();
        $company = Company::factory()->create();
        $project = Project::factory()->create(['company_id' => $company->id]);

        $response = $this->actingAs($user)->post(route('tickets.store'), [
            'project_id'  => $project->id,
            'title'       => 'Banho do Rex',
            'description' => 'Teste',
        ]);

        $response->assertRedirect(route('tickets.index'));
        $this->assertDatabaseHas('tickets', ['title' => 'Banho do Rex']);
    }

    public function test_unauthenticated_user_cannot_create_ticket(): void
    {
        $response = $this->post(route('tickets.store'), ['title' => 'Teste']);
        $response->assertRedirect(route('login'));
    }
}
