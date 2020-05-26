<?php

namespace App\Modules\Admin\Events;

use Illuminate\Queue\SerializesModels;
use App\Modules\CardUser\Models\CardUser;

class ManualLoanTransactionSet
{
  use SerializesModels;

  public $card_user;
  public $amount;
  public $transaction_type;

  /**
   * Create a new event instance.
   *
   * @return void
   */
  public function __construct(CardUser $card_user, float $amount, bool $transaction_type)
  {
    $this->amount = $amount;
    $this->card_user = $card_user;
    $this->transaction_type = $transaction_type;
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
