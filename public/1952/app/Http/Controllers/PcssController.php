<?php

/**
 * PCSS Controller
 *
 * @package     Makent
 * @subpackage  Controller
 * @category    PCSS
 * @author      Trioangle Product Team
 * @version     0.9
 * @link        http://trioangle.com
 */

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;
use App\Models\ThemeSettings;

class PcssController extends Controller
{
    private $BODYBGCOLOR   = "";
    private $BODYFONTCOLOR = "";
    private $BODYFONTSIZE  = "";
    private $HEADERCOLOR   = "";
    private $FOOTERCOLOR   = "";
    private $BUTTONPRIMARYCOLOR   = "";
    private $BUTTONPRIMARYBORDERCOLOR   = "";

    public static $defined = array();
	
	private $css;
    private $iniciado = false;

    /**
     * Load Given CSS File
     *
     * @return load CSS File
     */
    public function index()
    {
        if (isset($_GET["css"]))
        {
            $css = strip_tags($_GET["css"]);
            $this->init($css);
            return response($this->pcss)->header('Content-Type', 'text/css');
        }
    }

    /**
     * Set Dynamic CSS Styles
     *
     * @param string $arg CSS file name
     * @return string CSS styles
     */
    public function init($arq)
    {
        $result = ThemeSettings::get();

        $this->BODYBGCOLOR        = "background-color: ".$result[0]->value;
        $this->BODYFONTCOLOR      = "color: ".$result[1]->value;
        $this->BODYFONTSIZE       = "font-size: ".$result[2]->value;
        $this->HEADERBGCOLOR      = "background-color: ".$result[3]->value;
        $this->FOOTERBGCOLOR      = "background-color: ".$result[4]->value;
        $this->HREFCOLOR          = "color: ".$result[5]->value;
        $this->BUTTONPRIMARYCOLOR = "background-color: ".$result[6]->value;
        $this->BUTTONPRIMARYBORDERCOLOR = "border-color: ".$result[6]->value;

        if (!is_file($arq))
        {
            die("CSS File not found");
        } 
        else 
        {                        
            $f = file($arq,FILE_SKIP_EMPTY_LINES|FILE_IGNORE_NEW_LINES);
            foreach ($f as &$linha)
            {
                $linha = trim($linha);
                $defines = array();
                if (strtolower(substr(trim($linha),0,4))=="unit")
                {
                    $this->UNIT = trim(substr(trim($linha),5));
                    $linha = "";
                }
                if (strtolower(substr(trim($linha),0,6))=="imgdir")
                {
                    $this->IMGDIR = trim(substr(trim($linha),7));
                    if (substr($this->IMGDIR,-1,1)!="/") $this->IMGDIR .= "/";
                    $linha = "";
                }
                if (strtolower(substr(trim($linha),0,8))=="fontface")
                {
                    $tempval = trim(substr(trim($linha),8));
                    list($name,$file) = explode(" ",$tempval);
                    if ($this->IsBrowser("MSIE 6")||$this->IsBrowser("MSIE 7")||$this->IsBrowser("MSIE 8"))
                    {
                        $eot = "url('$file.eot#') format('eot'),";
                    } else 
                    {
                        $eot = false;
                    }
					$linha = "@font-face{font-family:'$name';font-style:normal;font-weight:normal;src:".$eot."url('$file.woff') format('woff'),url('$file.ttf') format('truetype');}";
                }
                if (strtolower(substr(trim($linha),0,1))=="&")
                {
                    $linhai = explode("=",substr(trim($linha),1),2);
                    eval ('$this->defined[\''.trim($linhai[0])."']="."'".addslashes(trim($linhai[1]))."';");
                    $linha = "";
                }
                if (strtolower(substr(trim($linha),0,2))=="//")
                {
                    $linha = "";
                }
            }
            $this->css = join("",$f);
            
            $pcss = $this->CSSDecode();
            $ret = $this->ParsePCSSArray($pcss);
            $this->pcss = $ret;
        }
    }

    private function CheckSyntax($code)
    {
        return @eval('return true;' . $code);
    }

