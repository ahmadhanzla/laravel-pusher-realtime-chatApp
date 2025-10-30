<div class="flex border border-gray-200 rounded-xl shadow-lg bg-white overflow-hidden" style="height: 100vh;">

    <!-- Left Sidebar: User List -->
    <div class="w-1/4 border-r border-gray-200 bg-gray-50 flex flex-col">
        <div class="p-4 font-semibold text-gray-800 border-b bg-white flex justify-between items-center">
            <span>Messages</span>
            <span class="text-xs text-gray-500">{{ $users->count() }} Users</span>
        </div>

        <div class="flex-1 overflow-y-auto">
            @forelse ($users as $user)
                <div
                    wire:click="selectUser({{ $user->id }})"
                    class="flex items-center gap-3 p-3 cursor-pointer transition-all duration-150
                        hover:bg-blue-100 {{ $selectedUser && $selectedUser->id === $user->id ? 'bg-blue-50 font-medium' : '' }}"
                >
                    <div class="relative">
                        <div class="w-10 h-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-semibold">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-green-500 border-2 border-white rounded-full"></span>
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="text-gray-800 truncate">{{ $user->name }}</div>
                        <div class="text-xs text-gray-500 truncate">{{ $user->email }}</div>
                    </div>
                </div>
            @empty
                <div class="p-4 text-sm text-gray-500 text-center">No users found.</div>
            @endforelse
        </div>
    </div>

    <!-- Right Panel: Chat Section -->
    <div class="w-3/4 flex flex-col bg-gray-50">

        @if ($selectedUser)
            <!-- Chat Header -->
            <div class="p-4 border-b border-gray-200 bg-white flex items-center gap-3 d-flex justify-between">
                <div style="display: flex; gap:10px;">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center text-blue-700 font-semibold">
                        {{ strtoupper(substr($selectedUser->name, 0, 1)) }}
                    </div>
                    <div>
                        <div class="text-gray-800 font-semibold">{{ $selectedUser->name }}</div>
                        <div class="text-xs text-gray-500">{{ $selectedUser->email }}</div>
                    </div>
                </div>

               <div>
                    <form method="POST" action="{{ route('logout') }}">
                            @csrf

                            <x-responsive-nav-link :href="route('logout')"
                                    onclick="event.preventDefault();
                                                this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-responsive-nav-link>
                    </form>
               </div>
            </div>

            <!-- Chat Messages -->
            <div
                id="chatBox"
                class="flex-1 p-4 overflow-y-auto space-y-3 bg-gray-100 scroll-smooth"
                wire:poll.5s="refreshMessages"
            >
                @forelse ($messages as $message)
                    <div class="flex {{ $message->sender_id === auth()->id() ? 'justify-end' : 'justify-start' }}">
                        <div
                            class="max-w-[70%] px-4 py-2 rounded-2xl shadow-sm
                                {{ $message->sender_id === auth()->id()
                                    ? 'bg-blue-600 text-white rounded-br-none'
                                    : 'bg-white text-gray-800 border border-gray-200 rounded-bl-none' }}"
                        >
                            <div class="text-sm">{{ $message->message }}</div>
                            <div class="text-[10px] text-gray-300 mt-1 text-right">
                                {{ $message->created_at->format('h:i A') }}
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-gray-500 text-sm mt-10">No messages yet. Start the conversation!</div>
                @endforelse
            </div>

            <!-- Chat Input -->
            <form
                wire:submit.prevent="submit"
                class="p-4 border-t border-gray-200 bg-white flex items-center gap-3"
            >
                <input
                    wire:model.defer="newMessage"
                    type="text"
                    class="flex-1 border border-gray-300 rounded-full px-4 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-400"
                    placeholder="Type a message..."
                />
                <button
                    type="submit"
                    class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-5 py-2 rounded-full transition-all duration-150"
                >
                    Send
                </button>
            </form>
        @else
            <!-- Empty State -->
            <div class="flex-1 flex items-center justify-center text-gray-400 text-sm">
                Select a user to start chatting.
            </div>
        @endif
    </div>
</div>

<!-- ✅ Add this script block at the bottom -->
@push('scripts')
<script>
    document.addEventListener('livewire:load', () => {
        // Attach Pusher socket ID to all Livewire outgoing requests
        Livewire.hook('message.send', (message, component) => {
            if (window.Echo && window.Echo.socketId()) {
                message.update({
                    headers: {
                        'X-Socket-Id': window.Echo.socketId()
                    }
                });
            }
        });

        // Auto-scroll to bottom when messages update
        Livewire.hook('message.processed', () => {
            const chatBox = document.getElementById('chatBox');
            if (chatBox) {
                chatBox.scrollTop = chatBox.scrollHeight;
            }
        });
    });
</script>
@endpush
