<?php if (!defined('INTEL')) die('go away'); ?>
<?php

// -------------------------------------------------

function tpl_header() {
    global $cfg_favicon_url, $cfg_alliance_name;;
    require("tpl/tpl_header.php");
}

function tpl_nav($name, $active) {
    global $cfg_cookie_name, $cfg_path_base, $cfg_header_img_html;
    require("tpl/tpl_nav.php");
}

function tpl_nav_empty() {
    global $cfg_path_base, $cfg_header_img_html;
    require("tpl/tpl_nav_empty.php");
}

function tpl_footer($pScripts) {
    require("tpl/tpl_footer.php");
}

// -------------------------------------------------

function tpl_map() {
    require("tpl/tpl_map.php");
}

function tpl_settings() {
    require("tpl/tpl_settings.php");
}

function tpl_uploader() {
    global $cfg_alliance_name;
    require("tpl/tpl_uploader.php");
}

function tpl_help() {
    global $cfg_alliance_name;
    require("tpl/tpl_help.php");
}

function tpl_bridges() {
    global $cfg_alliance_name, $cfg_intel_it_contacts;
    require("tpl/tpl_bridges.php");
}

// -------------------------------------------------

function tpl_error() {
    require("tpl/tpl_error.php");
}

// -------------------------------------------------

function tpl_auth_needed() {
    global $cfg_url_auth_init, $cfg_alliance_name, $cfg_auth_type;
    require("tpl/tpl_auth_needed_" . $cfg_auth_type . ".php");
}

function tpl_auth_error() {
    global $cfg_url_auth_init, $cfg_alliance_name, $cfg_auth_type;
    require("tpl/tpl_auth_error.php");
}

function tpl_auth_negative() {
    global $cfg_url_auth_init, $cfg_alliance_name, $cfg_auth_type;
    require("tpl/tpl_auth_negative.php");
}

// -------------------------------------------------

?>
