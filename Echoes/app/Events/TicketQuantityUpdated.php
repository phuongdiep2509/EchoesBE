<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TicketQuantityUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $ticketClassId;
    public $sold;
    public $total;
    public $eventId;

    /**
     * Create a new event instance.
     */
    public function __construct($ticketClassId, $sold, $total, $eventId)
    {
        $this->ticketClassId = $ticketClassId;
        $this->sold = $sold;
        $this->total = $total;
        $this->eventId = $eventId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('concert.' . $this->eventId),
        ];
    }
}
