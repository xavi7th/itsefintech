<?php

namespace App\Modules\CardUser\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Modules\CardUser\Notifications\Channels\TermiiSMSMessage;

class DebitCardRequested extends Notification
{
  use Queueable;

  protected $debit_card_type;

  /**
   * Create a new notification instance.
   *
   * @return void
   */
  public function __construct($debit_card_type)
  {
    $this->debit_card_type = $debit_card_type;
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
      ->subject('Card Request!')
      ->greeting('Dear ' . $notifiable->first_name . ',')
      ->line('Thank you for requesting for the ' . $this->debit_card_type->card_type_name . ' Card.')
      ->line('Your payment was successful, your request is being processed and your card will be delivered at the address you have provided. Kindly activate your card by changing your PIN in the nearest ATM. You can get updates on your card delivery on our app. Click here to download the Capital X app.')
      ->line('Kindly call ' . config('app.phone') . ' for enquiries.')
      ->line('Live your best life with Capital X card.')
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
      'action' => 'Your payment is successful and we have received your request for a ' . $this->debit_card_type->card_type_name . ' Card. Kindly activate your card by changing your PIN in the nearest ATM. Capital X Team',

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
      ->sms_message('Your payment is successful and we have received your request for a ' . $this->debit_card_type->card_type_name . ' Card. Kindly activate your card by changing your PIN in the nearest ATM. Capital X Team')
      ->to($card_user->phone);
  }
}
