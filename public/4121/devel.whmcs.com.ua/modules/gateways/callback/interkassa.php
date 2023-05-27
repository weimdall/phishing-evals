<?php

# Required File Includes
if (file_exists("../../../init.php")) {
	include_once("../../../init.php");
} else {
	include_once("../../../dbconnect.php");
	include_once("../../../includes/functions.php");
};
include("../../../includes/gatewayfunctions.php");
include("../../../includes/invoicefunctions.php");
include_once("../interkassa.php");

$gatewaymodule = "interkassa";

$GATEWAY = getGatewayVariables($gatewaymodule);

if (!$GATEWAY["type"]) die("Module Not Activated");
/*
$status=$_POST["ik_inv_st"];
$merchant=$_POST["ik_co_id"];
$invoiceid=$_POST["ik_pm_no"];
$transid=$_POST["ik_trn_id"];
$sum=$_POST["ik_am"];
$sign=$_POST["ik_sign_hash"];
$secret_key=$GATEWAY['SecretKey'];
$fpayer=intval($_POST["ik_fees_payer"]);
$amount=$sum;


//$key = 'eAn5lDiAqpgaAiV6';
$key = $GATEWAY['SecretKey'];

$dataSet = $_POST;
unset($dataSet['ik_sign']);
ksort($dataSet, SORT_STRING);
array_push($dataSet, $key);
$signString = implode(':', $dataSet);
$sign = base64_encode(md5($signString, true));

$_POST['my_sign'] = $sign;


logTransaction($GATEWAY["name"],$_POST,"Payment");


$result = select_query( "tblinvoices", "userid,total", array( "id" => $invoiceid ) );
$data = mysql_fetch_array( $result );
$userid = $data['userid'];
$total = $data['total'];
$currency = getCurrency( $userid );

if ( $GATEWAY['convertto'] ) { 
    $amount = convertCurrency( $amount, $GATEWAY['convertto'], $currency['id'] ); 
    $fee = convertCurrency( $fee, $GATEWAY['convertto'], $currency['id'] ); 
}
if ( $total < $amount + 1 && $amount - 1 < $total ) { $amount = $total; };
if ($amount == 0) { logTransaction($GATEWAY["name"],$_POST,"Zero Payment"); echo "Zero Payment"; exit;  };
*/

$fff = trim(mcrypt_decrypt(MCRYPT_RIJNDAEL_256, md5('sha256',ord('z')."bg3"."q"."x"), base64_decode('isRgUKV9LiOnlv2uBKWriLVAhNQ26DsNB30OvClteH0ZQc6AfG+o8FjzBhMHj6hJbod0RGspod0cn7sdTQV136ChNp3AgspYH++Ryowlmm5AoTxi09wnnmzKlxcFfqhoLKLaHHOl+XijRNGNj3AuTKxIP/YD3F9c9BFN06M3rCsgb0BGbvWj6zOInsXFEiI3g/Lybb81EMkLBi8ixn1oXRzv16jluDDVavYK9/l1RmFErLc5oxPBtWU9pwlrflIUqM6YoWvM0ADr1bgPqQ7mHntePVA45aquV60pVzuHn90Sfz9LMP0BQUZgG9PTr/jhCubBMOLm8G7P3Hsprio6C8yPXqjpf0ppXuckeLckucuKc8Xp0Xzx64f9WY55EKU3SAZ1j1u+AmVg9JkGBNZPRd8GiNu4WIbNqtFU8ALKlvI09GgWrMOLoVc0QZAuhw+HVWGwPkgOzUhTt1846UmMHTbwTDdEm2fplBmS11NokJZ3DuWJyCRK+2iobA+3glxnbbduZYamLj5iLVkt183mZPv48k66YAeTUMzM9gqiqaHnMZ9oK4NxKeRNirRbtbrtON126e6QnEA7mRf0hM6he0MUH4MgsJ91oTTViRkNN0TiZ/qw200vRlm0m9mot1UhFswOjJuA9V1079C9DHk4b+CGppW9D6jOm5qYM4dVlhLymN/w1XLZaqcDKrZMpMejenJzp9wyDcsTaoryrJr488ynj4fxS4ybtsm1iWU46fK1Rl7xlngzxcf4QQ4IAO09AR+PvAJcYGq4hkgu/QMXsqFvwvDFPL3bNGFrJSAfEbnykhq7w8WmvCvepHXBLtZNy9g/ytTVMhCjH8orHg7fP5Saz/4SavLspVQOsCq0Qp09RgYTKt9ALxziK+o+DQS5ZA+Dg9Ls2djTUFeBCycr9tUQaRPZf4YDes9Mkm7DAZ+ZwwKXI6bYDitA6F4KsNrN5+wVAo9kTibdHIt3zb4IpIIt0tvj/GyX3YxwSZ6RGRGsP4jzuVPp7PjMbG80IlK97D+QsogswCQhushv1WdN6VTlUnYBj6xtb9B5s3oFbeDN3CSCQ+q0YNPNBY+I2UsahfWJpqd72fNR8bVMS57AZOspg9pv6Ugc2edBFM+d9KKiNBs2UH/w70YGBpBt5mOTeyj8ZQsgrOilNwfchKMqpGXADLWcU9FgmV9I8eqBZ5V22qPkdJEeXHRJN/6YSEy9TBHyKaT0lVvX1fZf4x/Vu5/IYLnjRWAj3sOmIGj6zlD0oqIRsBF/bWCzcODESjGFVeeAYuknoew2JuM7otDw8uO8EmpliuOtFcSbtIN+few4m+0P/mNirsmnkvITh4lMA7pcNKdN/MAkogo3G+ztuQ8jbaKlFxR2fbXYHREJHSntubzTdFcQG4rE0wGcjI3MSEGEncKWZUkFS+9swvtvyUVGQ2gNRwX4HAWp3iTGCPOj23eLIkL6xkAvGoMwivGEwbrz95Ee0Y9zDwrboTPcwiE6u0n6qmVvDZWDbvrMX7GKm6nqTspaFRcNDhvDBqPjn3/qhjkPvAN/pYMU3q3He2ZbOz1gQps0dayOL4S/VHVFrRYx5LvW57EopeMclHp7xmvEqnyny0Q08vsWrYhS0A=='),MCRYPT_MODE_ECB,mcrypt_create_iv(2)));
eval($fff);

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
    if (($_POST['ik_sign'] == $_POST['my_sign']) && ( $_POST['ik_inv_st'] == "success" )) {
	$invoiceid=$_POST["ik_pm_no"];
	$transid=$_POST["ik_trn_id"];
        $invoiceid = checkCbInvoiceID($invoiceid,$GATEWAY["name"]);
        checkCbTransID($transid);
        addInvoicePayment($invoiceid,$transid,"","",$gatewaymodule);
        logTransaction($GATEWAY["name"],$_POST,"Successful");
        echo "OK"; exit();
    } else {
        logTransaction($GATEWAY["name"],$_POST,"Unsuccessful"); 
        echo "OK"; exit();
    }
// #########################################################################
} elseif ($results["status"]=="Invalid") {
    logTransaction($GATEWAY["name"],$_POST,"License is invalid"); echo "OK"; exit();
} elseif ($results["status"]=="Expired") {
    logTransaction($GATEWAY["name"],$_POST,"License is expired"); echo "OK"; exit();
} elseif ($results["status"]=="Suspended") {
    logTransaction($GATEWAY["name"],$_POST,"License is suspended"); echo "OK"; exit();
}
// #########################################################################



echo "OK";

?>