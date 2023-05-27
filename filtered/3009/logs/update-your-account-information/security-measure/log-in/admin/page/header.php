<?php
session_start();
set_time_limit(0);
require_once __DIR__ . '/../../function.php';


$password = "s2222";

if (!empty($password) and $_SESSION['admins'] != "KZLY") {
  if (isset($_REQUEST['pass']) and $_REQUEST['pass'] == $password) {
    $_SESSION['admins'] = "KZLY";
    $api->redirect("home");
  } else {
    print "<pre align=center><form method=post>Token: <input type='password' name='pass'><input type='submit' value='>>'></form></pre>";
    exit;
  }
}

?>
<!DOCTYPE html>
<html>

<head>
  <title>Admin</title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<meta name="robots" content="noindex, nofollow, noarchive, nosnippet, noodp, noydir">
  <link rel="shortcut icon" href="../assets/img/favicon.ico">
	<link rel="apple-touch-icon" href="../assets/img/apple-touch-icon.png">
	<link rel="stylesheet" href="../assets/css/admin_style.css">
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:600,400,300">
	<script src="../assets/js/jquery.min.js"></script>
	<script src="../assets/js/start.js"></script>
</head>
