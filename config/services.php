<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Third Party Services
	|--------------------------------------------------------------------------
	|
	| This file is for storing the credentials for third party services such
	| as Stripe, Mailgun, Mandrill, and others. This file provides a sane
	| default location for this type of information, allowing packages
	| to have a conventional place to find your various credentials.
	|
	*/
        'facebook' => [
        'client_id' =>  env('FACEBOOK_APP_ID','994531767281439'),
        'client_secret' => env('FACEBOOK_APP_SECRET','6cccafa820b5769cd46d7ddd875e0cec'),
        'redirect' => env('FACEBOOK_REDIRECT','http://www.sameroute.in/social/login/facebook'),
        ],
    'google' => [
            'client_id' => '97422807838-7a929si041u869oi113pik0bt10ihdg1.apps.googleusercontent.com',
            'client_secret' => 'jCa6q2U1Ql72uzmkKwworqgF',
            'redirect' => 'http://www.sameroute.in/social/login/google',
        ],
	'mailgun' => [
		'domain' => '',
		'secret' => '',
	],

	'mandrill' => [
		'secret' => '',
	],

	'ses' => [
		'key' => '',
		'secret' => '',
		'region' => 'us-east-1',
	],

	'stripe' => [
		'model'  => 'App\User',
		'secret' => '',
	],

];
