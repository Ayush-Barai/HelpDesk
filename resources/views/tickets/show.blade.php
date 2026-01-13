<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Ticket #{{ $ticket->id }}: {{ $ticket->subject }}
            </h2>
            <span class="px-3 py-1 rounded-full text-sm font-bold 
                {{ $ticket->status === 'Open' ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800' }}">
                {{ $ticket->status }}
            </span>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2 space-y-6">
                    <div class="bg-white p-6 shadow sm:rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Description</h3>
                        <p class="text-gray-700 whitespace-pre-wrap">{{ $ticket->description }}</p>
                    </div>

                    <div class="bg-white p-6 shadow sm:rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Attachments</h3>
                        @if($ticket->attachments->count() > 0)
                            <ul class="divide-y divide-gray-200">
                                @foreach($ticket->attachments as $file)
                                    <li class="py-3 flex justify-between items-center">
                                        <div class="flex items-center">
                                            <svg class="w-6 h-6 text-gray-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                                            <span class="text-sm text-gray-600">{{ $file->original_name }}</span>
                                        </div>
                                        <a href="/attachments/download" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Download</a>
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <p class="text-sm text-gray-500 italic">No attachments provided.</p>
                        @endif
                    </div>
                </div>

                <div class="space-y-6">
                    <div class="bg-white p-6 shadow sm:rounded-lg">
                        <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Ticket Details</h3>
                        <div class="space-y-3 text-sm">
                            <p><span class="font-bold">Category:</span> {{ $ticket->category }}</p>
                            <p><span class="font-bold">Severity:</span> Level {{ $ticket->severity }}</p>
                            <p><span class="font-bold">Created By:</span> {{ $ticket->creator->name}}</p>
                            <p><span class="font-bold">Date:</span> {{ $ticket->created_at->format('M d, Y H:i') }}</p>
                        </div>

                        @if(auth()->user()->role === 'agent')
                            <hr class="my-6">
                            <form action="{{ route('tickets.update', $ticket) }}" method="POST" class="space-y-4">
                                @csrf
                                @method('PATCH')
                                
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Update Status</label>
                                    <select name="status" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="Open" {{ $ticket->status == 'Open' ? 'selected' : '' }}>Open</option>
                                        <option value="In Progress" {{ $ticket->status == 'In Progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="Resolved" {{ $ticket->status == 'Resolved' ? 'selected' : '' }}>Resolved</option>
                                        <option value="Closed" {{ $ticket->status == 'Closed' ? 'selected' : '' }}>Closed</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Assign To</label>
                                    <select name="assigned_to" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                        <option value="">Unassigned</option>
                                        @foreach($agents as $agent)
                                            <option value="{{ $agent->id }}" {{ $ticket->assigned_to == $agent->id ? 'selected' : '' }}>{{ $agent->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-md hover:bg-indigo-700 transition">
                                    Save Changes
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>