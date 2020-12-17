<?php

namespace App\Modules\Accountant\Events;

use App\Modules\Admin\Models\Voucher;
use Illuminate\Queue\SerializesModels;
use App\Modules\Admin\Models\Merchant;

class MerchantRequestDebit
{
  use SerializesModels;

  public $voucher;
  public $merchant;

  /**
   * Create a new event instance.
   *
   * @return void
   */
  public function __construct(Voucher $voucher, Merchant $merchant)
  {
    $this->voucher = $voucher;
    $this->merchant = $merchant;
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
