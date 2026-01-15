<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create New Support Ticket') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form action="/tickets/store" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-4">
                        <label for="subject" class="block text-sm font-medium text-gray-700">Subject</label>
                        <input type="text" name="subject" id="subject" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Briefly describe the issue (e.g., Cannot access VPN)">
                        @error('subject') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div id="similar-suggestions" class="hidden mt-2 p-4 bg-amber-50 border border-amber-200 rounded-md">
                        <p class="text-sm font-bold text-amber-800 mb-2">⚠️ Possible similar tickets already exist:</p>
                        <ul id="suggestions-list" class="text-sm text-amber-700 space-y-1 list-disc pl-5">
                            </ul>
                    </div>

                    <div class="mb-4">
                        <label for="description" class="block text-sm font-medium text-gray-700">Detailed Description</label>
                        <textarea name="description" id="description" rows="4" required
                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="Provide as much detail as possible..."></textarea>
                        @error('description') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                        <div>
                            <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                            <select name="category" id="category" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="Access">Access</option>
                                <option value="Hardware">Hardware</option>
                                <option value="Network">Network</option>
                                <option value="Bug">Bug</option>
                                <option value="Other">Other</option>
                            </select>
                        </div>

                        <div>
                            <label for="severity" class="block text-sm font-medium text-gray-700">Severity (1-5)</label>
                            <select name="severity" id="severity" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="1">1 - Low (Small Question)</option>
                                <option value="2">2 - Normal</option>
                                <option value="3">3 - High</option>
                                <option value="4">4 - Critical</option>
                                <option value="5">5 - Blocker (Work Stopped)</option>
                            </select>
                        </div>
                    </div>

                    <div class="mb-6" x-data="fileUpload()">
                        <label class="block text-sm font-medium text-gray-700">Attachments</label>
                        
                        <div 
                            class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-dashed rounded-md transition-colors"
                            :class="dragging ? 'border-indigo-500 bg-indigo-50' : 'border-gray-300'"
                            @dragover.prevent="dragging = true"
                            @dragleave.prevent="dragging = false"
                            @drop.prevent="handleDrop($event)"
                        >
                            <div class="space-y-1 text-center">
                                <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                    <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                <div class="flex text-sm text-gray-600">
                                    <label for="attachments" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500">
                                        <span>Upload files</span>
                                        <input id="attachments" name="attachments[]" type="file" class="sr-only" multiple x-ref="fileInput" @change="addFiles($event)">
                                    </label>
                                    <p class="pl-1">or drag and drop</p>
                                </div>
                                <p class="text-xs text-gray-500">PNG, JPG, PDF, TXT up to 10MB</p>
                            </div>
                        </div>

                        <template x-if="files.length > 0">
                            <div class="mt-4 space-y-2">
                                <p class="text-sm font-semibold text-gray-700">Selected Files:</p>
                                <template x-for="(file, index) in files" :key="index">
                                    <div class="flex items-center justify-between p-2 bg-gray-50 border rounded-md">
                                        <div class="flex items-center space-x-2 truncate">
                                            <span class="text-gray-500">📄</span>
                                            <span class="text-sm text-gray-700 truncate" x-text="file.name"></span>
                                            <span class="text-xs text-gray-400" x-text="'(' + (file.size / 1024 / 1024).toFixed(2) + ' MB)'"></span>
                                        </div>
                                        <button type="button" @click="removeFile(index)" class="text-red-500 hover:text-red-700 text-xs font-bold px-2">
                                            X
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </template>
                    </div>

                    <script>
                        function fileUpload() {
                            return {
                                files: [],
                                dragging: false,
                                addFiles(event) {
                                    const newFiles = Array.from(event.target.files);
                                    this.files = [...this.files, ...newFiles];
                                    this.syncInput();
                                },
                                handleDrop(event) {
                                    this.dragging = false;
                                    const droppedFiles = Array.from(event.dataTransfer.files);
                                    this.files = [...this.files, ...droppedFiles];
                                    this.syncInput();
                                },
                                removeFile(index) {
                                    this.files.splice(index, 1);
                                    this.syncInput();
                                },
                                syncInput() {
                                    // This is the "Magic": It creates a new FileList and assigns it back to the hidden input
                                    const dataTransfer = new DataTransfer();
                                    this.files.forEach(file => dataTransfer.items.add(file));
                                    this.$refs.fileInput.files = dataTransfer.files;
                                }
                            }
                        }

                        let debounceTimer;

                        document.getElementById('subject').addEventListener('input', function() {
                            const subject = this.value;
                            const suggestionsBox = document.getElementById('similar-suggestions');
                            const list = document.getElementById('suggestions-list');

                            clearTimeout(debounceTimer);

                            debounceTimer = setTimeout(() => {
                                if (subject.length < 3) {
                                    suggestionsBox.classList.add('hidden');
                                    return;
                                }

                                fetch(`/tickets/search-similar?subject=${encodeURIComponent(subject)}`)
                                    .then(response => response.json())
                                    .then(data => {
                                        if (data.length > 0) {
                                            list.innerHTML = ''; // Clear previous
                                            data.forEach(ticket => {
                                                const li = document.createElement('li');
                                                li.innerHTML = `<a href="/tickets/${ticket.id}" target="_blank" class="underline font-medium">${ticket.subject}</a> (${ticket.category} - ${ticket.status})`;
                                                list.appendChild(li);
                                            });
                                            suggestionsBox.classList.remove('hidden');
                                        } else {
                                            suggestionsBox.classList.add('hidden');
                                        }
                                    });
                            }, 500); // Wait 500ms after user stops typing
                        });
                    </script>

                    <div class="flex items-center justify-end">
                        <button type="submit" 
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 active:bg-indigo-900 focus:outline-none focus:border-indigo-900 focus:ring ring-indigo-300 disabled:opacity-25 transition ease-in-out duration-150">
                            Create Ticket
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</x-app-layout>
