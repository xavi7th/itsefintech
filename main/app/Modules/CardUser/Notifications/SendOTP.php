<?php

namespace App\Modules\CardUser\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\NexmoMessage;

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
	 * @param mixed $notifiable
	 * @return array
	 */
	public function via($notifiable)
	{
		return ['nexmo'];
	}

	/**
	 * Get the SMS representation of the notification.
	 *
	 * @param mixed $notifiable
	 * @return \Illuminate\Notifications\Messages\MailMessage
	 */
	public function toNexmo($notifiable)
	{
		return (new NexmoMessage)
			->content('Your CapitalX OTP code:' . $this->otp . '.');
	}
}
