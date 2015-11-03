<?php

namespace App\Models;

use App\Models\Report;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;

class User extends Model implements AuthenticatableContract, AuthorizableContract
{
	use Authenticatable, Authorizable;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = ['characterID', 'characterName', 'settings'];

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = ['remember_token', 'uploader_token'];

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
        'isBanned' => 'boolean',
    ];

	public function reports()
	{
		return $this->belongsToMany('App\Models\Report', 'report_uploaders', 'userID', 'reportID');
	}

	public function scopeUploadedRecently($query)
	{
		return $query->where('uploaded_at', '>', \Carbon\Carbon::now('UTC')->subMinutes(10));
	}
}
