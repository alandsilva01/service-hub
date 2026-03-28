<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Project;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TicketController extends Controller
{
    public function index()
    {
        $tickets = Ticket::with(['project.company', 'user'])
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Tickets/Index', [
            'tickets' => $tickets,
        ]);
    }

    public function create()
    {
        $projects = Project::with('company')->get();

        return Inertia::render('Tickets/Create', [
            'projects' => $projects,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id'  => 'required|exists:projects,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();
        $validated['status']  = 'open';

        Ticket::create($validated);

        return redirect()->route('tickets.index')
            ->with('success', 'Ticket criado com sucesso!');
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['project.company', 'user', 'ticketDetail']);

        return Inertia::render('Tickets/Show', [
            'ticket' => $ticket,
        ]);
    }
}
