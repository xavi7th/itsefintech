<?php

namespace App\Modules\Admin\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Modules\CardUser\Notifications\Channels\SMSSolutionsMessage;

class LoanProcessed extends Notification
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
				->subject('Loan Processed!')
				->greeting('Hurray, ' . $notifiable->first_name . '!')
				->line('CapitalX has just finished processing your school fees loan of ' . $this->amount)
				->line('Payments have been made to your institution, and all the necessary receipts collected. Our team will contact you concerning getting the necessary documentations to you. Remember to shun cultism and stay focused on your academic pursuits and the sky will be your limit.')
				->line('Thank you for using our application!');
		} else {
			return (new MailMessage)
				->subject('Loan Processed!')
				->greeting('Hurray, ' . $notifiable->first_name . '!')
				->line('We just finished processing your loan of ' . $this->amount)
				->line('The funds are now available on your card for spending.')
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
				'action' => $this->amount . ' school fees processed. Documentation will be sent across to you.',

			];
		} else {
			return [
				'action' => $this->amount . ' loan processed. The amount is available for immediate access on your credit card',

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
				->sms_message('Your school fees for ' . $this->amount . ' has just been paid. Documents will be sent across to you. Keep studying')
				->to($notifiable->phone);
		} else {
			return (new SMSSolutionsMessage)
				->sms_message('Your requested loan of ' . $this->amount . ' has been processed and is ready for immediate access on your credit card.')
				->to($notifiable->phone);
		}
	}
}
