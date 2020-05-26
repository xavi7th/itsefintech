<?php

namespace App\Modules\CardUser\Events;

use Illuminate\Queue\SerializesModels;
use App\Modules\CardUser\Models\LoanRequest;

class OverdueLoan
{
  use SerializesModels;

  public $loan_request;

  /**
   * Create a new event instance.
   *
   * @return void
   */
  public function __construct(LoanRequest $loan_request)
  {
    $this->loan_request = $loan_request;
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
