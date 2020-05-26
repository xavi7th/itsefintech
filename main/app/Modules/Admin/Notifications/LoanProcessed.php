<?php

namespace App\Modules\Admin\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Modules\CardUser\Notifications\Channels\SMSSolutionsMessage;

class LoanProcessed extends Notification
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
   * @param mixed $notifiable
   * @return array
   */
  public function via($notifiable)
  {
    return ['mail', 'database', SMSSolutionsMessage::class];
  }

  /**
   * Get the mail representation of the notification.
   *
   * @param mixed $notifiable
   * @return \Illuminate\Notifications\Messages\MailMessage
   */
  public function toMail($notifiable)
  {
    if ($this->is_school_fees) {
      return (new MailMessage)
        ->subject('Loan Processed!')
        ->greeting('Hello, ' . $notifiable->first_name . ',')
        ->line('your school fees loan of ' . to_naira($this->amount) . ' has been processed  by Capital X on ' . now()->toDateString() . '.');
    } else {
      return (new MailMessage)
        ->subject('Loan Processed!')
        ->greeting('Hello, ' . $notifiable->first_name . ',')
        ->line('your card has been credited with ' . to_naira($this->amount) . ' by Capital X on ' . now()->toDateString() . ' Kindly visit an ATM close to you for full card balance.');
    }
  }

  /**
   * Get the database representation of the notification.
   *
   * @param mixed $notifiable
   */
  public function toDatabase($notifiable)
  {
    if ($this->is_school_fees) {
      return [
        'action' => to_naira($this->amount) . ' school fees processed. Documentation will be sent across to you.',

      ];
    } else {
      return [
        'action' => to_naira($this->amount) . ' loan processed. The amount is available for immediate access on your credit card',

      ];
    }
  }

  /**
   * Get the SMS representation of the notification.
   *
   * @param mixed $notifiable
   */
  public function toSMSSolutions($notifiable)
  {
    if ($this->is_school_fees) {
      return (new SMSSolutionsMessage)
        ->sms_message('Your school fees for ' . to_naira($this->amount) . ' has just been paid. Documents will be sent across to you. Keep studying')
        ->to($notifiable->phone);
    } else {
      return (new SMSSolutionsMessage)
        ->sms_message('Hello ' . $notifiable->full_name . ', your card has been credited with ' . to_naira($this->amount) . ' by Capital X on ' . now()->toDateString() . ' Kindly visit an ATM close to you for full card balance.')
        ->to($notifiable->phone);
    }
  }
}
