<?php

return [

	/*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

	'mailgun' => [
		'domain' => env('MAILGUN_DOMAIN'),
		'secret' => env('MAILGUN_SECRET'),
		'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
	],

	'postmark' => [
		'token' => env('POSTMARK_TOKEN'),
	],

	'ses' => [
		'key' => env('AWS_ACCESS_KEY_ID'),
		'secret' => env('AWS_SECRET_ACCESS_KEY'),
		'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
	],
	'nexmo' => [
		'sms_from' => '15556666666',
	],
	'sms_solutions' => [
		'url' => 'https://smartsmssolutions.com/api/json.php',
		'sms_sender' => 'Capital X',
		'token' => env('SMS_SOLUTIONS_TOKEN', '')
	],
	'twilio' => [
		'username' => env('TWILIO_USERNAME'), // optional when using auth token
		'password' => env('TWILIO_PASSWORD'), // optional when using auth token
		'auth_token' => env('TWILIO_AUTH_TOKEN'), // optional when using username and password
		'account_sid' => env('TWILIO_ACCOUNT_SID'),
		'from' => env('TWILIO_FROM'), // optional
	],
	'termii_sms' => [
		'endpoint' => 'https://termii.com/api/sms/send', // optional when using auth token
		'username' => env('TERMII_EMAIL'), // optional when using auth token
		'password' => env('TERMII_PASSWORD'), // optional when using auth token
		'api_key' => env('TERMII_API_KEY'), // optional when using username and password
		'from' => env('TERMII_SENDER_ID'), // optional
	],
  'bleyt' => [
    'endpoint' => 'https://termii.com/api/sms/send',
    'create_wallet_endpoint' => env('CREATE_WALLET_ENDPOINT'),
    'credit_wallet_endpoint' => env('CREDIT_WALLET_ENDPOINT'),
    'debit_wallet_endpoint' => env('DEBIT_WALLET_ENDPOINT'),
    'view_transactions_endpoint' => env('VIEW_TRANSACTIONS_ENDPOINT'),
    'issue_card_endpoint' => env('ISSUE_CARD_ENDPOINT'),
    'activate_card_endpoint' => env('ACTIVATE_CARD_ENDPOINT'),
    'check_card_balance_endpoint' => env('CHECK_CARD_BALANCE_ENDPOINT'),
    'fund_card_endpoint' => env('FUND_CARD_ENDPOINT'),
    'username' => env('BLEYT_USERNAME'),
    'password' => env('BLEYT_PASSWORD'),
    'secret_key' => env('BLEYT_SECRET_KEY'),
    'public_key' => env('BLEYT_PUBLIC_KEY'),

  ],

];
