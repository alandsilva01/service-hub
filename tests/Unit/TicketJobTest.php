<?php
namespace Tests\Unit;
use App\Jobs\ProcessTicketAttachment;
use App\Models\Company;
use App\Models\Project;
use App\Models\Ticket;
use App\Models\TicketDetail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;
class TicketJobTest extends TestCase
{
    use RefreshDatabase;
    public function test_job_creates_ticket_detail_from_json(): void
    {
        Storage::fake('local');
        $user = User::factory()->create();
        $company = Company::factory()->create();
        $project = Project::factory()->create(['company_id' => $company->id]);
        $ticket = Ticket::factory()->create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'attachment_path' => 'attachments/test.json',
        ]);
        Storage::disk('local')->put('attachments/test.json', '{"pet":"Rex","servico":"banho"}');
        (new ProcessTicketAttachment($ticket))->handle();
        $this->assertDatabaseHas('ticket_details', ['ticket_id' => $ticket->id]);
        $detail = TicketDetail::where('ticket_id', $ticket->id)->first();
        $data = json_decode($detail->enriched_data, true);
        $this->assertEquals('Rex', $data['pet']);
    }
}
