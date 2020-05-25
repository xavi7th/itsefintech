<?php

namespace App\Modules\CardUser\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Modules\CardUser\Notifications\Channels\SMSSolutionsMessage;

class VoucherDebited extends Notification
{
  use Queueable;

  protected $amount;
  protected $voucher_code;
  protected $merchant_name;

  /**
   * Create a new notification instance.
   *
   * @return void
   */
  public function __construct($amount, $voucher_code, $merchant_name)
  {
    $this->amount = $amount;
    $this->voucher_code = $voucher_code;
    $this->merchant_name = $merchant_name;
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
      ->subject('Voucher Debited!')
      ->greeting('Hello, ' . $notifiable->first_name . '!')
      ->line('Your voucher with code ' . $this->voucher_code . ' was just debited of ' . $this->amount . ' to pay your transaction with merchant ' . $this->merchant_name)
      ->line('If you believe this to be an error, kindly contact our support team of your account officer!')
      ->line('Thank you for using our application!');
  }


  /**
   * Get the database representation of the notification.
   *
   * @param mixed $notifiable
   */
  public function toDatabase($notifiable)
  {
    return [
      'action' => 'Your voucher with code ' . $this->voucher_code . ' was just debited of ' . $this->amount . ' to pay your transaction with merchant ' . $this->merchant_name,
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
      ->sms_message('Your voucher with code ' . $this->voucher_code . ' was just debited of ' . $this->amount . ' to pay your transaction with merchant ' . $this->merchant_name)
      ->to($notifiable->phone);
  }

  /**
   * Get the array representation of the notification.
   *
   * @param mixed $notifiable
   * @return array
   */
  public function toArray($notifiable)
  {
    return [
      //
    ];
  }
}
