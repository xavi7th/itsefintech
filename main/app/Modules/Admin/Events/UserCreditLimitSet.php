<?php

namespace App\Modules\Admin\Events;

use Illuminate\Queue\SerializesModels;
use App\Modules\CardUser\Models\CardUser;

class UserCreditLimitSet
{
  use SerializesModels;

  public $card_user;
  public $amount;
  public $interest_rate;

  /**
   * Create a new event instance.
   *
   * @return void
   */
  public function __construct(CardUser $card_user, float $amount, float $interest_rate)
  {
    $this->amount = $amount;
    $this->card_user = $card_user;
    $this->interest_rate = $interest_rate;
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
