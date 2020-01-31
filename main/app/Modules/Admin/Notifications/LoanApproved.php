<?php

namespace App\Modules\Admin\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Modules\CardUser\Notifications\Channels\SMSSolutionsMessage;

class LoanApproved extends Notification
{
	use Queueable;

	protected $amount;
	protected $Is_school_fees;


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
				->subject('Loan Request Approved!')
				->greeting('Hurray, ' . $notifiable->first_name . '!')
				->line('Your school fees loan request of ' . $this->amount . ' was just approved.')
				->line('Our team will contact your school authorities, make payments and reach out to you with the proof of payments. We hope you continue to have a condusive atmosphere as you pursue your academic dreams')
				->line('If we encounter any problems while trying to process the payment, a team member will get in touch with you.')
				->line('Thank you for using our application!');
		} else {
			return (new MailMessage)
				->subject('Loan Request Approved!')
				->greeting('Hello, ' . $notifiable->first_name . '.')
				->line('Your loan request of ' . $this->amount . ' has just been approved.')
				->line('The amount will be credited into your card of choice. We will notify you when this has been done.')
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
				'action' => $this->amount . ' loan requested for school fees has been approved. Payment process will begin immediately',
			];
		} else {
			return [
				'action' => $this->amount . ' loan requested has been approved. You will be notified when the anount is credited into your card.',
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
				->sms_message('Your school fees loan request of ' . $this->amount . ' has just been approved.')
				->to($notifiable->phone);
		} else {
			return (new SMSSolutionsMessage)
				->sms_message('Your loan request of ' . $this->amount . '. has just been approved.')
				->to($notifiable->phone);
		}
	}
}
