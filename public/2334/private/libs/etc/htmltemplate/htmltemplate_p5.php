<?php
/*
Htmltemplate for PHP5 ver 0.1
July/08/2003
Copyright (C) 2003 Hiroshi Ayukawa

This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, USA.

*/

/*

-----  Class diagram ------------------

+--------------+     (use)
| htmltemplate    |<----------  Client
+--------------+
           <>
            | (use to parse documents)
            V
+--------------+
| StandardParser |
+--------------+
            |
            |
           --
           V
+----------------+
|   TemplateParser  |
+----------------+
            <>
             |
             | (use to parse each tags)
             |
             V
    +----------+      +----------+            +-------------+
    |  TagBasis  |<|---| SimpleTag |---------| ConcreteTags  |
    +----------+      | MultiTag   |       +---+-------------+
                            +----------+      |        tag_val,tag_each,,,,etc.
                                                    |
                     +---------------+      |
                     | <<ShouldClose>> |<|--+
                     +---------------+

*/


/*
* Tag definition
*/

/* the interface of tags used in pair form 
    like {each ***} ... {/each}
 */
interface ShouldClose{}

/* the origine class of all tags */
abstract class TagBasis{
	protected $matchregexp;
	protected $fromstring;
	protected $tostring;
	protected $closestring;
	
	public function parse($str,$multilabels){
		while(preg_match($this->matchregexp,$str,$match)){
			$m=$match[1];
			$ind=$this->getIndex($m,$multilabels);

			$str=str_replace(
				sprintf($this->fromstring,$m),
				sprintf($this->tostring,$ind,$m),
			$str);
		}
		$str=$this->closeTag($str);
		return $str;
	}
	
	private function closeTag($str){
		if($this instanceof ShouldClose){
			$str=str_replace($this->closestring,
			"<?php
				}
			?>",
			$str);
		}
		return $str;
	}
	
	abstract protected function getIndex($m,$multilabels);
}

/* the super class of tags which handle non-array data  */
class SimpleTag extends TagBasis{
	protected function getIndex($m,$multilabels){
		$ar=split("/",$m);
		$ind="";
		$rui=array();
		foreach($ar as $x){
			array_push($rui,$x);
			$ind.="[\"$x\"]";
			if(in_array(join("/",$rui),$multilabels)){ 
				$ind.="[\$cnt[\"".join("/",$rui)."\"]]";
			}
		}
		return $ind;
	}
}

/* the super class of tags which handle array structure
 like {each *}  */
class MultiTag extends TagBasis{
	public function getLabelArray($str){
		$ans=array();
		preg_match_all($this->matchregexp,$str,$regans,PREG_SET_ORDER);
		foreach($regans as $x){
			$ans[]=$x[1];
		}
		return $ans;
	}
	
	protected function getIndex($m,$multilabels){
		$ar=split("/",$m);
		$ind="";
		$rui=array();
		$mattan=0;
		foreach($ar as $x){
			array_push($rui,$x);
			$ind.="[\"$x\"]";
			if($mattan!=count($ar)-1 && in_array(join("/",$rui),$multilabels)) {
				$ind.="[\$cnt[\"".join("/",$rui)."\"]]";
			}
			$mattan++;
		}
		return $ind;
	}
}


/*
*  parser classes
*/

/* main definition of parser */
class TemplateParser{
	private $tags=array("simple"=>array(),"multi"=>array());
	
	function add(TagBasis $tag){
		if($tag instanceof SimpleTag) $this->tags["simple"][]=$tag;
		elseif($tag instanceof MultiTag) $this->tags["multi"][]=$tag;
		else throw new Exception("Tag class is not well defined.");
		return $this;
	}
	
	function parse($str){
		reset($this->tags["multi"]);
		$multilabels=array();
		foreach($this->tags["multi"] as $x){
			$multilabels=array_merge($multilabels,$x->getLabelArray($str));
		}
		
		reset($this->tags["multi"]);
		foreach($this->tags["multi"] as $x){
			$str=$x->parse($str,$multilabels);
		}
		
		reset($this->tags["simple"]);
		foreach($this->tags["simple"] as $x){
			$str=$x->parse($str,$multilabels);
		}
		return $str;
	}
}


////////////////////////////////////////////////////


/*
*   Standard tag classes
*   these tags are defined as previous version of htmltemplate
*/

class tag_val extends SimpleTag{
	protected $matchregexp='/\{val ([^\}]+)\}/i';
	protected $fromstring="{val %s}";
	protected $tostring="<?php print nl2br(\$val%s); ?>\n";
}

class tag_rval extends SimpleTag{
	protected $matchregexp='/\{rval ([^\}]+)\}/i';
	protected $fromstring="{rval %s}";
	protected $tostring="<?php print \$val%s; ?>\n";
}

class tag_def extends SimpleTag implements ShouldClose{
	protected $matchregexp='/<!--\{def ([^\}]+)\}-->/i';
	protected $fromstring="<!--{def %s}-->";
	protected $tostring="<?php
		if((gettype(\$val%1\$s)!='array' && \$val%1\$s!=\"\") or (gettype(\$val%1\$s)=='array' && count(\$val%1\$s)>0)){ ?>";
	protected $closestring="<!--{/def}-->";
}

class tag_each extends MultiTag implements ShouldClose{
	protected $matchregexp='/<!--\{each ([^\}]+)\}-->/i';
	protected $fromstring="<!--{each %s}-->";
	protected $tostring="<?php
			for(\$cnt[\"%2\$s\"]=0;\$cnt[\"%2\$s\"]<count(\$val%1\$s);\$cnt[\"%2\$s\"]++){
				?>";
	protected $closestring="<!--{/each}-->";
}

/*
*   StandardParser
*   parser defined with above tags.
*   behave as previous htmltemplate
*/
class StandardParser extends TemplateParser{
	function StandardParser(){
		$this->add(new tag_val())->add(new tag_rval())->add(new tag_def())->add(new tag_each());
	}
}

/*
*  htmltemplate
*  the APIs defined after the manner of htmltemplate for PHP4
*  tmp file generation has not been implemented yet.(2003-07-08)
*/

class htmltemplate{
	private $parser;
	static private $instance;
	
	private function htmltemplate(){
		$this->parser=new StandardParser();
	}
	
	static public function getInstance(){
		if(! htmltemplate::$instance) htmltemplate::$instance=new htmltemplate();
		return htmltemplate::$instance;
	}
	
	static public function parse($str){
		return htmltemplate::getInstance()->parser->parse($str);
	}
	
	static function t_include($file,$data){
		print htmltemplate::t_buffer($file,$data);
	}
	
	static function t_buffer($file,$data){
		$val=$data;
		$all=fread(fopen($file,"rb"),filesize($file));
		$code=htmltemplate::parse($all);
		return eval('?>' .$code);
	}
	static function d_buffer($org_data,$data){
		$val=$data;
		$all=$org_data;
		$all = str_replace('<?', '<¡©', $all);
		$code=htmltemplate::parse($all);
		ob_start();
		eval('?>' .$code);
		$cnt=ob_get_contents();
		ob_end_clean();
		return $cnt;
		/*return eval('?>' .$code);*/
	}
}
?>