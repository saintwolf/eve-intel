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

		if((!$data = $this->authenticateWithSeAT($username, $password)) || $data->getStatusCode() !== 200) {
			return redirect()->back()->withInput()->withErrors([trans('app.invalid_credentials')]); }

		$data         = json_decode($data->getBody(), true);
		$corporations = config('seat.corporations');

		if($data['error'] === true) {
			return redirect()->back()->withInput()->withErrors([trans('app.invalid_credentials')]); }

		if(!isset($data['character']['characterID']) || !isset($data['character']['name'])) {
			return redirect()->back()->withInput()->withErrors([trans('app.invalid_main_character')]); }

		if(count($corporations) > 0 && !in_array($data['character']['corporationID'], $corporations)) {
			return redirect()->back()->withInput()->withErrors([trans('app.invalid_corporation')]); }

		$user = User::firstOrCreate([
			'characterID'   => $data['character']['characterID'],
			'characterName' => $data['character']['name'       ],
		]);

		if($user->isBanned) {
			return redirect()->back()->withInput()->withErrors([trans('app.invalid_uploader_banned')]); }

		$user->uploader_token = str_random(40);
		$user->save();

		auth()->loginUsingId($user->id, $remember_me);

		return redirect()->route('home');
	}

	private function authenticateWithSeAT($username, $password)
	{
		$method = filter_var($username, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

		try {
			$guzzle = new \GuzzleHttp\Client;

			return $guzzle->post(config('seat.url'), [
				'auth'   => [config('seat.username'), config('seat.password')],
				'verify' => config('seat.verify_ssl'),
				'form_params' => [$method    => $username, 'password' => $password],
			]); }

		catch(\GuzzleHttp\Exception\ClientException $e) {
			return false; }

		catch(\Exception $e) {
			throw app()->abort(500); }
	}
}
