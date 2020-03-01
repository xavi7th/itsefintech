<?php

namespace App\Modules\CardUser\Notifications\Channels;

use GuzzleHttp\Client;
use Illuminate\Notifications\Notification;

class TermiiSMSMessage
{
	/**
	 * The message content.
	 *
	 * @var string
	 */
	public $message;

	/**
	 * This is what the recipients of the messages
	 * will see as the sender of the message. This
	 * must be 11 characters or less. Longer
	 * sender ids are automatically shortened to
	 * meet the standard. This should be URL
	 * encoded when using the GET protocol.
	 *
	 * @var string
	 */
	public $from;

	/**
	 * These are the phone numbers the messages are to be sent to.
	 * Multiple phone numbers are to be separated by a comma ‘,’ or space.
	 * Large quantities of phone numbers can be called via the POST method.
	 *
	 * Recipients via the GET method are advised to be limited to 100 phone numbers at once.
	 * This is to avoid the possibilities of running into the HTTPS
	 * Error 400 (Bad Request - Request Too Long).
	 *
	 * @var string
	 */
	public $to;

	/**
	 * This specifies the type of message you want to send. Valid types are:
	 *
	 * plain, voice, mms, unicode
	 *
	 * Note: 70 characters makes a page of 1- page Unicode SMS, while 63 characters makes a page for multiple pages Unicode SMS
	 * @var int
	 */
	public $type = 'plain';

	/**
	 *	Set how you want the phone numbers on DND to be handled.
	 *
	 *	Valid options are:
	 *
	 * whatsapp, dnd, generic
	 *
	 * generic: this is an sms channel and you can register your preferred sender Ids on but delivery to phone numbers
	 * 					who have Do Not Disturb activated
	 * dnd: this is for OTP and product notifications, you Do not Disturb Ids
	 * whatsapp: is for WhatsApp
	 *
	 * DND represents Do-Not-Distrub and messages sent via this channel are blocked by telcom providers.
	 * To ensure your messages are allowed to deliver, apply for a sender ID through your dashboard.
	 *
	 * @var int
	 */
	public $channel = 'dnd';

	/**
	 * The API KEY.
	 * This is the access token that authorizes the delivery of the message. You can get your api_key from
	 * https://www.termii.com/dashboard
	 *
	 * @var string
	 */
	public $api_key;

	/**
	 * Create a new message instance.
	 *
	 * @param  string  $sms_message
	 * @return void
	 */
	public function __construct($sms_message = '')
	{
		$this->message = $sms_message;
	}

	/**
	 * Set the message content.
	 *
	 * @param  string  $sms_message
	 * @return $this
	 */
	public function sms_message($sms_message)
	{
		$this->message = $sms_message;
		return $this;
	}

	/**
	 * Set the phone number the message should be sent from.
	 *
	 * @param  string  $sender
	 * @return $this
	 */
	public function from($sender_id)
	{
		$this->sender = $sender_id;
		return $this;
	}

	/**
	 * Set the phone number the message should be sent from.
	 *
	 * @param  string  $sender
	 * @return $this
	 */
	public function to($recipients)
	{
		$this->to = $recipients;
		return $this;
	}

	/**
	 * Send the given notification.
	 *
	 * @param  mixed  $notifiable
	 * @param  \Illuminate\Notifications\Notification  $notification
	 * @return void
	 */
	public function send($notifiable, Notification $notification)
	{
		$msg_obj = $notification->toTermiiSMS($notifiable);
		$msg_obj->api_key = config('services.termii_sms.api_key');
		/** ! This will always overwrite the ID set by the from(). Refactor */
		$msg_obj->from = config('services.termii_sms.from');


		// Send notification to the $notifiable instance...
		$client = new Client();

		$url = config('services.termii_sms.endpoint');

		$response = $client->post($url, [
			'form_params' => [
				'from' => $msg_obj->from,
				'to' => $msg_obj->to,
				'type' => $msg_obj->type,
				'channel' => $msg_obj->channel,
				'sms' => $msg_obj->message,
				'api_key' => $msg_obj->api_key,
			]
		]);

		/**
		 * ? Create a DB table to save the response from the send attempt so that admins can see how the SMS gateway is working
		 */
		// $promise->then(
		// 	function (ResponseInterface $res) {
		// 		echo $res->getStatusCode() . "\n";
		// 	},
		// 	function (RequestException $e) {
		// 		echo $e->getMessage() . "\n";
		// 		echo $e->getRequest()->getMethod();
		// 	}
		// );

		// $response = $request->send();



		// dd($response->getBody());
	}
}
