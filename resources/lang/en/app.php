<?php

return [
	'error'                   => 'Error!',
	'404'                      => 'The requested page could not be found. <a href=":url">Click here</a> to return to the homepage.',
	'500'                      => 'There was an error. <a href=":url">Click here</a> to return to the homepage.',
	'503'                      => 'Be right back!',
	'invalid_credentials'      => 'Your credentials are invalid.',
	'invalid_corporation'      => 'Your character is not in an allowed corporation.',
	'invalid_uploader_token'   => 'Your uploader token is invalid.',
	'invalid_uploader_banned'  => 'Your character is banned from submitting reports.',
	'invalid_report_text'      => 'The text submitted for the report contains bad syntax.',
	'invalid_report_timestamp' => 'The timestamp submitted for the report must be within the last :seconds seconds.',
	'invalid_report_filtered'  => 'The message submitted for the report contains a word (:word) in the filter list.',
	'invalid_report_systems'   => 'The message submitted for the report does not contain any solar systems.',
	'invalid_report_insert'    => 'The report could not be inserted into the database for some reason.',

	'login'                    => 'Login',
	'logout'                   => 'Logout',
	'username'                 => 'Username',
	'password'                 => 'Password',
	'remember_me'              => 'Remember me',
	'uploader'                 => 'Uploader',
	'uploader_token'           => 'Your uploader token is: :token',
	'uploader_download'        => '<a href=":url">Click here</a> to get the updater script.',
	'uploader_explain'         => 'python uploader.py --url=:url --token=:token',
];
