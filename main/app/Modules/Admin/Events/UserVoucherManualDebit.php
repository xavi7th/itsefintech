<?php

namespace App\Modules\Admin\Events;

use Illuminate\Queue\SerializesModels;
use App\Modules\CardUser\Models\CardUser;

class UserVoucherManualDebit
{
  use SerializesModels;

  public $voucher_code;
  public $merchant_name;
  public $card_user;
  public $amount;

  /**
   * Create a new event instance.
   *
   * @return void
   */
  public function __construct(string $voucher_code, string $merchant_name, CardUser $card_user, float $amount)
  {
    $this->voucher_code = $voucher_code;
    $this->amount = $amount;
    $this->card_user = $card_user;
    $this->merchant_name = $merchant_name;
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
