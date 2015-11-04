<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;

class EveController extends BaseController
{
	private $guzzle;

	public function __construct(\GuzzleHttp\Client $guzzle)
	{
		$this->guzzle = $guzzle;
	}

	public function index(Request $request)
	{
		if(!$request->ajax()) { app()->abort(400); }

		if(($data = \Cache::get('eve:data', false))) {
			return $data; }

		if(($data = $this->fetchDataFromApi())) {
			\Cache::put('eve:data', $data, 60);
			return $data; }

		return [];
	}

	private function fetchDataFromApi()
	{
		try {
			$kills = $this->guzzle->get('https://api.eveonline.com/map/Kills.xml.aspx');
			$jumps = $this->guzzle->get('https://api.eveonline.com/map/Jumps.xml.aspx');

			if($kills->getStatusCode() !== 200 || $jumps->getStatusCode() !== 200) {
				return false; }

			$kills = simplexml_load_string((string)$kills->getBody());
			$jumps = simplexml_load_string((string)$jumps->getBody()); }

		catch(\Exception $e) {throw $e;
			return false; }

		$result          = [];
		$result['kills'] = $this->parseKillsFromApiData($kills);
		$result['jumps'] = $this->parseJumpsFromApiData($jumps);

		return $result;
	}

	private function parseKillsFromApiData($xml)
	{
		$result = [];

		foreach($xml->result->rowset[0] as $row) {
			$solarSystemID = (integer)$row{'solarSystemID'};
			$podKills      = (integer)$row{'podKills'     };
			$factionKills  = (integer)$row{'factionKills' };
			$shipKills     = (integer)$row{'shipKills'    };

			$result[$solarSystemID] = [
				'pods'  => $podKills    ,
				'rats'  => $factionKills,
				'ships' => $shipKills   ,
			]; }

		return $result;
	}

	private function parseJumpsFromApiData($xml)
	{
		$result = [];

		foreach($xml->result->rowset[0] as $row) {
			$solarSystemID = (integer)$row{'solarSystemID'};
			$shipJumps     = (integer)$row{'shipJumps'    };

			$result[$solarSystemID] = $shipJumps; }

		return $result;
	}
}
