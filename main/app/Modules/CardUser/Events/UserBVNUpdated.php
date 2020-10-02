<?php

namespace App\Modules\CardUser\Events;

use Illuminate\Queue\SerializesModels;
use App\Modules\CardUser\Models\CardUser;

class UserBVNUpdated
{
  use SerializesModels;

  public $cardUser;

  /**
   * Create a new event instance.
   *
   * @return void
   */
  public function __construct(CardUser $cardUser)
  {
    $this->cardUser = $cardUser;
  }

  /**
   * Get the channels the event should be broadcast on.
   *
   * @return array
   */
  public function broadcastOn()
  {
    return [];
  }
}
