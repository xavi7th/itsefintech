<?php

namespace App\Modules\CardUser\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Modules\CardUser\Notifications\Channels\TermiiSMSMessage;

class DebitCardActivated extends Notification
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
      ->subject('App Activated!')
      ->greeting('Hello, ' . $notifiable->first_name . '.')
      ->line('Your App has been successfully activated')
      ->line('One more step, please update your Profile and insert your BVN to secure your account and access credit up to NGN500,000 limit, Bills Payment and News on the go.')
      ->line('Do everything you love – on CREDIT!')
      ->line('Regards!')
      ->salutation('Capital X Card Team.!');
  }

  /**
   * Get the database representation of the notification.
   *
   * @param mixed $notifiable
   */
  public function toDatabase($notifiable)
  {

    return [
      'action' => 'Your app is successfully activated. One more step, please update your Profile and insert your BVN to secure your account and access credit. Capital X Team.',

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
      ->sms_message('Your app is successfully activated. One more step, please update your Profile and insert your BVN to secure your account and access credit. Capital X Team.')
      ->to($card_user->phone);
  }
}
