<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;

class MapController extends BaseController
{
	public function index(Request $request)
	{
		if(!$request->ajax()) { app()->abort(400); }

		$region = $request->input('region', 'Tenal');

		if(($data = \Cache::get("map:{$region}", false))) {
			return $data; }

		$file = storage_path("app/maps/{$region}.svg.json");

		if(\File::exists($file) && ($data = \File::get($file))) {
			\Cache::forever("map:{$region}", $data);
			return $data; }

		return $data;
	}
}
