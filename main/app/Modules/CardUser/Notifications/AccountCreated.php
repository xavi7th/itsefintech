<?php

namespace App\Modules\CardUser\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\NexmoMessage;

class AccountCreated extends Notification implements ShouldQueue
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
		return ['database', 'nexmo', 'mail'];
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
			// ->error() // btn goes red
			->subject('Account Created!')
			->greeting('Welcome, ' . $notifiable->first_name . '!')
			->line('The introduction to the notification.')
			->action('Notification Action', config('app.url'))
			->line('Thank you for using our application!');


		// return (new MailMessage)->view(
		// 	'emails.name',
		// 	['invoice' => $this->invoice]
		// );
	}

	/**
	 * Get the database representation of the notification.
	 *
	 * @param mixed $notifiable
	 * @return \Illuminate\Notifications\Messages\MailMessage
	 */
	public function toDatabase($notifiable)
	{
		return [
			'action' => 'Acccount Created',

		];
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
			->content('Welcome to Capital X ' . $notifiable->first_name . '. Your account has been verified. We will notify you of any updates in your account via SMS. Be good, make money.');
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
