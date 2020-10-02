<?php

namespace App\Modules\CardUser\Notifications;

use Illuminate\Bus\Queueable;
use App\Modules\CardUser\Models\CardUser;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Modules\CardUser\Notifications\Channels\TermiiSMSMessage;

class UserCreditLimitSet extends Notification
{
  use Queueable;

  public $card_user;
  public $amount;
  public $interest_rate;


  /**
   * Create a new notification instance.
   *
   * @return void
   */
  public function __construct(CardUser $card_user, float $amount, float $interest_rate)
  {
    $this->amount = $amount;
    $this->card_user = $card_user;
    $this->interest_rate = $interest_rate;
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
      ->subject('Credit Limit Set!')
      ->greeting('Hello, ' . $notifiable->first_name . '.')
      ->line('A credit limit of ' . $this->amount . ' has been assigned to you. Kindly login to your app to make a request')
      ->line('Thank you for using our application.')
      ->salutation('Capital X Team.');
  }

  /**
   * Get the database representation of the notification.
   *
   * @param mixed $notifiable
   */
  public function toDatabase($notifiable)
  {
    return [
      'action' =>  'A credit limit of ' . $this->amount . ' has been assigned to you. Kindly login to your app to make a request. Capital X Team.',

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
      ->sms_message('A credit limit of ' . $this->amount . ' has been assigned to you. Kindly login to your app to make a request. Capital X Team.')
      ->to($card_user->phone);
  }
}
