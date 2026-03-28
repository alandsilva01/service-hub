<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketDetail extends Model
{
    protected $fillable = [
        'ticket_id', 'service_type', 'notes', 'enriched_data'
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }
}
