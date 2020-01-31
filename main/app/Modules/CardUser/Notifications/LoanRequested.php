<?php

namespace App\Modules\CardUser\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Modules\CardUser\Notifications\Channels\SMSSolutionsMessage;

class LoanRequested extends Notification
{
	use Queueable;

	protected $amount;
	protected $is_school_fees;

	/**
	 * Create a new notification instance.
	 * @param double $amount The loan amount requested
	 * @param boolean $is_school_fees Boolean indicating if this is a school fees loan request
	 *
	 * @return void
	 */
	public function __construct($amount, $is_school_fees = false)
	{
		$this->amount = $amount;
		$this->is_school_fees = $is_school_fees;
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
		if ($this->is_school_fees) {
			return (new MailMessage)
				->subject('Loan Requested!')
				->greeting('Hello, ' . $notifiable->first_name . '.')
				->line('You just requested a school fees loan of ' . $this->amount)
				->line('Our team will review the loan request, get in touch with the school you have entered in your records and get back to you on the decicion taken.')
				->line('Thank you for using our application!');
		} else {
			return (new MailMessage)
				->subject('Loan Requested!')
				->greeting('Hello, ' . $notifiable->first_name . '.')
				->line('You just requested a loan of ' . $this->amount)
				->line('Our team will review the loan request and get back to you on the decicion taken.')
				->line('Thank you for using our application!');
		}
	}

	/**
	 * Get the database representation of the notification.
	 *
	 * @param mixed $notifiable
	 */
	public function toDatabase($notifiable)
	{
		if ($this->is_school_fees) {
			return [
				'action' => $this->amount . ' loan requested for school fees.',

			];
		} else {
			return [
				'action' => $this->amount . ' loan requested.',

			];
		}
	}

	/**
	 * Get the SMS representation of the notification.
	 *
	 * @param mixed $notifiable
	 */
	public function toSMSSolutions($notifiable)
	{
		if ($this->is_school_fees) {
			return (new SMSSolutionsMessage)
				->sms_message('You just requested ' . $this->amount . ' loan for your school fees. We will look into your request ASAP')
				->to($notifiable->phone);
		} else {
			return (new SMSSolutionsMessage)
				->sms_message('You just requested a loan of ' . $this->amount . '. Our team will look into the request and you will get a notification when we respond.')
				->to($notifiable->phone);
		}
	}
}
