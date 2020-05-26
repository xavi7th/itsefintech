<?php

namespace App\Modules\Admin\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Modules\CardUser\Notifications\Channels\SMSSolutionsMessage;

class LoanApproved extends Notification
{
  use Queueable;

  protected $amount;
  protected $Is_school_fees;


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
    return ['mail', 'database', SMSSolutionsMessage::class];
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
        ->subject('Credit Approved!')
        ->greeting('Dear ' . $card_user->first_name . ',')
        ->line('your school fees credit request for ' . to_naira($this->amount) . ' has been processed. For more enquiries, call ' . config('app.phone'))
        ->line('Make early repayment to enjoy higher limits up to N500,000.')
        ->line('Do everything you love and more – on CREDIT!')
        ->line('Regards')
        ->line('Capital X Card Team');
    } else {
      return (new MailMessage)
        ->subject('Loan Request Approved!')
        ->greeting('Dear, ' . $card_user->full_name . '.')
        ->line('your credit request for ' . to_naira($this->amount) . ' has been processed & ready for immediate access on your card.')
        ->line('Make early repayment to enjoy higher limits up to N500,000.')
        ->line('Do everything you love and more – on CREDIT!')
        ->line('Regards')
        ->line('Capital X Card Team');
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
        'action' => to_naira($this->amount) . ' loan requested for school fees has been approved.',
      ];
    } else {
      return [
        'action' => to_naira($this->amount) . ' loan requested has been approved.',
      ];
    }
  }

  /**
   * Get the SMS representation of the notification.
   *
   * @param App\Modules\CardUser\Models\CardUser $card_user
   */
  public function toSMSSolutions($card_user)
  {
    if ($this->is_school_fees) {
      return (new SMSSolutionsMessage)
        ->sms_message('Hello ' . $card_user->full_name . ', your school fees credit request of ' . to_naira($this->amount) . ' has been processed & ready for immediate access on your card. For more enquiries, call ' . config('app.phone'))
        ->to($card_user->phone);
    } else {
      return (new SMSSolutionsMessage)
        ->sms_message('Hello ' . $card_user->full_name . ', your credit request of ' . to_naira($this->amount) . ' has been processed & ready for immediate access on your card. For more enquiries, call ' . config('app.phone'))
        ->to($card_user->phone);
    }
  }
}
