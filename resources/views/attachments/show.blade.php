<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Files for Ticket #{{ $ticket->id }}</h2>
            <a href="{{ route('tickets.show', $ticket) }}" class="text-sm text-indigo-600 hover:underline">Back to Ticket</a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                @foreach($ticket->attachments as $file)
                    <div class="bg-white p-4 shadow rounded-lg text-center flex flex-col items-center">
                        <div class="mb-3 text-4xl">
                            @if(str_contains($file->file_type, 'image')) 🖼️ @elseif(str_contains($file->file_type, 'pdf')) 📕 @else 📄 @endif
                        </div>
                        <p class="text-xs font-medium text-gray-700 truncate w-full mb-2" title="{{ $file->original_name }}">
                            {{ $file->original_name }}
                        </p>
                        <a href="{{ route('attachments.download', $file) }}" class="text-xs bg-indigo-50 text-indigo-600 px-3 py-1 rounded hover:bg-indigo-100">
                            Download
                        </a>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</x-app-layout>