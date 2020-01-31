<?php

namespace App\Modules\CardUser\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Modules\CardUser\Notifications\Channels\SMSSolutionsMessage;

class VoucherApproved extends Notification
{
	use Queueable;

	protected $merchant;
	/**
	 * Create a new notification instance.
	 *
	 * @return void
	 */
	public function __construct($merchant_name)
	{
		$this->merchant = $merchant_name;
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
			->subject('Merchant Transaction Approved!')
			->greeting('Hello, ' . $notifiable->first_name . '.')
			->line('You just approved a transaction with ' . $this->merchant)
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
			'action' => $this->merchant . ' transaction approved.',

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
			->sms_message('You just approved a pending merchant transaction from ' . $this->merchant)
			->to($notifiable->phone);
	}
}
