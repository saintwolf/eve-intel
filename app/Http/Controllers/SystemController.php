<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller as BaseController;
use App\Models\Region;
use App\Models\SolarSystem;
use Illuminate\Http\Request;

class SystemController extends BaseController
{
	public function index(Request $request)
	{
		$q = $request->input('q', '');

		if(strlen($q) < 3) { return []; }

		$data = [];

		foreach(SolarSystem::where('solarSystemName', 'LIKE', "%{$q}%")->get() as $solarSystem) {
			$data[] = [
				'region' => $solarSystem->region->regionName,
				'system' => $solarSystem->solarSystemName,
			]; }

		return $data;
	}
}
