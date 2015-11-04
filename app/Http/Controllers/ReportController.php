<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller as BaseController;
use App\Http\Requests\ReportRequest;
use App\Models\Report;
use App\Models\SolarSystem;
use App\Models\User;

class ReportController extends BaseController
{
	public function index(Request $request)
	{
		if(!$request->ajax()) { app()->abort(404); }

		// Javascript returns timestamps in milliseconds, Carbon does not, so divide by 1000 if needed.
		$now   = \Carbon\Carbon::now('UTC');
		$since = (integer)$request->input('since', false);
		$since = $since && is_int($since)
			? \Carbon\Carbon::createFromTimestamp(strlen($since) === 13 ? $since / 1000 : $since)
			: \Carbon\Carbon::now('UTC')->subMinutes(10);

		$result = [
			'timestamp'      => $now->timestamp * 1000,
			'submitterCount' => User::uploadedRecently()->count(),
			'pollInterval'   => 5000,
			'reports'        => [],
		];

		$reports = Report::where('created_at', '>=', $since)->get();
		foreach($reports as $report) {
			$result['reports'][] = [
				'submitters'               => $report->uploaders->lists('characterName')->toArray(),
				'reporter'                 => $report->submitter,
				'textRaw'                  => $report->raw,
				'submitterCountAtCreation' => $report->uploaders->count(),
				'submittedAt'              => $report->created_at->timestamp * 1000,
				'systems'                  => $report->solarSystems->lists('solarSystemName')->toArray(),
				'textInterpreted'          => $report->interpreted,
			]; }

		return json_encode($result);
	}

	public function store(Request $request)
	{
		if(!($user = $this->getUserWithUploaderToken($request->input('uploader_token')))) {
			return response()->make(json_encode(['code' => 401, 'message' => trans('app.invalid_uploader_token')]), 401); }

		if($user->isBanned === true) {
			return response()->make(json_encode(['code' => 401, 'message' => trans('app.invalid_uploader_banned')]), 401); }

		if(!($user = $this->getUserWithUploaderToken($request->input('uploader_token')))) {
			return response()->make(json_encode(['code' => 401, 'message' => trans('app.invalid_uploader_token')]), 401); }

		if(!($data = $this->validateAndSplitString($request->input('text', false)))) {
			return response()->make(json_encode(['code' => 400, 'message' => trans('app.invalid_report_text')]), 400); }

		if(!($data = $this->validateTimestampIsRecent($data))) {
			return response()->make(json_encode(['code' => 400, 'message' => trans('app.invalid_report_timestamp', ['seconds' => 60])]), 400); }

		if(!($data = $this->validateMessageAgainstWordFilters($data, $word))) {
			return response()->make(json_encode(['code' => 400, 'message' => trans('app.invalid_report_filtered', ['word' => $word])]), 400); }

		if(!($data = $this->validateAndAddSystems($data, $word))) {
			return response()->make(json_encode(['code' => 400, 'message' => trans('app.invalid_report_systems')]), 400); }

		if(!$this->insertReportIntoDatabase($data, $user)) {
			return response()->make(json_encode(['code' => 500, 'message' => trans('app.invalid_report_insert')]), 500); }

		return response()->make('', 204);
	}

	private function getUserWithUploaderToken($uploader_token)
	{
		return User::where('uploader_token', $uploader_token)->first();
	}

	private function validateAndSplitString($data)
	{
		if(!preg_match('/^\[ (\d{4}\.\d{2}\.\d{2} \d{2}:\d{2}:\d{2}) \] (.*) > (.*)/', $data, $matches)) { return false; }

		if($matches[2] === 'Eve System') { return false; }

		return [
			'hash'      => md5($matches[3]),
			'raw'       => $matches[0],
			'timestamp' => \Carbon\Carbon::createFromFormat('Y.n.j G:i:s', $matches[1]),
			'submitter' => $matches[2],
			'message'   => $matches[3],
		];
	}

	private function validateTimestampIsRecent($data)
	{
		return $data['timestamp']->diffInSeconds(\Carbon\Carbon::now('UTC')) < 60 ? $data : false;
	}

	private function validateMessageAgainstWordFilters($data, &$word = null)
	{
		$tokens = explode(' ', $data['message']);
		$words  = ['clear', 'clr', 'status', 'status?'];

		foreach($tokens as $token) {
			if(in_array($token, $words)) {
				$word = $token;
				return false; } }

		return $data;
	}

	private function validateAndAddSystems($data, $user)
	{
		$data['interpreted'] = '';

		foreach(explode(' ', $data['message']) as $token) {
			if(strlen($token) >= 5 && ($system = SolarSystem::where('solarSystemName', "{$token}")->first())) {
				$data['systems'][$system->solarSystemID] = $system;
				$data['interpreted'] .= "<a href=\"javascript:mapSystemClicked('{$system->solarSystemName}');\">{$system->solarSystemName}</a> "; }
			else {$data['interpreted'] .= "{$token} "; } }

		$data['interpreted'] = trim($data['interpreted']);

		return count($data['systems']) > 0 ? $data : false;
	}

	private function insertReportIntoDatabase($data, $user)
	{
		return \DB::transaction(function() use($data, $user) {
			try {
				$now = \Carbon\Carbon::now('UTC');
				$report = null;

				if(!($report = Report::where('hash', $data['hash'])->orderBy('created_at', 'DESC')->first()) || $report->created_at->diffInSeconds($now) > 60) {
					$report = new Report;
					$report->hash        = $data['hash'       ];
					$report->submitter   = $data['submitter'  ];
					$report->raw         = $data['raw'        ];
					$report->interpreted = $data['interpreted'];
					$report->save();

					foreach($data['systems'] as $system) { $report->solarSystems()->attach($system); } }

				$report->uploaders()->attach($user);
				$user->uploaded_at = $now;
				$user->save();

				return true; }

			catch(\Exception $e) {
				return false; }
		});
	}
}
