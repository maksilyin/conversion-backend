<?php

namespace App\Events;

use App\Models\Task;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Task $task;
    /**
     * Create a new event instance.
     */
    public function __construct(Task $task)
    {
        $this->task = $task;
    }

    public function broadcastAs()
    {
        return 'TaskUpdated';
    }

    public function broadcastOn(): array
    {
        return [
            new Channel('task.'.$this->task->uuid)
        ];
    }

    public function broadcastWith(): array
    {
        return [
            'uuid' => $this->task->uuid,
            'type' => $this->task->type,
            'status' => $this->task->status,
            'payload' => $this->task->getPayload(),
        ];
    }
}
