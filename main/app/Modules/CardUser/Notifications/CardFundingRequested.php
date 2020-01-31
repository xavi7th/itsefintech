<?php

namespace App\Modules\CardUser\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Modules\CardUser\Notifications\Channels\SMSSolutionsMessage;

class CardFundingRequested extends Notification
{
	use Queueable;

	protected $amount;

	/**
	 * Create a new notification instance.
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
			->subject('Card Funding Requested!')
			->greeting('Hello, ' . $notifiable->first_name . '.')
			->line('You just requested a card funding of ' . $this->amount)
			->line('Our team will review the funding request and get back to you on the decicion taken.')
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
			'action' => $this->amount . ' card funding requested.',

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
			->sms_message('You just requested a card funding of ' . $this->merchant . '. Our team will look into the request and you will get a notification when we respond.')
			->to($notifiable->phone);
	}
}