	private function CSSDecode()
    {
		$css = $this->css;
		$comment = false;
		$out = '$css=array("';
	 
		for ($i=0; $i<strlen($css); $i++)
        {
			if (!$comment)
            {
				if (($css[$i] == '{'))       $out .= 'зк'.$i.'"=> array("';
				else if (($css[$i] == '}'))   $out .= '"), "';
				else if ($css[$i] == ';')    $out .= '","';
				else                         $out .= $css[$i];
			}
			else $out .= $css[$i];
			if ($css[$i] == '*/' && $css[($i-1)]!="/*")
				$comment = !$comment;
		}
		
		$out .= '");';

        if ($this->CheckSyntax($out))
        {
		    eval($out);
        } else 
        {
            print "/* There's an error on PCSS code. Check the code and try again. */"; exit();
        }
		return $css;
	}    

	private function ParsePCSSArray($array,$parent=false)
    {
		$ret = "";
		foreach ($array as $class=>$val)
        {
			$class = trim($class);
			$tclass = explode("зк",$class); $class = $tclass[0];
			if (is_array($val))
            {
				if ($this->iniciado) $ret .= "}"; else $this->iniciado = true;
				$ret .= (($parent)?$parent." ":'').$class." { ";
				$ret .= $this->ParsePCSSArray($val,$parent." ".$class);

			} else 
            {
				if (trim($val)) $ret .= $this->Interpreter(trim($val))."; ";
			}
			
		}
		if (!$parent) $ret .= "}";
		
		return $ret;
	}	

