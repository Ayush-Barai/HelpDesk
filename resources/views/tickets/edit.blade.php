<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Edit Ticket #{{ $ticket->id }}</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white p-6 shadow sm:rounded-lg">
                <form action="{{ route('tickets.update', $ticket) }}" method="POST">
                    @csrf
                    @method('PATCH')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Subject</label>
                        <input type="text" name="subject" value="{{ old('subject', $ticket->subject) }}" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" rows="5" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">{{ old('description', $ticket->description) }}</textarea>
                    </div>

                    <div class="flex items-center justify-between">
                        <a href="{{ route('tickets.show', $ticket) }}" class="text-gray-600 hover:underline">Cancel</a>
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700">
                            Update Ticket
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>