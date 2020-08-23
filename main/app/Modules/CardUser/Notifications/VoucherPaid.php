<?php

namespace App\Modules\CardUser\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Modules\CardUser\Notifications\Channels\TermiiSMSMessage;

class VoucherPaid extends Notification
{
  use Queueable;

  protected $amount;

  /**
   * Create a new notification instance.
   *
   * @return void
   */
  public function __construct($amount)
  {
    $this->amount = $amount;
  }

  /**
   * Get the notification's delivery channels.
   *
   * @param mixed $notifiable
   * @return array
   */
  public function via($notifiable)
  {
    return ['mail', 'database', TermiiSMSMessage::class];
  }

  /**
   * Get the mail representation of the notification.
   *
   * @param mixed $notifiable
   * @return \Illuminate\Notifications\Messages\MailMessage
   */
  public function toMail($notifiable)
  {
    return (new MailMessage)
      ->subject('Voucher Paid!')
      ->greeting('Hurray, ' . $notifiable->first_name . '!')
      ->line('You just cleared your pending merchant credit loan of ' . $this->amount)
      ->line('Thank you for using our application!');
  }


  /**
   * Get the database representation of the notification.
   *
   * @param mixed $notifiable
   */
  public function toDatabase($notifiable)
  {
    return [
      'action' => 'Outstanding merchant loan cleared. Amount: ' . $this->amount,

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
      ->sms_message('You just cleared your pending merchant credit loan of ' . $this->amount)
      ->to($card_user->phone);
  }
}
