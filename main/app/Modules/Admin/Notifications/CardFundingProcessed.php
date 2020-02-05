<?php

namespace App\Modules\Admin\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Modules\CardUser\Notifications\Channels\SMSSolutionsMessage;

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
	 * @param App\Modules\CardUser\Models\CardUser $card_user
	 * @return array
	 */
	public function via($card_user)
	{
		return ['mail', 'database', SMSSolutionsMessage::class];
	}

	/**
	 * Get the mail representation of the notification.
	 *
	 * @param App\Modules\CardUser\Models\CardUser $card_user
	 * @return \Illuminate\Notifications\Messages\MailMessage
	 */
	public function toMail($card_user)
	{

		return (new MailMessage)
			->subject('Card Funding Request Processed!')
			->greeting('Dear ' . $card_user->first_name . '.')
			->line('Your card funding request of ' . $this->amount .' has been processed & is ready for immediate access on your card. For more enquiries, call ' . config('app.phone'));
	}

	/**
	 * Get the database representation of the notification.
	 *
	 * @param App\Modules\CardUser\Models\CardUser $card_user
	 */
	public function toDatabase($card_user)
	{

		return [
			'action' => $this->amount . ' card funding request processed.',

		];
	}

	/**
	 * Get the SMS representation of the notification.
	 *
	 * @param App\Modules\CardUser\Models\CardUser $card_user
	 */
	public function toSMSSolutions($card_user)
	{

		return (new SMSSolutionsMessage)
			->sms_message('Your card funding request of ' . $this->amount .' has been processed & is ready for immediate access on your card. For more enquiries, call ' . config('app.phone'))
			->to($card_user->phone);
	}
}
