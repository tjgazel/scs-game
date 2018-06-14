<?php

namespace App\Events;

use App\Models\Retailer;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class RetailerWeekEvent implements ShouldBroadcast
{
	use Dispatchable, InteractsWithSockets, SerializesModels;

	private $gameId;

	public $gameOff;

	/**
	 * Create a new event instance.
	 *
	 * @param              $gameId
	 */
	public function __construct($gameId)
	{
		$this->gameId = $gameId;

		$retailer = Retailer::where('game_id', $gameId)->first();

		if ($retailer->game->max_weeks == ($retailer->retailerWeeks()->count() - 1)) {
			$this->gameOff = true;
			$retailer->game->status = false;
			$retailer->game->save();
		} else {
			$this->gameOff = false;
		}
	}

	/**
	 * Get the channels the event should broadcast on.
	 *
	 * @return \Illuminate\Broadcasting\Channel|array
	 */
	public function broadcastOn()
	{
		return new Channel("RetailerWeekEvent.{$this->gameId}");
	}
}
