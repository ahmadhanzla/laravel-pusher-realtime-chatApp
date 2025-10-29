<?php

namespace App\Livewire;

use App\Events\MessageSent;
use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Chat extends Component
{
    public $users;
    public $selectedUser;
    public $newMessage = '';
    public $messages = [];
    public $loginID;

    public function mount()
    {
        $this->loginID = Auth::id();
        $this->users = User::where('id', '!=', $this->loginID)->latest()->get();
        $this->selectedUser = $this->users->first();

        if ($this->selectedUser) {
            $this->loadMessages();
        }
    }

    public function selectUser($id)
    {
        $this->selectedUser = User::find($id);

        if ($this->selectedUser) {
            $this->loadMessages();
        }
    }

    public function loadMessages()
    {
        if (!$this->selectedUser) {
            $this->messages = collect();
            return;
        }

        $this->messages = ChatMessage::query()
            ->where(function ($q) {
                $q->where('sender_id', Auth::id())
                  ->where('receiver_id', $this->selectedUser->id);
            })
            ->orWhere(function ($q) {
                $q->where('sender_id', $this->selectedUser->id)
                  ->where('receiver_id', Auth::id());
            })
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function submit()
    {
        if (!$this->newMessage || !$this->selectedUser) return;

        $message = ChatMessage::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $this->selectedUser->id,
            'message' => $this->newMessage,
        ]);

        $this->messages->push($message);

        // FIX — clear input
        $this->newMessage = '';
        $this->dispatch('$refresh');

        broadcast(new MessageSent($message))->toOthers();
    }


    public function updatedNewMessage($value)
    {
        if (!$this->selectedUser) return;   

        $this->dispatch('userTyping', [
            'userID' => $this->loginID,
            'userName' => Auth::user()->name,
            'selectedUserID' => $this->selectedUser->id,
        ]);
    }

    public function getListeners()
    {
        return [
            "echo-private:chat.{$this->loginID},MessageSent" => 'newChatMessageNotification',
        ];
    }

    public function newChatMessageNotification($payload)
    {
        $message = ChatMessage::find($payload['id'] ?? null);

        if ($message && $message->sender_id == $this->selectedUser->id) {
            $this->messages->push($message);
        }
    }

    public function render()
    {
        return view('livewire.chat');
    }
}
