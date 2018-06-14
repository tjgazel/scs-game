<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class DistributorWeekEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

	private $gameId;

	/**
	 * Create a new event instance.
	 *
	 * @param $gameId
	 */
    public function __construct($gameId)
    {
		$this->gameId = $gameId;
	}

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new Channel("DistributorWeekEvent.{$this->gameId}");
    }
}
