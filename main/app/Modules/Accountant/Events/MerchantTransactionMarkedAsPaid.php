<?php

namespace App\Modules\Accountant\Events;

use Illuminate\Queue\SerializesModels;

class MerchantTransactionMarkedAsPaid
{
  use SerializesModels;

  public $transaction;

  /**
   * Create a new event instance.
   *
   * @return void
   */
  public function __construct($transaction)
  {
    $this->transaction = $transaction;
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
