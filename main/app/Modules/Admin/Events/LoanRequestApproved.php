<?php

namespace App\Modules\Admin\Events;

use Illuminate\Queue\SerializesModels;
use App\Modules\CardUser\Models\CardUser;

class LoanRequestApproved
{
  use SerializesModels;

  public $card_user;
  public $amount;
  public $is_school_fees;

  /**
   * Create a new event instance.
   *
   * @return void
   */
  public function __construct(CardUser $card_user, float $amount, bool $is_school_fees)
  {
    $this->amount = $amount;
    $this->card_user = $card_user;
    $this->is_school_fees = $is_school_fees;
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
