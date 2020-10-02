<?php

namespace App\Modules\CardUser\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\HtmlString;

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
      ->greeting('Hello John,')
										// ->greeting('Hello ' . $user->first_name . '.')
                    ->line('You are receiving this email because we received a password reset request for your account.')
      ->line(new HtmlString('Your password reset token is: <strong style="color:red;">' . $this->token . '<strong>'))
										->line('Enter this code into your app to reset your password')
                    ->line('If you did not request a password reset, no further action is required.')
    ->salutation(new HtmlString('Regards,<br>' . config('app.name')));
    }
}
