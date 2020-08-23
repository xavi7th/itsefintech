<?php

namespace App\Modules\CardUser\Notifications;

use Illuminate\Bus\Queueable;
use App\Modules\CardUser\Models\CardUser;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Modules\CardUser\Notifications\Channels\TermiiSMSMessage;

class VoucherRequested extends Notification
{
  use Queueable;

  /**
   * Create a new notification instance.
   *
   * @return void
   */
  public function __construct()
  {
    //
  }


  /**
   * Get the notification's delivery channels.
   *
   * @param CardUser $card_user
   * @return array
   */
  public function via($card_user)
  {
    return ['database', TermiiSMSMessage::class];
  }

  /**
   * Get the mail representation of the notification.
   *
   * @param CardUser $card_user
   * @return \Illuminate\Notifications\Messages\MailMessage
   */
  public function toMail($card_user)
  {

    return (new MailMessage)
      ->subject('Merchant Credit Requested!')
      ->greeting('Hello ' . $card_user->first_name . ',')
      ->line('We’ve received your request for ' . $this->amount . ' school fees credit.')
      ->line('We will notify you when it is approved and and accessible as a voucher for usage.')
      ->line('Regards')
      ->salutation('Capital X Card Team');
  }

  /**
   * Get the database representation of the notification.
   *
   * @param CardUser $card_user
   */
  public function toDatabase($card_user)
  {

    return [
      'action' => $card_user->merchant_limit . ' merchant credit requested.',

    ];
  }

  /**
   * Get the SMS representation of the notification.
   *
   * @param mixed $card_user
   */
  public function toTermiiSMS($card_user)
  {
    return (new TermiiSMSMessage)
      ->sms_message('Dear ' . $card_user->full_name . ', we received your merchant credit request of ' . $card_user->merchant_limit  . '.')
      ->to($card_user->phone);
  }
}
