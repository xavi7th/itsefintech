<?php

namespace App\Modules\Admin\Events;

use Illuminate\Queue\SerializesModels;
use App\Modules\CardUser\Models\CardUser;

class LoanDisbursed
{
  use SerializesModels;

  public $card_user;
  public $amount;
  public $is_school_fees_loan;

  /**
   * Create a new event instance.
   *
   * @return void
   */
  public function __construct(CardUser $card_user, float $amount, bool $is_school_fees_loan)
  {
    $this->amount = $amount;
    $this->card_user = $card_user;
    $this->is_school_fees_loan = $is_school_fees_loan;
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
