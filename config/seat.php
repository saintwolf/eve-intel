<?php

return [

	'url'          => env('SEAT_URL', 'https://seat.example.com/api/v1/authenticate'),
	'username'     => env('SEAT_USERNAME'),
	'password'     => env('SEAT_PASSWORD'),
	'verify_ssl'   => env('SEAT_VERIFY_SSL', true),
	'corporations' => [],

];
