<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;

class MapController extends BaseController
{
	public function index(Request $request)
	{
		if(!$request->ajax()) { app()->abort(400); }

		$region = $request->input('region', false);
		$file   = "app/maps/{$region}.svg.json";

		if(!\File::exists(storage_path($file))) {
			$file = "app/maps/Tenal.svg.json"; }

		return \File::get(storage_path($file));
	}
}
