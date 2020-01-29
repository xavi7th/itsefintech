<?php

namespace App\Modules\CardUser\Notifications\Channels;

use GuzzleHttp\Client;
use Illuminate\Notifications\Notification;

class SMSSolutionsMessage
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
	public $sender;

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
	 * 0: Plain Text
	 * 1: Flash Plain Text
	 * 2: Unicode SMS
	 * 6: Unicode Flash SMS
	 *
	 * Note: 70 characters makes a page of 1- page Unicode SMS, while 63 characters makes a page for multiple pages Unicode SMS
	 * @var int
	 */
	public $type = 0;

	/**
	 *	Set how you want the phone numbers on DND to be handled.
	 *
	 *	Valid options are:
	 *
	 *	2 = Send all messages via the Basic Route. DND phone numbers are not charged
	 *	3 = Send message via the Basic route but send to those on DND via the Corporate Route.
	 *	4 = Use corporate route for all the phone numbers
	 *	5 = Use the SIM server for all the phone numbers Note: Note that the pricing of the corporate route is different from the basic route.
	 * 	6 = This option allows sending of SMS to all numbers on DND only via the Hosted SIM
	 *
	 * @var int
	 */
	public $routing = 3;

	/**
	 * The client reference.
	 * This is the access token that authorizes the delivery of the message. You can generate on our Access Token Page.
	 * https://smartsmssolutions.com/sms/api-x-tokens
	 *
	 * @var string
	 */
	public $token;

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
	 * Set the message type to be a flash message.
	 *
	 * @return $this
	 */
	public function flash_message()
	{
		$this->type = 1;
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
		$msg_obj = $notification->toSMSSolutions($notifiable);
		$msg_obj->token = config('services.sms_solutions.token');
		/** ! This will always overwrite the ID set by the from(). Refactor */
		$msg_obj->sender = config('services.sms_solutions.sms_sender');


		// Send notification to the $notifiable instance...
		$client = new Client();

		$url = config('services.sms_solutions.url');

		$response = $client->post($url, [
			'form_params' => [
				'sender' => $msg_obj->sender,
				'to' => $msg_obj->to,
				'type' => $msg_obj->type,
				'routing' => $msg_obj->routing,
				'message' => $msg_obj->message,
				'token' => $msg_obj->token,
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
