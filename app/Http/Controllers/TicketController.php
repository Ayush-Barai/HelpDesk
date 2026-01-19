<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        // Start the query with the relationships to avoid N+1 performance issues
        $query = Ticket::with(['creator', 'agent']);

        // Role-based filtering
        if (auth()->user()->role !== 'agent') {
            // Employees only see their own tickets
            $query->where('created_by', auth()->id());
        }

        // Search by Subject
        if ($request->filled('search')) {
            $query->where('subject', 'like', '%'.$request->search.'%');
        }

        // Filter by Status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by Category
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        // Filter by Severity
        if ($request->filled('severity')) {
            $query->where('severity', $request->severity);
        }

        // Pagination + Query Persistence
        // 'withQueryString' ensures that when you click 'Next' page,
        // your filters (like status or category) stay in the URL.
        $tickets = $query->latest()->simplePaginate(6)->withQueryString();

        return view('tickets.index', compact('tickets'));
    }

    public function show(Ticket $ticket)
    {
        // Security: Only the owner or an agent can view
        if (auth()->user()->role !== 'agent' && auth()->id() !== $ticket->created_by) {
            abort(403);
        }

        $ticket->load(['creator', 'attachments', 'agent']);

        // Get agents list for the assignment dropdown
        $agents = User::where('role', 'agent')->get();

        return view('tickets.show', compact('ticket', 'agents'));
    }

    public function create()
    {
        // dd("Hello");
        return view('tickets.create');
    }

    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'subject' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|in:Access,Hardware,Network,Bug,Other',
            'severity' => 'required|integer|min:1|max:5',
            'attachments.*' => 'nullable|file|mimes:png,jpg,jpeg,pdf,txt,log|max:10240', // 10MB limit
        ]);
        // Create the Ticket
        $ticket = Ticket::create([
            'subject' => $request->subject,
            'description' => $request->description,
            'category' => $request->category,
            'severity' => $request->severity,
            'status' => 'Open',
            'created_by' => auth()->id(),
        ]);

        // Handle Attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('tickets/attachments', 'private');

                $ticket->attachments()->create([
                    'file_path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'file_type' => $file->getClientMimeType(),
                    'file_size' => $file->getSize(),
                ]);
            }
        }

        return redirect()->route('tickets.index')->with('success', 'Ticket created successfully!');
    }

    public function edit(Ticket $ticket)
    {
        if (auth()->id() !== $ticket->created_by) {
            abort(403);
        }

        return view('tickets.edit', compact('ticket'));
    }

    public function update(Request $request, Ticket $ticket)
    {
        // 1. Employee Edit Logic (Updating content)
        if (auth()->id() === $ticket->created_by && $ticket->status !== 'Closed') {
            $validated = $request->validate([
                'subject' => 'required|string|max:255',
                'description' => 'required|string',
                'category' => 'required|in:Access,Hardware,Network,Bug,Other',
                'severity' => 'required|integer|min:1|max:5',
            ]);

            $ticket->update($validated);

            return redirect()->route('tickets.show', $ticket)->with('success', 'Ticket updated successfully.');
        }

        // 2. Agent Update Logic (Updating status/assignment)
        if (auth()->user()->role === 'agent') {
            $validated = $request->validate([
                'status' => 'required|in:Open,In Progress,Resolved,Closed',
                'assigned_to' => 'nullable|exists:users,id',
            ]);

            $ticket->update([
                'status' => $validated['status'],
                'assigned_to' => $validated['assigned_to'], // This will be NULL if '-- Unassigned --' is picked
            ]);

            return redirect()->route('tickets.show', $ticket)->with('success', 'Ticket status updated.');
        }

        abort(403);
    }

    public function searchSimilar(Request $request)
    {
        $subject = $request->query('subject');

        if (strlen($subject) < 3) {
            return response()->json([]);
        }

        // Lane 1 logic:
        // 1. Shortlist: Only look at tickets created in the last 30 days
        // 2. Shortlist: Exclude 'Closed' tickets as they are less relevant for current issues
        // 3. Search: Use a simple 'LIKE' query (Classic approach)
        $similarTickets = Ticket::where('status', '!=', 'Closed')
            ->where('created_at', '>=', now()->subDays(30))
            ->where('subject', 'LIKE', "%{$subject}%")
            ->limit(5) // Requirement: "Top 5"
            ->get(['id', 'subject', 'status', 'category']);

        return response()->json($similarTickets);
    }
}
