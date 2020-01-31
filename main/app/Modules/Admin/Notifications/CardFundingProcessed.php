<?php

namespace App\Modules\Admin\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class CardFundingProcessed extends Notification
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
			->subject('Card Funding Request Processed!')
			->greeting('Hurray, ' . $notifiable->first_name . '!')
			->line('Your card funding request of ' . $this->amount . ' has been processed')
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
			'action' => $this->amount . ' card funding request processed.',

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
			->sms_message('Your requested card funds of ' . $this->amount . ' has been processed.')
			->to($notifiable->phone);
	}
}
