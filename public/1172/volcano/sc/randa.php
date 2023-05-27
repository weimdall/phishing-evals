<?php
function getChak($len = 10) {
    $word = array_merge(range('a', 'z'), range('A', 'Z'));
    shuffle($word);
    return substr(implode($word), 0, $len);
}
function getBChak($len = 10) {
    $word = array_merge(range('A', 'Z'));
    shuffle($word);
    return substr(implode($word), 0, $len);
}

$fo1=getChak(13);
$fo2=getChak(8);
$fo3=getChak(10);
$fo4=getChak(9);
$fo5=getChak(14);
$fo6=getChak(6);
$fo7=getChak(16);
$fo8=getBChak(19);
$fo9=getBChak(13);

$num1=rand(10020000, 10090000);
$num2=rand(10020000, 10090000);
$num3=rand(10020000, 10090000);
$num4=rand(10020000, 10090000);
$num5=rand(10020000, 10090000);

$rand = dechex(rand(0x000000, 0xFFFFFF));
$randa = dechex(rand(0x000000, 0xDDDDDD));
$rando = dechex(rand(0x000000, 0xAAAAAA));
$randomString = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 1) . substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 10);
?>