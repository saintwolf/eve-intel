<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'reports';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['hash', 'submitter', 'raw', 'interpreted'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = [];

	public function solarSystems()
	{
		return $this->belongsToMany('App\Models\SolarSystem', 'report_systems', 'reportID', 'systemID');
	}

	public function uploaders()
	{
		return $this->belongsToMany('App\Models\User', 'report_uploaders', 'reportID', 'userID');
	}
}
