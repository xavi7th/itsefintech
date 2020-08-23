<?php

namespace App\Modules\CardUser\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Modules\CardUser\Models\LoanRequest;
use Illuminate\Notifications\Messages\MailMessage;
use App\Modules\CardUser\Notifications\Channels\TermiiSMSMessage;

class LoanOverdue extends Notification
{
  use Queueable;

  protected $loan_request;

  public function __construct(LoanRequest $loan_request)
  {
    $this->loan_request = $loan_request;
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
      ->subject('Loan Overdue!')
      ->greeting('Hello, ' . $notifiable->first_name . ',')
      ->line('You are over due on your next scheduled payment of ' . to_naira($this->loan_request->breakdownStatistics()->scheduled_repayment_amount));
  }

  /**
   * Get the database representation of the notification.
   *
   * @param mixed $notifiable
   */
  public function toDatabase($notifiable)
  {

    return [
      'action' => 'You are over due on your next scheduled payment of ' . to_naira($this->loan_request->breakdownStatistics()->scheduled_repayment_amount)
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
      ->sms_message('Hello ' . $card_user->full_name . '. You are over due on your next scheduled payment of ' . to_naira($this->loan_request->breakdownStatistics()->scheduled_repayment_amount))
      ->to($card_user->phone);
  }
}
