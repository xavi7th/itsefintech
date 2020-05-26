<?php

namespace App\Modules\Admin\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Modules\CardUser\Notifications\Channels\SMSSolutionsMessage;

class MerchantCreditApproved extends Notification
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
   * @param mixed $card_user
   * @return array
   */
  public function via($card_user)
  {
    return ['mail', 'database', SMSSolutionsMessage::class];
  }

  /**
   * Get the mail representation of the notification.
   *
   * @param mixed $card_user
   * @return \Illuminate\Notifications\Messages\MailMessage
   */
  public function toMail($card_user)
  {

    return (new MailMessage)
      ->subject('merchant Credit Approved!')
      ->greeting('Hello, ' . $card_user->first_name . '!')
      ->line('your credit request of ' . to_naira($this->amount) . ' has been processed & ready for immediate access on your card. For more enquiries, call ' . config('app.phone'));
  }

  /**
   * Get the database representation of the notification.
   *
   * @param mixed $card_user
   */
  public function toDatabase($card_user)
  {
    return [
      'action' => to_naira($card_user->merchant_limit) . ' voucher approved for your account.',

    ];
  }

  /**
   * Get the SMS representation of the notification.
   *
   * @param mixed $card_user
   */
  public function toSMSSolutions($card_user)
  {

    return (new SMSSolutionsMessage)
      ->sms_message('Hello ' . $card_user->full_name . ', your credit request of ' . to_naira($this->amount) . ' has been processed & ready for immediate access on your as a voucher. For more enquiries, call ' . config('app.phone'))
      ->to($card_user->phone);
  }
}
