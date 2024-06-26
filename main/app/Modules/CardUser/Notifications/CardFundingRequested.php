<?php

namespace App\Modules\CardUser\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Modules\CardUser\Notifications\Channels\TermiiSMSMessage;

class CardFundingRequested extends Notification
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
      ->subject('Card Funding Requested!')
      ->greeting('Hello, ' . $notifiable->first_name . '.')
      ->line('We received your credit request of ' . $this->amount);
  }

  /**
   * Get the database representation of the notification.
   *
   * @param mixed $notifiable
   */
  public function toDatabase($notifiable)
  {

    return [
      'action' => $this->amount . ' card funding requested.',

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
      ->sms_message('Hello, ' . $card_user->first_name . '. We received your credit request of ' . $this->amount)
      ->to($card_user->phone);
  }
}
