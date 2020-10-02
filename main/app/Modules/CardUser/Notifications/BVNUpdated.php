<?php

namespace App\Modules\CardUser\Notifications;

use App\Modules\CardUser\Models\CardUser;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Modules\CardUser\Notifications\Channels\TermiiSMSMessage;

class BVNUpdated extends Notification
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
   * @param CardUser $cardUser
   * @return \Illuminate\Notifications\Messages\MailMessage
   */
  public function toMail($cardUser)
  {
    return (new MailMessage)
      ->subject('Profile Edited!')
      ->greeting('Hello, ' . $cardUser->first_name . '.')
      ->line('Your profile details was just changed on our platform.')
      ->line('BVN update successful! You will be assigned a credit limit in 24 hours or a member of our Credit Team will reach out to you.')
      ->line('Thank you for using our application!')
      ->salutation('Capital X Team');
  }

  /**
   * Get the database representation of the notification.
   *
   * @param mixed $notifiable
   */
  public function toDatabase($notifiable)
  {
    return [
      'action' =>  'BVN update successful! You will be assigned a credit limit in 24 hours or a member of our Credit Team will reach out to you. Capital X Team.',
    ];
  }

  /**
   * Get the SMS representation of the notification.
   *
   * @param CardUser $cardUser
   */
  public function toTermiiSMS($cardUser)
  {
    return (new TermiiSMSMessage)
      ->sms_message('BVN update successful! You will be assigned a credit limit in 24 hours or a member of our Credit Team will reach out to you. Capital X Team.')
      ->to($cardUser->phone);
  }
}
