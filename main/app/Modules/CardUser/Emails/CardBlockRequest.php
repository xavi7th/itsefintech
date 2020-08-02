<?php

namespace App\Modules\CardUser\Emails;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Modules\CardUser\Models\CardUser;

class CardBlockRequest extends Mailable
{
  use Queueable, SerializesModels;

  public $user;
  public $card_block_reason;

  /**
   * Create a new message instance.
   *
   * @return void
   */
  public function __construct(CardUser $user, $card_block_reason)
  {
    $this->user = $user;
    $this->card_block_reason = $card_block_reason;
  }

  /**
   * Build the message.
   *
   * @return $this
   */
  public function build()
  {
    return $this
      ->view('carduser::emails.card-block-request');
  }
}
