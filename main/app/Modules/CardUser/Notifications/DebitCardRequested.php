<?php

namespace App\Modules\CardUser\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Modules\CardUser\Notifications\Channels\SMSSolutionsMessage;

class DebitCardRequested extends Notification
{
	use Queueable;

	protected $debit_card_type;

	/**
	 * Create a new notification instance.
	 *
	 * @return void
	 */
	public function __construct($debit_card_type)
	{
		$this->debit_card_type = $debit_card_type;
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
			->subject('Card Requested!')
			->greeting('Hello, ' . $notifiable->first_name . '.')
			->line('You just requested a new card.')
			->line('Our team will look into the request and you will get a notification when we respond.')
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
			'action' => ' New credit card requested.',

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
			->sms_message('We have received your request for a '. $this->debit_card_type->card_type_name . ' Card. Kindly log in our mobile app to track delivery updates. For more enquiries, call ' . config('app.phone'))
			->to($notifiable->phone);
	}
}
