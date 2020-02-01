<?php

namespace App\Modules\CardUser\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class SendPasswordResetNotification extends Notification
{
		use Queueable;

		protected $token;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($token)
    {
			$this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $user
     * @return array
     */
    public function via($user)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param mixed $user
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($user)
    {
				return (new MailMessage)
										->subject('Reset Password Notification')
										->greeting('Hello ' . $user->first_name . '.')
                    ->line('You are receiving this email because we received a password reset request for your account.')
                    ->line('Your password reset token is ' . $this->token)
										->line('Enter this code into your app to reset your password')
                    ->line('If you did not request a password reset, no further action is required.')
										->with('Regards,')
										->salutation(config('app.name'));
    }
}
