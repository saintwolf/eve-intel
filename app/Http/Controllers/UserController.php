<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller as BaseController;
use App\Http\Requests\AuthRequest;
use App\Models\User;

class UserController extends BaseController
{
	public function getLogout()
	{
		auth()->user()->uploader_token = str_random(40);
		auth()->user()->save();
		auth()->logout();

		return redirect()->route('home');
	}

	public function getLogin()
	{
		return view('auth.login');
	}

	public function postLogin(AuthRequest $request)
	{
		$username    = $request->input('username'          );
		$password    = $request->input('password'          );
		$remember_me = $request->input('remember_me', false);

		// Get the user details from seat.
		$user = $this->authenticateUser($username, $password);

		if (is_integer($user)) {
			return redirect()->back()->withInput()->withErrors([$user['error']]); }

		$user = User::firstOrCreate([
			'characterID'   => $user['characterID'  ],
			'characterName' => $user['characterName'],
		]);

		if($user->isBanned) {
			return redirect()->back()->withInput()->withErrors([trans('app.invalid_uploader_banned')]); }

		$user->uploader_token = str_random(40);
		$user->save();

		auth()->loginUsingId($user->id, $remember_me);

		return redirect()->route('home');
	}

	/**
	 * Authenticates a user with seat and returns the user's details or an error code.
	 * @param  string $username
	 * @param  string $password
	 * @return array|integer
	 */
	private function authenticateUser($username, $password)
	{
		$url   = config('seat.url');
		$token = config('seat.token');
		$ssl   = config('seat.verify_ssl');

		curl_setopt_array(($curl = curl_init()), [
			CURLOPT_URL            => "{$url}/login",
			CURLOPT_HTTPHEADER     => [
				"X-Token: {$token}",
				"service: intel",
				"username: {$username}",
				"password: {$password}",
			],
			CURLOPT_POST           => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_SSL_VERIFYHOST => $ssl == true ? 2 : 0,
			CURLOPT_SSL_VERIFYPEER => $ssl == true ? 1 : 0,
		]);

		$response = json_decode(curl_exec($curl), true);
		curl_close($curl);

		if (!$response          ) { return 1001; }
		if (!$response['result']) { return $response['errno']; }

		return $response['data'];
	}
}
