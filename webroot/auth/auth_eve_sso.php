<?php if (!defined('INTEL')) die('go away'); ?>
<?php

DEFINE('APPLICATION_MANAGEMENT_URL', 'https://developers.eveonline.com/applications');
DEFINE('CREST_BASE_URL', 'https://login.eveonline.com');
DEFINE('CREST_AUTH_URL', '/oauth/authorize');
DEFINE('CREST_TOKEN_URL', '/oauth/token');
DEFINE('CREST_VERIFY_URL', '/oauth/verify');
DEFINE('USER_AGENT', 'iveeCrest/1.0');

function authInit() {
    global $cfg_app_client_id, $cfg_url_auth_success;
    header(
      'Location:' . CREST_BASE_URL . CREST_AUTH_URL . '?response_type=code&redirect_uri=' . $cfg_url_auth_success
      . '&client_id=' . $cfg_app_client_id . '&state=' . MD5(microtime() . uniqid())
    );
    return true;
}

function authVerify() {

    global $cfg_app_client_id, $cfg_app_secret, $cfg_url_base, $cfg_alliance_file;

    $header = 'Authorization: Basic ' . base64_encode($cfg_app_client_id . ':' . $cfg_app_secret);
    $fields_string = '';
    $fields = array(
        'grant_type' => 'authorization_code',
        'code' => $_GET['code']
    );
    foreach ($fields as $key => $value) {
        $fields_string .= $key . '=' . $value . '&';
    }
    rtrim($fields_string, '&');
    $ch = curl_init();
    
    curl_setopt_array(
        $ch,
        array(
            CURLOPT_URL             => CREST_BASE_URL . CREST_TOKEN_URL,
            CURLOPT_POST            => true,
            CURLOPT_POSTFIELDS      => $fields_string,
            CURLOPT_HTTPHEADER      => array($header),
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_USERAGENT       => USER_AGENT,
            CURLOPT_SSL_VERIFYPEER  => true,
            CURLOPT_SSL_CIPHER_LIST => 'TLSv1', //prevent protocol negotiation fail
        )
    );

    $resBody = curl_exec($ch);
    $info = curl_getinfo($ch);
    $err  = curl_errno($ch);
    $errmsg = curl_error($ch);
    curl_close($ch);

    if ($err != 0) {
        error_log('AUTH RESP ERROR ' . $err . ' : ' . $errmsg);
        return NULL;
    }
    if (!in_array($info['http_code'], array(200, 302))) {
        error_log('AUTH HTTP ERROR ' . (int)$info['http_code'] . '. Response body: ' . $resBody);
        return NULL;
    }

    error_log('RES BODY ' . $resBody);
    $response = json_decode($resBody);
    
    $ch = curl_init();
    $header='Authorization: Bearer '.$response->access_token;
    curl_setopt_array(
        $ch,
        array(
            CURLOPT_URL             => CREST_BASE_URL . CREST_VERIFY_URL,
            CURLOPT_HTTPHEADER      => array($header),
            CURLOPT_RETURNTRANSFER  => true,
            CURLOPT_USERAGENT       => USER_AGENT,
            CURLOPT_SSL_VERIFYPEER  => true,
            CURLOPT_SSL_CIPHER_LIST => 'TLSv1', //prevent protocol negotiation fail
        )
    );
    
    $resBody = curl_exec($ch);
    $info = curl_getinfo($ch);
    $err  = curl_errno($ch);
    $errmsg = curl_error($ch);
    curl_close($ch);

    if ($err != 0) {
        error_log('AUTH RESP ERROR ' . $err . ' : ' . $errmsg);
        return NULL;
    }
    if (!in_array($info['http_code'], array(200, 302))) {
        error_log('AUTH HTTP ERROR ' . (int)$info['http_code'] . '. Response body: ' . $resBody);
        return NULL;
    }

    $json = json_decode($resBody);
    error_log('RES ' . $resBody);
    if( exec('grep -x '.$json->CharacterID.' '.$cfg_alliance_file) ) {
        authSession($json->CharacterID, $json->CharacterName);
        header("Location: " . $cfg_url_base);
        return true;
    } else {
        error_log('Character not authorized ' . $json->CharacterID . ' : ' . $json->CharacterName);
        return false;
    }
}

?>
