<?php

namespace App\Modules\Admin\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Modules\CardUser\Notifications\Channels\SMSSolutionsMessage;

class LoanModified extends Notification
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

    return (new MailMessage)
      ->subject('Loan Processed!')
      ->greeting('Hello, ' . $notifiable->first_name . ',')
      ->line('A loan transaction of ' . to_naira($this->amount) . ' was processed on your account by Capital X on ' . now()->toDateString() . '. Contact support for more information on ' . config('app.email'));
  }

  /**
   * Get the database representation of the notification.
   *
   * @param mixed $notifiable
   */
  public function toDatabase($notifiable)
  {

    return [
      'action' => 'A loan transaction of ' . to_naira($this->amount) . ' was processed on your account.',
    ];
  }

  /**
   * Get the SMS representation of the notification.
   *
   * @param mixed $notifiable
   */
  public function toSMSSolutions($notifiable)
  {
    return (new SMSSolutionsMessage)
      ->sms_message('Hello ' . $notifiable->full_name . ', a loan transaction of ' . to_naira($this->amount) . ' was processed on your Capital X account on ' . now()->toDateString() . '.')
      ->to($notifiable->phone);
  }
}