    private function Interpreter($val)
    {
        if (strpos($val,":"))
        {
            list($dec,$v) = explode(":",$val);
            switch($dec):
                case "KERN": $final = "letter-spacing:".$v.$this->UNIT; break;
                
                case "LINEH": $final = "line-height:".$v.$this->UNIT; break;
                
                case "FSIZE": $final = "font-size:".$v.$this->UNIT; break;
                
                case "STROKE": 
                    list($n,$color) = explode(" ",$v);
                    $final = "border: solid $color ".$n.$this->UNIT;
                break;
                               
                case "BOUNDS": 
                    list($paddraw,$margraw) = explode(",",$v);
                    $final = array();
                    
                    if ($this->IsValid($paddraw))
                    {
                        $paddn = explode(" ",trim($paddraw));
                        $padda = array();
                        foreach ($paddn as $p) $padda[] = "$p".$this->UNIT;
                        $final[] = "padding:".join(" ",$padda);
                    }                        
                    if ($this->IsValid($margraw))
                    {
                        $margn = explode(" ",trim($margraw));
                        $marga = array();
                        foreach ($margn as $m) $marga[] = "$m".$this->UNIT;
                        $final[] = "margin:".join(" ",$marga);
                    }                    
                break;  
                              
                case "MAX":
                    list($w,$h) = explode(" ",$v);
                    $final = array();
                    if (!$this->IsBrowser("MSIE 6"))
                    {
                        if ($this->IsValid($w)) $final[] = "max-width:$w".$this->UNIT;
                        if ($this->IsValid($h)) $final[] = "max-height:$h".$this->UNIT;
                    } 
                    else 
                    {
                        if ($this->IsValid($w)) $final[] = "width:$w".$this->UNIT;
                        if ($this->IsValid($h)) $final[] = "height:$h".$this->UNIT; $final[] = "overflow:hidden";
                    }
                break;
                
                case "MIN":
                    list($w,$h) = explode(" ",$v);
                    $final = array();
                    if (!$this->IsBrowser("MSIE 6"))
                    {
                        if ($this->IsValid($w)) $final[] = "min-width:$w".$this->UNIT;
                        if ($this->IsValid($h)) $final[] = "min-height:$h".$this->UNIT;
                    } 
                    else 
                    {
                        if ($this->IsValid($w)) $final[] = "width:$w".$this->UNIT;
                        if ($this->IsValid($h)) $final[] = "height:$h".$this->UNIT;
                    }
                break;
                
                case "POS":
                    list($l,$t) = explode(" ",$v);
                    $final = array();
                    if ($this->IsValid($l)) $final[] = "left:$l".$this->UNIT;
                    if ($this->IsValid($t)) $final[] = "top:$t".$this->UNIT;
                    
                break;
                
                case "Z": $final = "z-index:".$v; break;
                
                case "SIZE": case "S":
                    list($w,$h) = explode(" ",$v);
                    $final = array();
                    if ($this->IsValid($w)) $final[] = "width:$w".$this->UNIT;
                    if ($this->IsValid($h)) $final[] = "height:$h".$this->UNIT;
                break;
                
                case "BGPOS":
                    list($x,$y) = explode(" ",$v);
                    $final = "background-position:";
                    if (is_numeric($x)) $final .= "$x".$this->UNIT." "; else $final .= "$x ";
                    if (is_numeric($y)) $final .= "$y".$this->UNIT; else $final = $y;
                break;
                
                case "BGIMG": $final = "background-image:url(".$this->IMGDIR."$v)"; break;
                
                case "BGCOLOR": $final = "background-color:$v"; break;
                
                case "BORDERRADIUS": case "RADIUS": 
                    $num = $v.$this->UNIT;
                    $final = array();
                    if ($this->IsBrowser("firefox"))
                    {
                        $final[] = "-moz-border-radius:$num;-moz-background-clip:padding";
                    } 
                    elseif ($this->IsBrowser("webkit"))
                    {
                        $final[] = "-webkit-border-radius:$num;-webkit-background-clip:padding-box";
                    }
                    $final[] = "border-radius:$num;background-clip:padding-box";
                break;
                
                case "BOXSHADOW": case "SHADOW":
                    $n = explode(" ",$v);
                    if (count($n)==3){ list($x,$d,$c) = $n; $y = $x; }
                    else list($x,$y,$d,$c) = $n;
                    $x .= $this->UNIT; $y .= $this->UNIT; $d .= $this->UNIT;
                    $str = "$x $y $d $c";
                    $final = array();
                    if ($this->IsBrowser("firefox"))
                    {
                        $final[] = "-moz-box-shadow:$str";
                    } 
                    elseif ($this->IsBrowser("webkit"))
                    {
                        $final[] = "-webkit-box-shadow:$str";
                    } 
                    elseif ($this->IsBrowser("MSIE 8")||$this->IsBrowser("MSIE 9"))
                    {
                        $final[] = "-ms-filter:\"progid:DXImageTransform.Microsoft.Shadow(Strength=$x, Direction=135, Color='$c')\"";
                    } 
                    elseif ($this->IsBrowser("MSIE"))
                    {
                        $final[] = "filter: progid:DXImageTransform.Microsoft.Shadow(Strength=$x, Direction=135, Color='$c')";
                    }
                    $final[] = "box-shadow:$str";
                break;
                
                case "OPACITY":
                    $ie = $v * 100;
                    $final = array();
                    if ($this->IsBrowser("firefox"))
                    {
                        $final[] = "-moz-opacity:$v";
                    } 
                    elseif ($this->IsBrowser("MSIE 6")||$this->IsBrowser("MSIE 7"))
                    {
                        $final[] = "filter:alpha(opacity=$ie)";
                    } 
                    elseif ($this->IsBrowser("MSIE 8")||$this->IsBrowser("MSIE 9"))
                    {
                        $final[] = "-ms-filter:\"progid:DXImageTransform.Microsoft.Alpha(Opacity=$ie)\"";
                    }
                    
                    $final[] = "opacity:$v;";
                break;
                
                case "TRANSFORM":
                    $final = array();
                    if ($this->IsBrowser("firefox"))
                    {
                        $final[] = "-moz-transform:$v";
                    } 
                    elseif ($this->IsBrowser("opera"))
                    {
                        $final[] = "-o-transform:$v";
                    } 
                    elseif ($this->IsBrowser("webkit"))
                    {
                        $final[] = "-webkit-transform:$v";
                    } 
                    elseif ($this->IsBrowser("MSIE"))
                    {
                        $final[] = "-ms-transform:$v";
                    }
                    $final[] = "transform:$v";
                break;
                
                case "TRANSITIONS":
                    if (is_numeric($v)) $v = "all {$v}s ease-out";
                    $final = array();
                    if ($this->IsBrowser("firefox"))
                    {
                        $final[] = "-moz-transition:$v";
                    } 
                    elseif ($this->IsBrowser("opera"))
                    {
                        $final[] = "-o-transition:$v";
                    } 
                    elseif ($this->IsBrowser("webkit"))
                    {
                        $final[] = "-webkit-transition:$v";
                    } 
                    elseif ($this->IsBrowser("MSIE"))
                    {
                        $final[] = "-ms-transition:$v";
                    }
                    $final[] = "transition:$v";
                break;
                
                case "GRADIENT":
                    list($c1,$c2) = explode(" ",$v);
                    $final = array();
                    $final[] = "background-color:$c1"; 
                    if ($this->IsBrowser("firefox"))
                    {
                        $final[] = "background-image:-moz-linear-gradient(top, $c1, $c2)";
                    } 
                    elseif ($this->IsBrowser("MSIE 10"))
                    {
                        $final[] = "background-image:-ms-linear-gradient(top, $c1, $c2)";
                    } 
                    elseif ($this->IsBrowser("MSIE"))
                    {
                        $final[] = "filter: progid:DXImageTransform.Microsoft.gradient(startColorStr='$c1', EndColorStr='$c2')";
                    } 
                    elseif ($this->IsBrowser("opera"))
                    {
                        $final[] = "background-image:-o-linear-gradient(top, $c1, $c2)";
                    } 
                    elseif ($this->IsBrowser("webkit"))
                    {
                        $final[] = "background-image:-webkit-gradient(linear, left top, left bottom, from($c1), to($c2));background-image:-webkit-linear-gradient(top, $c1, $c2)";
                    }
                    $final[] = "background-image:linear-gradient(top, $c1, $c2)";
                    
                break;
                
                case "ROTATE":
                    $vd = $v."deg";
                
                    if ($this->IsBrowser("firefox"))
                    {
                        $final[] = "-moz-transform:rotate($vd)";
                    } 
                    elseif ($this->IsBrowser("opera"))
                    {
                        $final[] = "-o-transform:rotate($vd)";
                    } 
                    elseif ($this->IsBrowser("webkit"))
                    {
                        $final[] = "-webkit-transform:rotate($vd)";
                    } 
                    elseif ($this->IsBrowser("MSIE 10"))
                    {
                        $final[] = "-ms-transform:rotate($vd)";
                    } 
                    elseif ($this->IsBrowser("MSIE"))
                    {
                        $cos = cos(deg2rad($v));
                        $sin = sin(deg2rad($v));
                        $final[] = "filter:progid:DXImageTransform.Microsoft.Matrix(M11=$cos, M12=".(-$sin).",M21=$sin, M22=$cos, sizingMethod='auto expand');
               ";
                    }
                    
                    $final[] = "transform:rotate($vd)";
                    $final[] = "zoom:1";
                break;
                
                default:
                    $final = $val;
                break;
            
            endswitch;
            
            if (is_array($final)) $final = join(";",$final);
            
        } 
        else 
        {
            if (@$this->$val)
                $final = $this->$val;
            else 
            {
                $final = $val;
            }  
        }
        return preg_replace_callback('/\&([a-zA-Z0-9_-]+)/', function($matches) { return $this->ReturnDefined($matches["$1"]); }, $final);
    }

    private function IsValid($val)
    {
        $val = trim($val);
        if ($val!=="" && strtolower($val) != "auto" && strtolower($val) != "a") return true;
        else return false;
    }

    private function ReturnDefined($val)
    {
        global $defined;
        if (substr($this->defined[$val],-1,1)==";") $this->defined[$val] = substr($this->defined[$val],0,-1);
        return $this->defined[$val];
    }

    private function IsBrowser($str)
    {
        return (strpos(strtolower($_SERVER["HTTP_USER_AGENT"]),strtolower($str)) !== false);
    }

	public function __toString()
    {
		return $this->pcss;
	}	
}