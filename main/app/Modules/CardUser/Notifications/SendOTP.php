<?php

namespace App\Modules\CardUser\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Modules\CardUser\Notifications\Channels\TermiiSMSMessage;

class SendOTP extends Notification
{
  use Queueable;
  protected $otp;

  /**
   * Create a new notification instance.
   *
   * @return void
   */
  public function __construct($otp)
  {
    $this->otp = $otp;
  }

  /**
   * Get the notification's delivery channels.
   *
   * @param mixed $card_user
   * @return array
   */
  public function via($card_user)
  {
    // return ['nexmo'];
    return [TermiiSMSMessage::class];
  }

  /**
   * Get the SMS representation of the notification.
   *
   * @param mixed $card_user
   */
  public function toTermiiSMS($card_user)
  {
    return (new TermiiSMSMessage)
      ->sms_message('DO NOT DISCLOSE. Your Capital X OTP code for phone number confirmation is ' . $this->otp . '.')
      ->to($card_user->phone);
  }
}
