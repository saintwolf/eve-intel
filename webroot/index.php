<?php

ini_set('display_errors', 'On');
error_reporting(E_ALL);

define('INTEL', 23);

require("config.php");

try {
    $dbr = new PDO($cfg_sql_url, $cfg_sql_user, $cfg_sql_pass);
} catch (PDOException $e) {
    die('database init failed');
}

require("tpl/tpl.php");
require("auth/auth.php");
require("auth/auth_" . $cfg_auth_type . ".php");

$authResult = authCheck();

$pNav = "";
if (isset($_GET["nav"])) {
    $pNav = preg_replace("/[^a-z]/", '', $_GET['nav']);
}

if ($authResult === false) {

    if ($pNav == "") {
	tpl_header();
	tpl_nav_empty();
	tpl_auth_needed();
	tpl_footer(array());
	return;
    }

    if ($pNav == "init") {
	if (authInit() === NULL) {
	    tpl_header();
	    tpl_nav_empty();
	    tpl_auth_error();
	    tpl_footer(array());
	}
	// if true authInit() sets location header for redirect
	return;
    }

    if ($pNav == "verify") {
	$verify = authVerify();

	if ($verify === NULL) {
	    tpl_header();
	    tpl_nav_empty();
	    tpl_auth_error();
	    tpl_footer(array());
	}

	if ($verify === false) {
	    tpl_header();
	    tpl_nav_empty();
	    tpl_auth_negative();
	    tpl_footer(array());
	}

	// if true authVerify() sets location header for redirect

	return;
    }

    if ($pNav == "error") {
	tpl_header();
	tpl_nav_empty();
	tpl_auth_error();
	tpl_footer(array());
	return;
    }

    tpl_header();
    tpl_nav_empty();
    tpl_auth_needed();
    tpl_footer(array());
    return;

} else {
    $authCharId = $authResult[0];
    $authCharName = $authResult[1];

    if ($pNav == "") {
	$pNav = "map";
    }

    if ($pNav == "map") {
	tpl_header();
	tpl_nav($authCharName, $pNav);
	tpl_map();
	tpl_footer(array('js/intel_settings.js', 'js/intel_map.js', 'js/intel_map_poll.js', 'js/intel_map_eve.js', 'js/intel_map_jb.js', 'js/intel_map_draw.js', 'js/intel_map_reports.js', 'js/intel_map_logs.js', 'js/simpleheat.min.js', 'https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.10.2/typeahead.bundle.min.js', 'https://cdnjs.cloudflare.com/ajax/libs/handlebars.js/1.3.0/handlebars.min.js'));
	return;
    }

    if ($pNav == "settings") {
	tpl_header();
	tpl_nav($authCharName, $pNav);
	tpl_settings();
	tpl_footer(array('js/intel_settings.js'));
	return;
    }

    if ($pNav == "help") {
	tpl_header();
	tpl_nav($authCharName, $pNav);
	tpl_help();
	tpl_footer(array('js/intel_settings.js'));
	return;
    }

    if ($pNav == "uploader") {
	tpl_header();
	tpl_nav($authCharName, $pNav);
	tpl_uploader();
	tpl_footer(array('js/intel_settings.js'));
	return;
    }

    if ($pNav == "bridges") {
	tpl_header();
	tpl_nav($authCharName, $pNav);
	tpl_bridges();
	tpl_footer(array('js/intel_settings.js', 'js/intel_bridges.js', 'js/intel_map_jb.js'));
	return;
    }

    tpl_header();
    tpl_nav_empty();
    tpl_error();
    tpl_footer(array());
}

?>
