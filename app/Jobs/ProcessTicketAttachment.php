<?php
namespace App\Jobs;

use App\Models\Ticket;
use App\Models\TicketDetail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Storage;

class ProcessTicketAttachment implements ShouldQueue
{
    use Queueable;

    public function __construct(public Ticket $ticket) {}

    public function handle(): void
    {
        if (!$this->ticket->attachment_path) return;

        $contents = Storage::disk('local')->get($this->ticket->attachment_path);
        $data = json_decode($contents, true);

        if (!$data) return;

        TicketDetail::updateOrCreate(
            ['ticket_id' => $this->ticket->id],
            ['enriched_data' => json_encode($data)]
        );
    }
}
