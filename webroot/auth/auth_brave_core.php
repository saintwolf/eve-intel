<?php if (!defined('INTEL')) die('go away'); ?>
<?php

require_once("auth.php")

function authInit() {
    global $cfg_core_endpoint, $cfg_core_application_id, $cfg_core_private_key, $cfg_core_public_key, $cfg_url_auth_success, $cfg_url_auth_fail;
    define('USE_EXT', 'GMP');
    require 'vendor/autoload.php';
    try {
	$api = new Brave\API($cfg_core_endpoint, $cfg_core_application_id, $cfg_core_private_key, $cfg_core_public_key);
	$info_data = array( 'success' => $cfg_url_auth_success, 'failure' => $cfg_url_auth_fail);
	$result = $api->core->authorize($info_data);
	header("Location: " . $result->location);
        return true;
    } catch(\Exception $e) {
        return NULL;
    }
}

function authVerify() {
    global $cfg_core_endpoint, $cfg_core_application_id, $cfg_core_private_key, $cfg_core_public_key, $cfg_url_base;
    $token = preg_replace("/[^A-Za-z0-9]/", '', $_GET['token']);
    if (empty($token)) {
	return NULL;
    }
    define('USE_EXT', 'GMP');
    require 'vendor/autoload.php';
    try {
	$api = new Brave\API($cfg_core_endpoint, $cfg_core_application_id, $cfg_core_private_key, $cfg_core_public_key);
	$result = $api->core->info(array('token' => $token));
    } catch(\Exception $e) {
	return NULL;
    }

    $charId = $result->character->id;
    $charName = $result->character->name;
    $tags = $result->tags;

    $valid = false;
    if (in_array("member", $tags)) {
	$valid = true;
    }
    if (in_array("blue", $tags)) {
	$valid = true;
    }

    if ($valid) {
	authSession($charId, $charName);
	header("Location: " . $cfg_url_base);
	return true;
    } else {
	return false;
    }
}

?>
