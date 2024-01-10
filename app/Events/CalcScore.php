<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\TournamentMatch;

class CalcScore
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public TournamentMatch $tournamentMatch;
    /**
     * Create a new event instance.
     */
    public function __construct(TournamentMatch $tournamentMatch)
    {
        $this->tournamentMatch = $tournamentMatch;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [new PrivateChannel('channel-name')];
    }
}
