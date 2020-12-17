<?php

namespace App\Modules\Accountant\Events;

use Illuminate\Queue\SerializesModels;

class MerchantLoanPaid
{
  use SerializesModels;

  public $voucher_code;

  /**
   * Create a new event instance.
   *
   * @return void
   */
  public function __construct($voucher_code)
  {
    $this->voucher_code = $voucher_code;
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
