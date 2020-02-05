<?php

namespace App\Modules\CardUser\Notifications;

use Illuminate\Bus\Queueable;
use App\Modules\CardUser\Models\DebitCard;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Modules\CardUser\Notifications\Channels\SMSSolutionsMessage;

class CardDebited extends Notification
{
    use Queueable;

    protected $trans_details;

	/**
	 * Create a new notification instance.
	 *
	 * @return void
	 */
	public function __construct(array $trans_details)
	{
		$this->trans_details = (object)$trans_details;
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
			->subject('Debit Alert!')
			->greeting('Dear ' . $notifiable->first_name . ',')
			->line('Your card '. DebitCard::find($this->trans_details->debit_card_id)->card_number .' has been debited with ' . to_naira($this->trans_details->amount) .' by ' . $this->trans_details->trans_description . ' on ' . now()->toDateTimeString() . '. Kindly visit an ATM close to you for full card balance.');

	}

	/**
	 * Get the database representation of the notification.
	 *
	 * @param mixed $notifiable
	 */
	public function toDatabase($notifiable)
	{

		return [
			'action' => DebitCard::find($this->trans_details->debit_card_id)->card_number .' debited ' . to_naira($this->trans_details->amount) .' by ' . $this->trans_details->trans_description . ' on ' . now()->toDateTimeString() . '.',
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
			->sms_message('Your card '. DebitCard::find($this->trans_details->debit_card_id)->card_number .' has been debited with ' . to_naira($this->trans_details->amount) .' by ' . $this->trans_details->trans_description . ' on ' . now()->toDateTimeString() . '. Kindly visit an ATM close to you for full card balance.')
			->to($notifiable->phone);
	}
}
