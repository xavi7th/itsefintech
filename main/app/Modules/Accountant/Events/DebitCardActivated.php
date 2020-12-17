<?php

namespace App\Modules\Accountant\Events;

use Illuminate\Queue\SerializesModels;

class DebitCardActivated
{
  use SerializesModels;

  /**
   * Create a new event instance.
   *
   * @return void
   */
  public function __construct()
  {
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
