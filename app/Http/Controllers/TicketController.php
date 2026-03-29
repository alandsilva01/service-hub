<?php
namespace App\Http\Controllers;
use App\Models\Ticket;
use App\Models\Project;
use App\Jobs\ProcessTicketAttachment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with(['project.company', 'user'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $tickets = $query->get();

        return Inertia::render('Tickets/Index', [
            'tickets' => $tickets,
            'filters' => $request->only('status'),
        ]);
    }

    public function create()
    {
        $projects = Project::with('company')->get();
        return Inertia::render('Tickets/Create', ['projects' => $projects]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'project_id'  => 'required|exists:projects,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'attachment'  => 'nullable|file|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('attachment')) {
            $ext = $request->file('attachment')->getClientOriginalExtension();
            if (!in_array(strtolower($ext), ['json', 'txt'])) {
                return back()->withErrors(['attachment' => 'O arquivo deve ser .json ou .txt']);
            }
            $path = $request->file('attachment')->store('attachments', 'local');
        }

        $ticket = Ticket::create([
            'project_id'      => $validated['project_id'],
            'user_id'         => auth()->id(),
            'title'           => $validated['title'],
            'description'     => $validated['description'] ?? null,
            'status'          => 'open',
            'attachment_path' => $path,
        ]);

        if ($path) {
            ProcessTicketAttachment::dispatch($ticket);
        }

        return redirect()->route('tickets.index')
            ->with('success', 'Ticket criado com sucesso!');
    }

    public function show(Ticket $ticket)
    {
        $ticket->load(['project.company', 'user', 'detail']);
        return Inertia::render('Tickets/Show', ['ticket' => $ticket]);
    }

    public function edit(Ticket $ticket)
    {
        $projects = Project::with('company')->get();
        return Inertia::render('Tickets/Edit', [
            'ticket'   => $ticket->load('project.company'),
            'projects' => $projects,
        ]);
    }

    public function update(Request $request, Ticket $ticket)
    {
        $validated = $request->validate([
            'project_id'  => 'required|exists:projects,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'status'      => 'required|in:open,in_progress,closed',
        ]);

        $ticket->update($validated);

        return redirect()->route('tickets.show', $ticket->id)
            ->with('success', 'Ticket atualizado com sucesso!');
    }
}
