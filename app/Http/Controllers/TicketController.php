<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\User;
use Illuminate\Http\Request;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $query = Ticket::with(['creator', 'employee']);

        //Role-based filtering
        if (auth()->user()->isAgent()) {
            // Agents see all tickets
            // We can add search/filter logic here later
        } else {
            // Employees only see their own
            $query->where('created_by', auth()->id());
        }

        // simple search (by subject)
        if ($request->has('search')) {
            $query->where('subject', 'like', '%' . $request->search . '%');
        }

        //simple pagination (requested in assignment)
        $tickets = $query->latest()->simplePaginate(6);

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
    public function create(){
        // dd("Hello");
        return view("tickets.create");
    }
    public function store(Request $request)
    {
        //Validation
        $request->validate([
            'subject'     => 'required|string|max:255',
            'description' => 'required|string',
            'category'    => 'required|in:Access,Hardware,Network,Bug,Other',
            'severity'    => 'required|integer|min:1|max:5',
            'attachments.*' => 'nullable|file|mimes:png,jpg,jpeg,pdf,txt,log|max:10240', // 10MB limit
        ]);
        //Create the Ticket
        $ticket = Ticket::create([
            'subject'     => $request->subject,
            'description' => $request->description,
            'category'    => $request->category,
            'severity'    => $request->severity,
            'status'      => 'Open',
            'created_by'  => auth()->id(),
        ]);

        
        //Handle Attachments
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('tickets/attachments', 'private');

                $ticket->attachments()->create([
                    'file_path'     => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'file_type'     => $file->getClientMimeType(),
                    'file_size'     => $file->getSize(),
                ]);
            }
        }
        return redirect('/');
        // return redirect()->route('tickets')->with('success', 'Ticket created successfully!');
    }
    
    public function edit(Ticket $ticket) {
        if (auth()->id() !== $ticket->created_by) abort(403);
        return view('tickets.edit', compact('ticket'));
    }
    public function update(Request $request, Ticket $ticket)
    {
        // Only Agents can update status/assignment
        // if (auth()->user()->role !== 'agent' || auth()->id() !== $ticket->created_by) {
        //     abort(403);
        // }

        $validated = $request->validate([
            'subject'     => 'required|string|max:255',
            'description' => 'required|string',
            // 'category'    => 'required|in:Access,Hardware,Network,Bug,Other',
            // 'severity'    => 'required|integer|min:1|max:5',
            // 'attachments.*' => 'nullable|file|mimes:png,jpg,jpeg,pdf,txt,log|max:10240', // 10MB limi
        ]);

        $ticket->update($validated);

        return redirect('/');
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
