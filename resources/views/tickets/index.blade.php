@props(['tickets'])

<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ auth()->user()->role === 'agent' ? 'All Support Tickets' : 'My Tickets' }}
            </h2>
            @if(auth()->user()->role !== 'agent')
                <a href="{{ route('tickets.create') }}" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700 transition shadow-sm">
                    + New Ticket
                </a>
            @endif
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <div class="mb-6 bg-white p-4 rounded-lg shadow-sm border border-gray-100" x-data>
                <form action="{{ route('tickets.index') }}" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div class="md:col-span-1">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Search Subject</label>
                        <input type="text" name="search" value="{{ request('search') }}" 
                            @input.debounce.500ms="$el.form.submit()"
                            placeholder="Type to search..." 
                            class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Status</label>
                        <select name="status" onchange="this.form.submit()" class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500">
                            <option value="">All Statuses</option>
                            <option value="Open" {{ request('status') == 'Open' ? 'selected' : '' }}>Open</option>
                            <option value="In Progress" {{ request('status') == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                            <option value="Resolved" {{ request('status') == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                            <option value="Closed" {{ request('status') == 'Closed' ? 'selected' : '' }}>Closed</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Category</label>
                        <select name="category" onchange="this.form.submit()" class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500">
                            <option value="">All Categories</option>
                            @foreach(['Access', 'Hardware', 'Network', 'Bug', 'Other'] as $cat)
                                <option value="{{ $cat }}" {{ request('category') == $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-1">Severity</label>
                        <select name="severity" onchange="this.form.submit()" class="w-full border-gray-300 rounded-md text-sm focus:ring-indigo-500">
                            <option value="">All Severities</option>
                            @foreach(range(1,5) as $sev)
                                <option value="{{ $sev }}" {{ request('severity') == $sev ? 'selected' : '' }}>Level {{ $sev }}</option>
                            @endforeach
                        </select>
                    </div>
                </form>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Subject</th>
                            @if(auth()->user()->role === 'agent')
                                <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Employee</th>
                            @endif
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Severity</th>
                            <th class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Created</th>
                            <th class="px-6 py-3 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($tickets as $ticket)
                            <tr class="hover:bg-gray-50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-semibold text-gray-900">{{ $ticket->subject }}</div>
                                </td>
                                @if(auth()->user()->role === 'agent')
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                                        {{ $ticket->creator->name }}
                                    </td>
                                @endif
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $ticket->category }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full
                                        {{ $ticket->status === 'Open' ? 'bg-yellow-100 text-yellow-800' : '' }}
                                        {{ $ticket->status === 'In Progress' ? 'bg-blue-100 text-blue-800' : '' }}
                                        {{ $ticket->status === 'Resolved' ? 'bg-green-100 text-green-800' : '' }}
                                        {{ $ticket->status === 'Closed' ? 'bg-red-100 text-red-800' : '' }}">
                                        {{ $ticket->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-sm font-medium
                                        {{ $ticket->severity == 5 ? 'text-red-600 font-bold' : '' }}
                                        {{ $ticket->severity == 4 ? 'text-orange-500' : '' }}
                                        {{ $ticket->severity <= 3 ? 'text-gray-600' : '' }}">
                                        Level {{ $ticket->severity }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $ticket->created_at->diffForHumans() }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <a href="{{ route('tickets.show', $ticket) }}" class="text-indigo-600 hover:text-indigo-900 font-bold">View Details</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center">
                                        <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 9.172L15 15M15 9.172l-5.828 5.828M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                        <p class="text-gray-500 text-lg">No tickets matching your filters were found.</p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $tickets->links() }}
            </div>
        </div>
    </div>
</x-app-layout>