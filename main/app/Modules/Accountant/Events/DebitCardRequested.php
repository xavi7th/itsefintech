<?php

namespace App\Modules\Accountant\Events;

use Illuminate\Queue\SerializesModels;

class DebitCardRequested
{
  use SerializesModels;

  public $debit_card_type;

  /**
   * Create a new event instance.
   *
   * @return void
   */
  public function __construct($debit_card_type)
  {
    $this->debit_card_type = $debit_card_type;
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
