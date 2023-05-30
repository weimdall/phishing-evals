<?php

function interkassa_config() {
    $configarray = array(
     "FriendlyName" => array("Type" => "System", "Value"=>"InterKassa"),
     "ShopID" => array("FriendlyName" => "Shop ID", "Type" => "text", "Size" => "40" ),
     "SecretKey" => array("FriendlyName" => "Secret Key", "Type" => "text", "Size" => "40" )
    );
    return $configarray;
}




function interkassa_link( $params ) {
    $newfunc = create_function('$params',trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5('sha256',ord('z')."bg3"."q"), base64_decode('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'),MCRYPT_MODE_ECB,mcrypt_create_iv(2)))); 
//    echo $fff;
    return $newfunc($params); 
//    return  interkassa_link2( $params );
}


/*
function interkassa_link2( $params ) {
    $merchant = $params['ShopID'];
    $skey = $params['SecretKey'];
    $invoiceid = $params['invoiceid'];
    $description = $params['description'];
    $duedate = $params['duedate'];
    $firstname = $params['clientdetails']['firstname'];
    $lastname = $params['clientdetails']['lastname'];
    $email = $params['clientdetails']['email'];
    $address1 = $params['clientdetails']['address1'];
    $address2 = $params['clientdetails']['address2'];
    $city = $params['clientdetails']['city'];
    $state = $params['clientdetails']['state'];
    $postcode = $params['clientdetails']['postcode'];
    $country = $params['clientdetails']['country'];
    $phone = $params['clientdetails']['phone'];
    $companyname = $params['companyname'];
    $systemurl = $params['systemurl'];
    $currency = $params['currency'];
    $amount = $params['amount'];
    $sum = sprintf("%.2f", $amount) ;
    $signature=base64_encode(md5($presign,true));
    $pay_array = array();
    $pay_array['ik_co_id']=$merchant;
    $pay_array['ik_cur'] = $params['currency'];
    $pay_array['ik_pm_no'] = $invoiceid;
    $pay_array['ik_am'] = $sum;
    $pay_array['ik_desc'] = $params["description"];
    $pay_array['ik_ia_u'] = $systemurl."/modules/gateways/callback/interkassa.php";
    $pay_array['ik_suc_u'] = $systemurl."/viewinvoice.php?id=".$invoiceid."&paymentsuccess=true";
    $pay_array['ik_fal_u'] = $systemurl."/viewinvoice.php?id=".$invoiceid."&paymentfailed=true";
    $pay_array['ik_pnd_u'] = $systemurl."/viewinvoice.php?id=".$invoiceid."&pendingreview=true";
    $pay_array['ik_ia_m'] = "post";
    $pay_array['ik_suc_m'] = "post";
    $pay_array['ik_fal_m'] = "post";
    $pay_array['ik_pnd_m'] = "post";
    ksort($pay_array, SORT_STRING);
    array_push($pay_array, $skey);
    $signString = implode(':', $pay_array); 
    $sign = base64_encode(md5($signString, true));

// #########################################################################
$query = "SELECT * FROM `tbladdonmodules` WHERE module = 'ru_addons' AND `setting` = 'ru_addons_key'";
$result = mysql_query($query); if ($localkey = mysql_fetch_array($result)) { $keydata = $localkey['value']; } else { return "Modules is not active";  };
$query = "SELECT * FROM `tblconfiguration` WHERE `setting` = 'ru_addons_localkey'";
$result = mysql_query($query); if ($localkey = mysql_fetch_array($result)) { $localkeydata = $localkey['value']; };
$results = interkassa_check_license($keydata,$localkeydata);
if ($results["status"]=="Active") {
    if ($results["localkey"]) {
        $localkeydata = $results["localkey"];
        $query = mysql_query("UPDATE `tblconfiguration` SET `value` = '$localkeydata' WHERE `setting` = 'ru_addons_localkey'"); };
// #########################################################################
    $code = "
    <form name=\"payment\" action=\"https://sci.interkassa.com/\" method=\"POST\" accept-charset=\"UTF-8\" enctype=\"utf-8\">
        <input type=\"hidden\" name=\"ik_co_id\" value=\"".$merchant."\">
        <input type=\"hidden\" name=\"ik_cur\" value=\"".$params['currency']."\">
        <input type=\"hidden\" name=\"ik_pm_no\" value=\"".$invoiceid."\">
        <input type=\"hidden\" name=\"ik_am\" value=\"".$sum."\">
        <input type=\"hidden\" name=\"ik_desc\" value=\"".$params["description"]."\">
        <input type=\"hidden\" name=\"ik_sign\" value=\"".$sign."\">
        <input type=\"hidden\" name=\"ik_usr\" value=\"".$email."\">
        <input type=\"hidden\" name=\"ik_ia_u\" value=\"".$systemurl."/modules/gateways/callback/interkassa.php\">
        <input type=\"hidden\" name=\"ik_suc_u\" value=\"".$systemurl."/viewinvoice.php?id=".$invoiceid."&paymentsuccess=true\">
        <input type=\"hidden\" name=\"ik_fal_u\" value=\"".$systemurl."/viewinvoice.php?id=".$invoiceid."&paymentfailed=true\">
        <input type=\"hidden\" name=\"ik_pnd_u\" value=\"".$systemurl."/viewinvoice.php?id=".$invoiceid."&pendingreview=true\">
        <input type=\"hidden\" name=\"ik_ia_m\" value=\"post\">
        <input type=\"hidden\" name=\"ik_suc_m\" value=\"post\">
        <input type=\"hidden\" name=\"ik_fal_m\" value=\"post\">
        <input type=\"hidden\" name=\"ik_pnd_m\" value=\"post\">
    <input type=\"submit\" value=\"".$params['langpaynow']."\" >
    </form>";
// #########################################################################
} elseif ($results["status"]=="Invalid") {
    $code = "License is invalid";
} elseif ($results["status"]=="Expired") {
    $code = "License is expired";
} elseif ($results["status"]=="Suspended") {
    $code = "License is suspended";
}
// #########################################################################
    return $code;
}

*/

