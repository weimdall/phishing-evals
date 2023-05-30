<?php

include 'err.php';

    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = @$_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    if ($ip == '::1') {
        $ip = '127.0.0.1';
    }

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "http://ip-geo.ru/whois/?ip=".$ip.'?');
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    $ip_data_in = curl_exec($ch);  
    curl_close($ch);

    if($ip_data_in) {

        preg_match('/<td class="country">(.*?)<\/td>/',$ip_data_in,$country);
        $country = $country[1];

        preg_match('/<th class="country">(.*?)<\/th>/',$ip_data_in,$location);
        $location = htmlspecialchars_decode($location[1]);

    }
 
 if (strpos($ip_data_in, '404') || strpos($ip_data_in, 'reserved')) {
        $country = 'Unknown';
    }

?>
 