<?php
if(!defined('sugarEntry') || !sugarEntry) die('Not A Valid Entry Point');
/*********************************************************************************
 * The contents of this file are subject to the SugarCRM Public License Version
 * 1.1.3 ("License"); You may not use this file except in compliance with the
 * License. You may obtain a copy of the License at http://www.sugarcrm.com/SPL
 * Software distributed under the License is distributed on an "AS IS" basis,
 * WITHOUT WARRANTY OF ANY KIND, either express or implied.  See the License
 * for the specific language governing rights and limitations under the
 * License.
 *
 * All copies of the Covered Code must include on each user interface screen:
 *    (i) the "Powered by SugarCRM" logo and
 *    (ii) the SugarCRM copyright notice
 * in the same form as they appear in the distribution.  See full license for
 * requirements.
 *
 * The Original Code is: SugarCRM Open Source
 * The Initial Developer of the Original Code is SugarCRM, Inc.
 * Portions created by SugarCRM are Copyright (C) 2004-2006 SugarCRM, Inc.;
 * All Rights Reserved.
 * Contributor(s): ______________________________________.
 ********************************************************************************/

require_once('log4php/LoggerManager.php');
require_once('include/database/PearDatabase.php');
require_once('include/TreeView/TreeView.php');
require_once('modules/Documents/Document.php');
require_once('include/utils.php');

// User is used to store Forecast information.
class DocumentTreeView extends TreeView {

	function DocumentTreeView($seed_object) {
		parent::Treeview($seed_object);	
	}
	
	function traverse_tree($parent_node_id, $flag="", $depth="", $max_depth="1", $var_name="",$parent_ids_array=array()){
		
		
		// if the language is not set yet, then set it to the default language.
		if(isset($_SESSION['authenticated_user_language']) && $_SESSION['authenticated_user_language'] != '')
		{
			$current_language = $_SESSION['authenticated_user_language'];
		}
		else
		{
			$current_language = $sugar_config['default_language'];
		}

		//set module and application string arrays based upon selected language
		$app_strings = return_application_language($current_language);
		$app_list_strings = return_app_list_strings_language($current_language);
		$mod_strings = return_module_language($current_language, 'Documents');

				
		$category_id='';
		$subcategory_id='';
		$document_name='';
			
		$processing=1;  //1-categories, 2-sub-categories, 3-documents. 
		if (count($parent_ids_array) ==0) {    //fill category.
		  $processing=1;
		}
		if (count($parent_ids_array) ==1) {    //fill sub-categories
		  $processing=2;
		  $category_id=$parent_ids_array[0];	
		}
		if (count($parent_ids_array) >=2) {    //fill documents.
		  $processing=3;
		  $subcategory_id=$parent_ids_array[0];	
		  $category_id=$parent_ids_array[1];	
		}
		
		if ($depth == 0){
			if($parent_node_id=="") echo "var ".$var_name." = [ ['".$mod_strings[$this->seed_object->branch_jscript_map[$this->tree_type]['tree_title']]."', 0, {'fn':0, 'st':1}, \n"; //prepare JS var record
			if($parent_node_id!="") echo "var ".$var_name." = [ \n"; //prepare JS var record
		}	
		
	  	//if this is the start of the growth, then set the initial tree contents
		if(!isset($tree_contents)) $tree_contents = "";
		
		$query = $this->seed_object->tree_query($parent_node_id,$parent_ids_array);	
		$result =$this->seed_object->db->query($query,true, "Error Traversing Tree");
	
		$folder=1;
		$disable=0;
		if (count($parent_ids_array) >=2) $folder=0;
		 	
		// We have some branches and leafs.
		while ($row = $this->seed_object->db->fetchByAssoc($result)) {
				
			if(!$var_name) $tree_contents .= ',';	//comma logic (JS var name is also comma flag)
			$var_name = 0;	//drop flag

			$state = "0";
			$special_css = "";
			$disable_flag = ", 'df':'0'";
			$node_id=$row['id'];
			
			if ($processing==1) {
				if (empty($row['id'])) { 
					$node_id='NULL_CAT';
					$category_id='NULL_CAT';
					$branch_name = $mod_strings['LBL_CAT_OR_SUBCAT_UNSPEC'];			
				} else {
					$category_id=$node_id;
					$branch_name = $app_list_strings['document_category_dom'][$node_id];	
				}
			}
			
			if ($processing==2) {
				if (empty($row['id'])) { 
					$node_id='NULL_SUBCAT';
					$subcategory_id='NULL_SUBCAT';
					$branch_name = $mod_strings['LBL_CAT_OR_SUBCAT_UNSPEC'];			
				} else {
					$subcategory_id=$node_id;
					$branch_name = $app_list_strings['document_subcategory_dom'][$node_id];	
				}
			}

			if ($processing==3) {
				$document_name=$row['name'];
				$branch_name=$row['name'];
			}
			
			$javascript_url = $this->get_javascript_url("Branch",  $category_id,  $subcategory_id, $document_name);

			if ($folder==1) {
 				$tree_contents .= "['".$branch_name."', \"$javascript_url\", {'id':'$node_id', 'fn':$folder, 'st':$state $special_css $disable_flag} \n"; 
			} else {
				$tree_contents .= "['$branch_name', \"$javascript_url\", {'id':'$node_id', 'fn':$folder $special_css} \n"; 
			}
		
			$tree_contents .= "] \n"; //end item's record				
		
			//end while loop		
		}
			
		if($depth == 0){
			if($parent_node_id==""){
				$tree_contents .= "] \n ];"; //end JS var record
			}
			
			if($parent_node_id!=""){
				$tree_contents .= "];"; //end JS var record
			}
		}
			
		return $tree_contents;
		//end function traverse_tree 
	}  
	
	function get_javascript_url($type,$category_id, $subcategory_id, $document_name){
	
		if($type=="Branch"){
			$str = $this->seed_object->branch_jscript_map[$this->tree_type]['function'];					
	
			$document_name = htmlspecialchars($document_name);
			eval("\$str = \"$str\";");
			return "$str";		
		}
						
		if($type=="Leaf"){
			$str = $this->seed_object->leaf_jscript_map[$this->tree_type]['function'];
			
			$document_name = htmlspecialchars($document_name);
			eval("\$str = \"$str\";");
			return "$str";
		}
	}
}
?>