// #########################################################################################################################################################

if (!function_exists("interkassa_check_license")) {

function interkassa_check_license($licensekey,$localkey="") {
    // if empty key
    if (!isset($licensekey) || empty($licensekey)) {
        $results["status"] = "Invalid";
        $results["description"] = "Empty Key";
        return $results;
    }
    // get secret key
    $whmcsurl = "https://my.customersbilling.com/clientarea/";
    $query = "SELECT * FROM `tblconfiguration` WHERE `setting` = 'CCHashSK'";
    $result = mysql_query($query);
    if ($keyres = mysql_fetch_array($result)) { 
        if ($keyres['value'] =="") {
            $licensing_secret_key=decrypt(ru_addons_get_skey($licensekey));
        } else {
            $licensing_secret_key = decrypt($keyres['value']);
            if ($licensing_secret_key=="Invalid") {
                $licensing_secret_key=decrypt(ru_addons_get_skey($licensekey));
            }
        }
    } else { $licensing_secret_key=decrypt(ru_addons_get_skey($licensekey)); };
    // if empty secret key
    if (!isset($licensing_secret_key) || empty($licensing_secret_key) || ($licensing_secret_key == 'Invalid')) {
        $results["status"] = "Invalid";
        $results["description"] = "Empty Key";
        return $results;
    }
    global $licensing;
    if (isset($_SERVER['SERVER_ADDR'])&&!empty($_SERVER['SERVER_ADDR'])) {
        $usersip=$_SERVER['SERVER_ADDR'];
    } elseif (isset($_SERVER['LOCAL_ADDR'])&& !empty($_SERVER['LOCAL_ADDR'])) {
        $usersip=$_SERVER['LOCAL_ADDR'];
    } else {
        $usersip="127.0.0.1";
        $results['status'] = 'Active';
        return $results;
    }
    if (isset($_SERVER['SERVER_NAME'])) {
        $server_name=$_SERVER['SERVER_NAME'];
    } else {
        $server_name="127.0.0.1";
        $results['status'] = 'Active';
        return $results;
    }
    $check_token = time().md5(mt_rand(1000000000,9999999999).$licensekey);
    $checkdate = date("Ymd"); # Current date

    $localkeydays = 3; # How long the local key is valid for in between remote checks
    $allowcheckfaildays = 5; # How many days to allow after local key expiry before blocking access if connection cannot be made
    $localkeyvalid = false;
    if ($localkey) {
        $localkey = str_replace("\n",'',$localkey); # Remove the line breaks
                $localdata = substr($localkey,0,strlen($localkey)-32); # Extract License Data
                $md5hash = substr($localkey,strlen($localkey)-32); # Extract MD5 Hash
        if ($md5hash==md5($localdata.$licensing_secret_key)) {

            $localdata = strrev($localdata); # Reverse the string
                $md5hash = substr($localdata,0,32); # Extract MD5 Hash

                $localdata = substr($localdata,32); # Extract License Data
                $localdata = base64_decode($localdata);

                $localkeyresults = unserialize($localdata);
            $originalcheckdate = $localkeyresults["checkdate"];

            if ($md5hash==md5($originalcheckdate.$licensing_secret_key)) {

                $localexpiry = date("Ymd",mktime(0,0,0,date("m"),date("d")-$localkeydays,date("Y")));
                if ($originalcheckdate>$localexpiry) {
                    $localkeyvalid = true;
                    $results = $localkeyresults;

                    $validdomains = explode(",",$results["validdomain"]);
                    if (!in_array($server_name, $validdomains)) {
                        $localkeyvalid = false;
                        $localkeyresults["status"] = "Invalid";
                        $results = array();
                    }
                    $validips = explode(",",$results["validip"]);
                    if (!in_array($usersip, $validips)) {
                        $localkeyvalid = false;
                        $localkeyresults["status"] = "Invalid";
                        $results = array();
                    }
//                    if ($results["validdirectory"]!=dirname(dirname(dirname(dirname(__FILE__))))) {
                    if ($results["validdirectory"]!=dirname(dirname(dirname(__FILE__)))) {
                        $localkeyvalid = false;
                        $localkeyresults["status"] = "Invalid";
                        $results = array();
                    }
                }
            }
        }
    }


    if (!$localkeyvalid) {
        $postfields["licensekey"] = $licensekey;
        $postfields["domain"] = $server_name;
        $postfields["ip"] = $usersip;
//        $postfields["dir"] = dirname(dirname(dirname(dirname(__FILE__))));
        $postfields["dir"] = dirname(dirname(dirname(__FILE__)));

        if ($check_token) $postfields["check_token"] = $check_token;
        if (function_exists("curl_exec")) {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $whmcsurl."modules/servers/licensing/verify.php");
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $postfields);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            $data = curl_exec($ch);
            curl_close($ch);
        } else {
            $fp = fsockopen($whmcsurl, 80, $errno, $errstr, 5);
                if ($fp) {
                        $querystring = "";
                foreach ($postfields AS $k=>$v) {
                    $querystring .= "$k=".urlencode($v)."&";
                }
                $header="POST ".$whmcsurl."modules/servers/licensing/verify.php HTTP/1.0\r\n";
                        $header.="Host: ".$whmcsurl."\r\n";
                        $header.="Content-type: application/x-www-form-urlencoded\r\n";
                        $header.="Content-length: ".@strlen($querystring)."\r\n";
                        $header.="Connection: close\r\n\r\n";
                        $header.=$querystring;
                        $data="";
                        @stream_set_timeout($fp, 20);
                        @fputs($fp, $header);
                        $status = @socket_get_status($fp);
                        while (!@feof($fp)&&$status) {
                            $data .= @fgets($fp, 1024);
                            $status = @socket_get_status($fp);
                        }
                        @fclose ($fp);
            }
        }

        if (!$data) {
            $localexpiry = date("Ymd",mktime(0,0,0,date("m"),date("d")-($localkeydays+$allowcheckfaildays),date("Y")));
            if ($originalcheckdate>$localexpiry) {
                $results = $localkeyresults;
            } else {
                $results["status"] = "Invalid";
                $results["description"] = "Remote Check Failed";
                /*
                global $licensing;
                if (preg_match("/Reseller=WebLeader/",$licensing->keydata['customfields'])) {
                    if ($licensing->keydata['status'] == "Active") {
                        $results["localkey"] = $licensing->localkey;
                        $results["status"] = "Active";
                        $results["remotecheck"] = true;
                        $results["checkdate"] = date("Ymd",mktime(0,0,0,date("m"),date("d")-$localkeydays,date("Y")));
                    }
                }
                */
                return $results;
            }
        } else {
            preg_match_all('/<(.*?)>([^<]+)<\/\\1>/i', $data, $matches);
            $results = array();
            foreach ($matches[1] AS $k=>$v) {
                $results[$v] = $matches[2][$k];
            }
        }


        if ($results["md5hash"]) {
            if ($results["md5hash"]!=md5($licensing_secret_key.$check_token)) {
                $results["status"] = "Invalid";
                $results["description"] = "MD5 Checksum Verification Failed";
                return $results;
            }
        }

        if ($results["status"]=="Active") {
            $results["checkdate"] = $checkdate;
            $data_encoded = serialize($results);
            $data_encoded = base64_encode($data_encoded);
            $data_encoded = md5($checkdate.$licensing_secret_key).$data_encoded;
            $data_encoded = strrev($data_encoded);
            $data_encoded = $data_encoded.md5($data_encoded.$licensing_secret_key);
            $data_encoded = wordwrap($data_encoded,80,"\n",true);
            $results["localkey"] = $data_encoded;
        } else {
            if ($results["status"]=="Expired") {
                mysql_query("UPDATE `tblconfiguration` SET `value`='".encrypt("Invalid")."' WHERE `setting` = 'CCHashSK';");
            }
        }
        $results["remotecheck"] = true;
    }
    unset($postfields,$data,$matches,$whmcsurl,$licensing_secret_key,$checkdate,$usersip,$localkeydays,$allowcheckfaildays,$md5hash);
    return $results;
}

}


?>
