<?php

return [
	'error'                         => 'Error!',
	'404'                           => 'The requested page could not be found. <a href=":url">Click here</a> to return to the homepage.',
	'500'                           => 'There was an error. <a href=":url">Click here</a> to return to the homepage.',
	'503'                           => 'Be right back!',
	'invalid_credentials'           => 'Your credentials are invalid.',
	'invalid_corporation'           => 'Your character is not in an allowed corporation.',
	'invalid_uploader_token'        => 'Your uploader token is invalid.',
	'invalid_uploader_banned'       => 'Your character is banned from using the intel map.',
	'invalid_report_text'           => 'The text submitted for the report contains bad syntax.',
	'invalid_report_timestamp'      => 'The timestamp submitted for the report must be within the last :seconds seconds.',
	'invalid_report_filtered'       => 'The message submitted for the report contains a word (:word) in the filter list.',
	'invalid_report_systems'        => 'The message submitted for the report does not contain any solar systems.',
	'invalid_report_insert'         => 'The report could not be inserted into the database for some reason.',

	'login'                         => 'Login',
	'logout'                        => 'Logout',
	'username'                      => 'Username',
	'password'                      => 'Password',
	'remember_me'                   => 'Remember me',

	'uploader'                      => 'Uploader',
	'uploader_download'             => 'Download',
	'uploader_download_explain'     => 'The intel map relies on people running the game while having the intel channels and the uploader open to update the map. If no one is running the uploader, no intel will appear on the map.',
	'uploader_download_binary'      => 'Windows users can <a href=":url">click here</a> to download the binary version.',
	'uploader_download_source'      => 'Linux / Mac users can <a href=":url">right click / save as</a> to download the source script. Use "python -m pip install [module]" on any missing modules.',

	'uploader_config'               => 'Configuration',
	'uploader_config_explain'       => 'If you are using the above uploader you can paste the following into the uploader.ini file or paste them into the program itself.',
	'uploader_config_token_explain' => '* Your token changes every time you log into or out of the intel map.',
];
