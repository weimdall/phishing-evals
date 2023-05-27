<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
if (!function_exists('lang()'))
{
	function lang($line,$parameter=array())
	{
	    $CI =& get_instance();
	    $implodeparameter=' $line = printf($CI->lang->line($line)';
	    if (!empty($parameter) AND is_array($parameter)) {
	    	$checkpstring =$CI->lang->line($line,false);
	    	foreach ($parameter as $key) {
	    		$implodeparameter.=',"'.$key.'"';
	    	}
	    	if (count($parameter) != $totalp) {
	    		$each = $totalp-count($parameter);
	    		for ($i=1; $i <= $each ; $i++) { 
	    			$implodeparameter.=',""';
	    		}
	    	}
	    	$implodeparameter.=');';
	    	eval($implodeparameter);
	    }else{
	    	$line = $CI->lang->line($line);
	    };
	    echo $line;
	}
}
