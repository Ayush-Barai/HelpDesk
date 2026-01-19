<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Ticket #{{ $ticket->id }}: {{ $ticket->subject }}
            </h2>
            <div class="flex items-center space-x-3">
                {{-- Edit Button: Only visible to the owner if the ticket isn't Closed --}}
                @if(auth()->id() === $ticket->created_by && $ticket->status !== 'Closed')
                    <a href="{{ route('tickets.edit', $ticket) }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 transition">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        Edit Ticket
                    </a>
                @endif
                
                {{-- Status Badge --}}
                <span class="px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider
                    {{ $ticket->status === 'Open' ? 'bg-yellow-100 text-yellow-800' : '' }}
                    {{ $ticket->status === 'In Progress' ? 'bg-blue-100 text-blue-800' : '' }}
                    {{ $ticket->status === 'Resolved' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $ticket->status === 'Closed' ? 'bg-gray-100 text-gray-800' : '' }}">
                    {{ $ticket->status }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            {{-- Flash Messages for Success --}}
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded shadow-sm" role="alert">
                    <p class="text-sm">{{ session('success') }}</p>
                </div>
            @endif

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                {{-- Left Column: Ticket Content --}}
                <div class="md:col-span-2 space-y-6">
                    
                    {{-- Description Card --}}
                    <div class="bg-white p-6 shadow sm:rounded-lg border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-100 pb-3 mb-4">Description</h3>
                        <p class="text-gray-700 text-base leading-relaxed whitespace-pre-wrap">{{ $ticket->description }}</p>
                    </div>

                    {{-- Attachments Card --}}
                    <div class="bg-white p-6 shadow sm:rounded-lg border border-gray-100">
                        <div class="flex justify-between items-center border-b border-gray-100 pb-3 mb-4">
                            <h3 class="text-lg font-semibold text-gray-900">Attachments ({{ $ticket->attachments->count() }})</h3>
                            @if($ticket->attachments->count() > 0)
                                <a href="{{ route('tickets.attachments', $ticket) }}" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                                    View Gallery
                                </a>
                            @endif
                        </div>

                        @if($ticket->attachments->count() > 0)
                            <ul class="divide-y divide-gray-100">
                                @foreach($ticket->attachments as $file)
                                    <li class="py-3 flex justify-between items-center hover:bg-gray-50 px-2 rounded-md transition">
                                        <div class="flex items-center space-x-3">
                                            <svg class="w-6 h-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                            <div class="flex flex-col">
                                                <span class="text-sm font-medium text-gray-700">{{ $file->original_name }}</span>
                                                <span class="text-xs text-gray-400">{{ number_format($file->file_size / 1024, 1) }} KB</span>
                                            </div>
                                        </div>
                                        <a href="{{ route('attachments.download', $file )}}" class="inline-flex items-center px-3 py-1 bg-indigo-50 text-indigo-600 text-xs font-bold rounded-md hover:bg-indigo-100 transition">
                                            Download
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <div class="text-center py-6">
                                <p class="text-sm text-gray-500 italic">No attachments provided for this ticket.</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Right Column: Meta Info & Actions --}}
                <div class="space-y-6">
                    
                    {{-- Metadata Card --}}
                    <div class="bg-white p-6 shadow sm:rounded-lg border border-gray-100">
                        <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-100 pb-3 mb-4">Ticket Details</h3>
                        <dl class="space-y-4 text-sm">
                            <div class="flex justify-between">
                                <dt class="font-medium text-gray-500">Category:</dt>
                                <dd class="text-gray-900 font-semibold">{{ $ticket->category }}</dd>
                            </div>
                            <div class="flex justify-between">
                                <dt class="font-medium text-gray-500">Severity:</dt>
                                <dd class="text-gray-900 font-semibold">Level {{ $ticket->severity }}</dd>
                            </div>
                            <div class="flex justify-between border-t border-gray-50 pt-2">
                                <dt class="font-medium text-gray-500">Created By:</dt>
                                <dd class="text-gray-900">{{ $ticket->creator->name }}</dd>
                            </div>
                            <div class="flex justify-between border-t border-gray-50 pt-2">
                                <dt class="font-medium text-gray-500">Date Created:</dt>
                                <dd class="text-gray-900">{{ $ticket->created_at->format('M d, Y H:i') }}</dd>
                            </div>
                            <div class="flex justify-between border-t border-gray-50 pt-2">
                                <dt class="font-medium text-gray-500">Assigned To:</dt>
                                <dd class="text-gray-900 font-bold italic">
                                    {{ $ticket->agent ? $ticket->agent->name : 'Unassigned' }}
                                </dd>
                            </div>
                        </dl>

                        {{-- Agent Only: Assignment & Status Form --}}
                        @if(auth()->user()->role === 'agent')
                            <div class="mt-8 border-t border-gray-100 pt-6">
                                <h4 class="text-xs font-bold text-indigo-600 uppercase tracking-widest mb-4">Agent Actions</h4>
                                <form action="{{ route('tickets.update', $ticket) }}" method="POST" class="space-y-5">
                                    @csrf
                                    @method('PATCH')
                                    
                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Update Status</label>
                                        <select name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                            <option value="Open" {{ $ticket->status == 'Open' ? 'selected' : '' }}>Open</option>
                                            <option value="In Progress" {{ $ticket->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                            <option value="Resolved" {{ $ticket->status == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                                            <option value="Closed" {{ $ticket->status == 'Closed' ? 'selected' : '' }}>Closed</option>
                                        </select>
                                    </div>

                                    <div>
                                        <label class="block text-xs font-semibold text-gray-700 uppercase mb-1">Assign Ticket</label>
                                        <select name="assigned_to" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-sm focus:ring-indigo-500 focus:border-indigo-500">
                                            <option value="">-- Unassigned --</option>
                                            @foreach($agents as $agent)
                                                <option value="{{ $agent->id }}" {{ $ticket->assigned_to == $agent->id ? 'selected' : '' }}>
                                                    {{ $agent->name }} {{ auth()->id() === $agent->id ? '(You)' : '' }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <button type="submit" class="w-full inline-flex justify-center items-center px-4 py-2.5 bg-indigo-600 border border-transparent rounded-md font-bold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none shadow-sm transition">
                                        Update Ticket
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>

                    {{-- Navigation Hint --}}
                    <div class="px-2">
                        <a href="{{ route('tickets.index') }}" class="text-xs text-gray-500 hover:text-indigo-600 flex items-center transition">
                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                            Back to Ticket List
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>