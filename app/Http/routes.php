<?php

Route::group(['middleware' => 'guest'], function() {
	Route::get ('/login', 'UserController@getLogin' )->name( 'getLogin');
	Route::post('/login', 'UserController@postLogin')->name('postLogin');
});

Route::group(['middleware' => 'auth'], function() {
	Route::get('/logout', 'UserController@getLogout' )->name( 'getLogout');
	Route::get('/uploader', function() { return view('intel.uploader'); })->name('getUploader');
	Route::get('/', function () { return view('index'); })->name('home');

});

Route::resource('/map'   , 'MapController'   , ['only' => ['index'         ]]);
Route::resource('/report', 'ReportController', ['only' => ['index', 'store']]);

Route::get('/settings', function() {
	return json_encode([
		's-background-image' => rand(1, 8),
	]);
});
