<?php

namespace App\Modules\CardUser\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Modules\CardUser\Notifications\Channels\TermiiSMSMessage;

class LoanRequested extends Notification
{
  use Queueable;

  protected $amount;
  protected $is_school_fees;

  /**
   * Create a new notification instance.
   * @param double $amount The loan amount requested
   * @param boolean $is_school_fees Boolean indicating if this is a school fees loan request
   *
   * @return void
   */
  public function __construct($amount, $is_school_fees = false)
  {
    $this->amount = $amount;
    $this->is_school_fees = $is_school_fees;
  }

  /**
   * Get the notification's delivery channels.
   *
   * @param App\Modules\CardUser\Models\CardUser $card_user
   * @return array
   */
  public function via($card_user)
  {
    return ['mail', 'database', TermiiSMSMessage::class];
  }

  /**
   * Get the mail representation of the notification.
   *
   * @param App\Modules\CardUser\Models\CardUser $card_user
   * @return \Illuminate\Notifications\Messages\MailMessage
   */
  public function toMail($card_user)
  {
    if ($this->is_school_fees) {
      return (new MailMessage)
        ->subject('Credit Request!')
        ->greeting('Hello ' . $card_user->first_name . ',')
        ->line('We’ve received your request for ' . $this->amount . ' school fees credit.')
        ->line('We will notify you when it is approved and payments have been made.')
        ->line('Regards')
        ->salutation('Capital X Card Team');
    } else {
      return (new MailMessage)
        ->subject('Credit Requested!')
        ->greeting('Hello ' . $card_user->first_name . ',')
        ->line('We’ve received your request for ' . $this->amount . ' credit.')
        ->line('We will notify you when it is approved and accessible on your card for usage.')
        ->line('Regards')
        ->salutation('Capital X Card Team');
    }
  }

  /**
   * Get the database representation of the notification.
   *
   * @param App\Modules\CardUser\Models\CardUser $card_user
   */
  public function toDatabase($card_user)
  {
    if ($this->is_school_fees) {
      return [
        'action' => $this->amount . ' credit requested for school fees.',

      ];
    } else {
      return [
        'action' => $this->amount . ' credit requested.',

      ];
    }
  }

  /**
   * Get the SMS representation of the notification.
   *
   * @param mixed $card_user
   */
  public function toTermiiSMS($card_user)
  {
    if ($this->is_school_fees) {
      return (new TermiiSMSMessage)
        ->sms_message('Dear ' . $card_user->full_name . ', we received your credit request of ' . $this->amount . ' for your school fees.')
        ->to($card_user->phone);
    } else {
      return (new TermiiSMSMessage)
        ->sms_message('Dear ' . $card_user->full_name . ', we received your credit request of ' . $this->amount . '.')
        ->to($card_user->phone);
    }
  }
}
