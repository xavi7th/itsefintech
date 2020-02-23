<?php

namespace App\Modules\CardUser\Notifications;

use Twilio\Rest\Client;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Twilio\Exceptions\TwilioException;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use NotificationChannels\Twilio\TwilioChannel;
use NotificationChannels\Twilio\TwilioSmsMessage;
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
		// return [TwilioChannel::class];
	}

	public function toTwilio($notifiable)
	{
		$accountSid = env('TWILIO_ACCOUNT_SID');
		$authToken = env('TWILIO_AUTH_TOKEN');
		$twilioNumber = env('TWILIO_NUMBER');

		$client = new Client($accountSid, $authToken);

		try {
			$client->messages->create(
				$notifiable->phone,
				[
					"body" => 'DO NOT DISCLOSE. Your Capital X OTP code for phone number confirmation is ' . $this->otp . '.',
					"from" => $twilioNumber
					//   On US phone numbers, you could send an image as well!
					//  'mediaUrl' => $imageUrl
				]
			);
			Log::info('Message sent to ' . $twilioNumber);
		} catch (TwilioException $e) {
			Log::error(
				'Could not send SMS notification.' .
					' Twilio replied with: ' . $e
			);
		}
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
			->content(' DO NOT DISCLOSE. Your Capital X OTP code for phone number confirmation is ' . $this->otp . '.');
	}
}
