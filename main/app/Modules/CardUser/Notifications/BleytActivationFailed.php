<?php

namespace App\Modules\CardUser\Notifications;

use App\Modules\CardUser\Models\CardUser;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Modules\CardUser\Notifications\Channels\TermiiSMSMessage;

class BleytActivationFailed extends Notification implements ShouldQueue
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
      ->line('Attempts to activate your debit card failed becasue you are yet to update your profile.')
      ->line('Kindly log into your ' . config('app.name') . ' account and complete your KYC. For more information contact us at ' . config('app.email') . '.')
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
      'action' =>  'Debit Card activation failed. Update your details from your profile page. Capital X Team.',
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
      ->sms_message('Debit Card activation failed due to incomplete profile details. Kindly update your details by logging in to your account. Capital X Team.')
      ->to($cardUser->phone);
  }

  public function viaQueues()
  {
    return [
      'mail' => 'high',
      'database' => 'high',
      TermiiSMSMessage::class => 'low'
    ];
  }
}
