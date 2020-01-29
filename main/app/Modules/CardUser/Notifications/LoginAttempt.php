<?php

namespace App\Modules\CardUser\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Modules\CardUser\Notifications\Channels\SMSSolutionsMessage;

class LoginAttempt extends Notification
{
	use Queueable;

	/**
	 * Create a new notification instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//
	}

	/**
	 * Get the notification's delivery channels.
	 *
	 * @param mixed $notifiable
	 * @return array
	 */
	public function via($notifiable)
	{
		return ['database', 'mail', SMSSolutionsMessage::class];
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
			->subject('Login Alert')
			->greeting('Hello, ' . $notifiable->first_name . '.')
			->line('We just noticed a log in attempt on your account.')
			->line('If this was you, you are all set. It you don\'t recognise this activity, then reset your password using the link below!')
			->action('Reset Password', config('app.url'))
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
			'action' => 'Your account was logged into at ' . now()->toDateTimeString(),

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
			->sms_message('Hello ' . $notifiable->first_name . '. Your account was just logged into.')
			->to($notifiable->phone);
	}
}
