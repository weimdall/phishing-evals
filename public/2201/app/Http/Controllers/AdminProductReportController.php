<?php namespace App\Http\Controllers;

	use Session;
	use Request;
	use DB;
	use CRUDBooster;
	use CB;
	use PDF;
	use Illuminate\Support\Facades\App;
	use Maatwebsite\Excel\Facades\Excel;


	class AdminProductReportController extends \crocodicstudio\crudbooster\controllers\CBController {

	    public function cbInit() {

			# START CONFIGURATION DO NOT REMOVE THIS LINE
			$this->title_field = "product_name";
			$this->limit = "20";
			$this->orderby = "id,desc";
			$this->global_privilege = false;
			$this->button_table_action = false;
			$this->button_bulk_action = false;
			$this->button_action_style = "button_icon";
			$this->button_add = false;
			$this->button_edit = false;
			$this->button_delete = false;
			$this->button_detail = false;
			$this->button_show = false;
			$this->button_filter = true;
			$this->button_import = false;
			$this->button_export = false;
			$this->table = "master_po";

			if (CRUDBooster::myPrivilegeId() == 1) {
				$this->custom_filter = true;
				$this->custom_filter_data = array();
			}
			if($this->custom_filter){
				if(!empty(Request::get('custom_filter'))){
					$this->custom_filter_selected = Request::get('custom_filter');
				}else{
					$this->button_export = false;
					$this->custom_filter_selected = "";

				}
				$this->custom_filter_first_text = "Please select client";
					 $client_data = DB::table('cms_users')
	                     ->select('id','name')
	                     ->where('id_cms_privileges',3)
	                     ->where('status',1)
	                     ->get();
				$this->custom_filter_data = $client_data;				
			}	
			if (isset($this->custom_filter_selected) && !empty($this->custom_filter_selected)) {
				$client_id = $this->custom_filter_selected;
				
			}elseif (CRUDBooster::myPrivilegeId() == 3) {
				//$this->custom_filter_selected = CRUDBooster::myId();
				$client_id = CRUDBooster::myId();
			}
			$this->custom_filter2 = true;
			//$this->custom_filter2_name = "product_filter";
			$this->custom_filter_data2 = array();
			if($this->custom_filter2){
				if(!empty(Request::get('product_filter'))){
					/*if (CRUDBooster::myPrivilegeId() == 1 && empty(Request::get('custom_filter'))) {
						$this->button_export = false;
					}*/
					
					$this->button_export = true;
					$this->custom_filter_selected2 = Request::get('product_filter');
				}else{
					$this->button_export = false;
					$this->custom_filter_selected2 = "";
				}
				$this->custom_filter_first_text2 = "Please select Product";
					 $product_data = DB::table('master_products')
	                     ->select('id','name')
	                     ->where('client_id',$client_id)
	                     ->where('status',1)
	                     ->get();
				$this->custom_filter_data2 = $product_data;				
			}	
			# END CONFIGURATION DO NOT REMOVE THIS LINE

			# START COLUMNS DO NOT REMOVE THIS LINE
			$this->col = [];
			//$this->col[] = ["label"=>"Product","name"=>"id","join"=>"master_po_detail,product_name"];
			//
			//$this->col[] = ["label"=>"Date","name"=>"date","callback_php"=>'date("d-M-Y",strtotime($row->date))'];

			$this->col[] = ["label"=>"Date","name"=>"po_date","callback"=>function($row){
				/*$po = DB::table('master_po as po')
						->where('po.id', $row->master_po_id)
						->first();*/
					return date("d-M-Y",strtotime($row->po_date));	
			}];

			$this->col[] = ["label"=>"Particulars","name"=>"vendor_name"];
			$this->col[] = ["label"=>"PO Number","name"=>"po_number"];

			$this->col[] = ["label"=>"Product Name","name"=>"id","callback"=>function($row){
				$q = DB::table('master_po_detail as pod')
						->where('pod.master_po_id', $row->id);
						if (!empty($this->custom_filter_selected)) {
							$q->where('pod.master_products_id',$this->custom_filter_selected);
						}
						
					$po = $q->first();
					return $po->product_name;	
			}];
			$this->col[] = ["label"=>"Quantity","name"=>"id","callback"=>function($row){
				$q = DB::table('master_po_detail as pod')
						->where('pod.master_po_id', $row->id);
						if (!empty($this->custom_filter_selected)) {
							$q->where('pod.master_products_id',$this->custom_filter_selected);
						}
						
					$po = $q->first();
					return $po->quantity;	
			}];
			$this->col[] = ["label"=>"Price","name"=>"id","callback"=>function($row){


				$q = DB::table('master_po_detail as pod')
						->where('pod.master_po_id', $row->id);
						//$this->custom_filter_selected=229;
						if (!empty($this->custom_filter_selected)) {
							$q->where('pod.master_products_id',$this->custom_filter_selected);
						}
						
					$po = $q->first();
					//echo"<pre>";print_r($po);exit;
					return $po->price;	
			}];
			$this->col[] = ["label"=>"Value","name"=>"id","callback"=>function($row){
				$q = DB::table('master_po_detail as pod')
						->where('pod.master_po_id', $row->id);
						if (!empty($this->custom_filter_selected)) {
							$q->where('pod.master_products_id',$this->custom_filter_selected);
						}
						
					$po = $q->first();
					return $po->quantity*$po->price;	
			}];
			/*$this->col[] = ["label"=>"Quantity","name"=>"quantity"];
			$this->col[] = ["label"=>"Rate","name"=>"price"];
			$this->col[] = ["label"=>"Value","name"=>"price","callback"=>function($row){
				return number_format(($row->quantity * $row->price),2);
			}];*/
			# END COLUMNS DO NOT REMOVE THIS LINE

			# START FORM DO NOT REMOVE THIS LINE
			$this->form = [];

			# END FORM DO NOT REMOVE THIS LINE

			# OLD START FORM
			//$this->form = [];
			//$this->form[] = ["label"=>"Master Invoice Id","name"=>"master_invoice_id","type"=>"select2","required"=>TRUE,"validation"=>"required|integer|min:0","datatable"=>"master_invoice,customer_name"];
			//$this->form[] = ["label"=>"Master Products Id","name"=>"master_products_id","type"=>"select2","required"=>TRUE,"validation"=>"required|integer|min:0","datatable"=>"master_products,name"];
			//$this->form[] = ["label"=>"Hsn No","name"=>"hsn_no","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Product Name","name"=>"product_name","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Product Code","name"=>"product_code","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Product Description","name"=>"product_description","type"=>"textarea","required"=>TRUE,"validation"=>"required|string|min:5|max:5000"];
			//$this->form[] = ["label"=>"Unit Type","name"=>"unit_type","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Master Categories Id","name"=>"master_categories_id","type"=>"select2","required"=>TRUE,"validation"=>"required|integer|min:0","datatable"=>"master_categories,name"];
			//$this->form[] = ["label"=>"Price","name"=>"price","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Reverse Cal Price","name"=>"reverse_cal_price","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Quantity","name"=>"quantity","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Sub Total","name"=>"sub_total","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Discount","name"=>"discount","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Taxable Amount","name"=>"taxable_amount","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Tax","name"=>"tax","type"=>"number","required"=>TRUE,"validation"=>"required|integer|min:0"];
			//$this->form[] = ["label"=>"Total","name"=>"total","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Status","name"=>"status","type"=>"text","required"=>TRUE,"validation"=>"required|min:1|max:255"];
			//$this->form[] = ["label"=>"Uodated At","name"=>"uodated_at","type"=>"datetime","required"=>TRUE,"validation"=>"required|date_format:Y-m-d H:i:s"];
			# OLD END FORM

			/* 
	        | ---------------------------------------------------------------------- 
	        | Sub Module
	        | ----------------------------------------------------------------------     
			| @label          = Label of action 
			| @path           = Path of sub module
			| @foreign_key 	  = foreign key of sub table/module
			| @button_color   = Bootstrap Class (primary,success,warning,danger)
			| @button_icon    = Font Awesome Class  
			| @parent_columns = Sparate with comma, e.g : name,created_at
	        | 
	        */
	        $this->sub_module = array();


	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add More Action Button / Menu
	        | ----------------------------------------------------------------------     
	        | @label       = Label of action 
	        | @url         = Target URL, you can use field alias. e.g : [id], [name], [title], etc
	        | @icon        = Font awesome class icon. e.g : fa fa-bars
	        | @color 	   = Default is primary. (primary, warning, succecss, info)     
	        | @showIf 	   = If condition when action show. Use field alias. e.g : [id] == 1
	        | 
	        */
	        $this->addaction = array();


	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add More Button Selected
	        | ----------------------------------------------------------------------     
	        | @label       = Label of action 
	        | @icon 	   = Icon from fontawesome
	        | @name 	   = Name of button 
	        | Then about the action, you should code at actionButtonSelected method 
	        | 
	        */
	        $this->button_selected = array();

	                
	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add alert message to this module at overheader
	        | ----------------------------------------------------------------------     
	        | @message = Text of message 
	        | @type    = warning,success,danger,info        
	        | 
	        */
	        $this->alert        = array();
	                

	        
	        /* 
	        | ---------------------------------------------------------------------- 
	        | Add more button to header button 
	        | ----------------------------------------------------------------------     
	        | @label = Name of button 
	        | @url   = URL Target
	        | @icon  = Icon from Awesome.
	        | 
	        */
	        $this->index_button = array();



	        /* 
	        | ---------------------------------------------------------------------- 
	        | Customize Table Row Color
	        | ----------------------------------------------------------------------     
	        | @condition = If condition. You may use field alias. E.g : [id] == 1
	        | @color = Default is none. You can use bootstrap success,info,warning,danger,primary.        
	        | 
	        */
	        $this->table_row_color = array();     	          

	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | You may use this bellow array to add statistic at dashboard 
	        | ---------------------------------------------------------------------- 
	        | @label, @count, @icon, @color 
	        |
	        */
	        $this->index_statistic = array();



	        /*
	        | ---------------------------------------------------------------------- 
	        | Add javascript at body 
	        | ---------------------------------------------------------------------- 
	        | javascript code in the variable 
	        | $this->script_js = "function() { ... }";
	        |
	        */
	        $this->script_js = "$(function() {

					 $('#table_dashboard').stickyTable({
					    overflowy:true
					 });
					  
	        })";


            /*
	        | ---------------------------------------------------------------------- 
	        | Include HTML Code before index table 
	        | ---------------------------------------------------------------------- 
	        | html code to display it before index table
	        | $this->pre_index_html = "<p>test</p>";
	        |
	        */
	        $this->pre_index_html = null;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include HTML Code after index table 
	        | ---------------------------------------------------------------------- 
	        | html code to display it after index table
	        | $this->post_index_html = "<p>test</p>";
	        |
	        */
	        $this->post_index_html = null;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include Javascript File 
	        | ---------------------------------------------------------------------- 
	        | URL of your javascript each array 
	        | $this->load_js[] = asset("myfile.js");
	        |
	        */
	        $this->load_js = array();
	        $this->load_js[] = asset("js/jquery-stickytable.js");
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Add css style at body 
	        | ---------------------------------------------------------------------- 
	        | css code in the variable 
	        | $this->style_css = ".style{....}";
	        |
	        */
	        $this->style_css = NULL;
	        
	        
	        
	        /*
	        | ---------------------------------------------------------------------- 
	        | Include css File 
	        | ---------------------------------------------------------------------- 
	        | URL of your css each array 
	        | $this->load_css[] = asset("myfile.css");
	        |
	        */
	        $this->load_css = array();
	        $this->load_css[] = asset("css/scroll.css");
	        $this->load_css[] = asset("css/jquery-stickytable.css");
	        
	        
	    }


	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for button selected
	    | ---------------------------------------------------------------------- 
	    | @id_selected = the id selected
	    | @button_name = the name of button
	    |
	    */
	    public function actionButtonSelected($id_selected,$button_name) {
	        //Your code here
	            
	    }


	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate query of index result 
	    | ---------------------------------------------------------------------- 
	    | @query = current sql query 
	    |
	    */
	    public function hook_query_index(&$query) {
	        //Your code here
	        $query->where('master_po.status','!=','deleted');
	         if (CRUDBooster::myPrivilegeId() == 3) {
	         	//print_r($user);exit;
	         	$query->where('master_po.client_id',CRUDBooster::myId());
	         }	
	        if (isset($this->custom_filter_selected) && !empty($this->custom_filter_selected)) {
	         	//print_r($this->custom_filter_selected );exit;
	         	$query->where('master_po.client_id',$this->custom_filter_selected);
	         }
	         if (isset($this->custom_filter_selected2) && !empty($this->custom_filter_selected2)) {
	         	
	         	$query->where('master_po_detail.master_products_id',$this->custom_filter_selected2);
	         }
	         $query->groupBy('master_po_detail.id');
	            
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate row of index table html 
	    | ---------------------------------------------------------------------- 
	    |
	    */    
	    public function hook_row_index($column_index,&$column_value) {	        
	    	//Your code here
	    }

	    /*
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before add data is execute
	    | ---------------------------------------------------------------------- 
	    | @arr
	    |
	    */
	    public function hook_before_add(&$postdata) {        
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after add public static function called 
	    | ---------------------------------------------------------------------- 
	    | @id = last insert id
	    | 
	    */
	    public function hook_after_add($id) {        
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for manipulate data input before update data is execute
	    | ---------------------------------------------------------------------- 
	    | @postdata = input post data 
	    | @id       = current id 
	    | 
	    */
	    public function hook_before_edit(&$postdata,$id) {        
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after edit public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_after_edit($id) {
	        //Your code here 

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command before delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_before_delete($id) {
	        //Your code here

	    }

	    /* 
	    | ---------------------------------------------------------------------- 
	    | Hook for execute command after delete public static function called
	    | ----------------------------------------------------------------------     
	    | @id       = current id 
	    | 
	    */
	    public function hook_after_delete($id) {
	        //Your code here

	    }

	    //By the way, you can still create your own method in here... :) 

	    public function getIndex()
    {
        $this->cbLoader();

        $module = CRUDBooster::getCurrentModule();

        if (! CRUDBooster::isView() && $this->global_privilege == false) {
            CRUDBooster::insertLog(trans('crudbooster.log_try_view', ['module' => $module->name]));
            CRUDBooster::redirect(CRUDBooster::adminPath(), trans('crudbooster.denied_access'));
        }

        if (Request::get('parent_table')) {
            $parentTablePK = CB::pk(g('parent_table'));
            $data['parent_table'] = DB::table(Request::get('parent_table'))->where($parentTablePK, Request::get('parent_id'))->first();
            if (Request::get('foreign_key')) {
                $data['parent_field'] = Request::get('foreign_key');
            } else {
                $data['parent_field'] = CB::getTableForeignKey(g('parent_table'), $this->table);
            }

            if ($parent_field) {
                foreach ($this->columns_table as $i => $col) {
                    if ($col['name'] == $parent_field) {
                        unset($this->columns_table[$i]);
                    }
                }
            }
        }

        $data['table'] = $this->table;
        $data['table_pk'] = CB::pk($this->table);
        $data['page_title'] = $module->name;
        $data['page_description'] = trans('crudbooster.default_module_description');
        $data['date_candidate'] = $this->date_candidate;
        if (CRUDBooster::myPrivilegeId() == 3) {
	         	$client_id = crudbooster::myId();
        }else{
        	$client_id = Request::get('custom_filter');
        }	
        if (!empty($client_id)) {
        	$client=DB::table('cms_users')
		    	->where('id',$client_id)
		    	->select('financial_year')
		    	->first();
		    $data['financial_year'] = $client->financial_year; 
		     if (empty($client->financial_year)) {
		                $year = date("Y");
		                $data['financial_year'] = $year."-".($year+1);
		        }
        }
        
        if (CRUDBooster::myPrivilegeId() == 1 && (empty($this->custom_filter_selected2) || empty($this->custom_filter_selected))) {

        		$data['limit'] = $limit = 0.1;
        }else if(empty($this->custom_filter_selected2)) {

        	$data['limit'] = $limit = 0.1;
        }
       else{
        	$data['limit'] = $limit = (Request::get('limit')) ? Request::get('limit') : $this->limit;
        }
        //$data['limit'] = $limit = (Request::get('limit')) ? Request::get('limit') : $this->limit;

       // print_r($this->limit);exit;
        $tablePK = $data['table_pk'];
        $table_columns = CB::getTableColumns($this->table);
        $result = DB::table($this->table);
        //$result->select(DB::raw($this->table.".".$this->primary_key));

        if (Request::get('parent_id')) {
            $table_parent = $this->table;
            $table_parent = CRUDBooster::parseSqlTable($table_parent)['table'];
            $result->where($table_parent.'.'.Request::get('foreign_key'), Request::get('parent_id'));
        }

        $this->hook_query_index($result);

        if (in_array('deleted_at', $table_columns)) {
            $result->where($this->table.'.deleted_at', null);
        }
		//$result->leftjoin('master_po_detail','master_po.id',"=",'master_po_detail.master_po_id');
        $alias = [];
        $join_alias_count = 0;
        $join_table_temp = [];
        $table = $this->table;
        $columns_table = $this->columns_table;
        //echo"payal<pre>";print_r($columns_table);exit;
        foreach ($columns_table as $index => $coltab) {

            $join = @$coltab['join'];
            $join_where = @$coltab['join_where'];
            $join_id = @$coltab['join_id'];
            $field = @$coltab['name'];
            $join_table_temp[] = $table;

            if (! $field) {
                continue;
            }

            if (strpos($field, ' as ') !== false) {
                $field = substr($field, strpos($field, ' as ') + 4);
                $field_with = (array_key_exists('join', $coltab)) ? str_replace(",", ".", $coltab['join']) : $field;
                $result->addselect(DB::raw($coltab['name']));
                $columns_table[$index]['type_data'] = 'varchar';
                $columns_table[$index]['field'] = $field;
                $columns_table[$index]['field_raw'] = $field;
                $columns_table[$index]['field_with'] = $field_with;
                $columns_table[$index]['is_subquery'] = true;
                continue;
            }

            if (strpos($field, '.') !== false) {
                $result->addselect($field);
            } else {
                $result->addselect($table.'.'.$field);
            }

            $field_array = explode('.', $field);

            if (isset($field_array[1])) {
                $field = $field_array[1];
                $table = $field_array[0];
            } else {
                $table = $this->table;
            }

            if ($join) {

                $join_exp = explode(',', $join);

                $join_table = $join_exp[0];
                $joinTablePK = CB::pk($join_table);
                $join_column = $join_exp[1];
                $join_alias = str_replace(".", "_", $join_table);

                if (in_array($join_table, $join_table_temp)) {
                    $join_alias_count += 1;
                    $join_alias = $join_table.$join_alias_count;
                }
                $join_table_temp[] = $join_table;

                $result->leftjoin($join_table.' as '.$join_alias, $join_alias.(($join_id) ? '.'.$join_id : '.'.$joinTablePK), '=', DB::raw($table.'.'.$field.(($join_where) ? ' AND '.$join_where.' ' : '')));
                $result->addselect($join_alias.'.'.$join_column.' as '.$join_alias.'_'.$join_column);

                $join_table_columns = CRUDBooster::getTableColumns($join_table);
                if ($join_table_columns) {
                    foreach ($join_table_columns as $jtc) {
                        $result->addselect($join_alias.'.'.$jtc.' as '.$join_alias.'_'.$jtc);
                    }
                }

                $alias[] = $join_alias;
                $columns_table[$index]['type_data'] = CRUDBooster::getFieldType($join_table, $join_column);
                $columns_table[$index]['field'] = $join_alias.'_'.$join_column;
                $columns_table[$index]['field_with'] = $join_alias.'.'.$join_column;
                $columns_table[$index]['field_raw'] = $join_column;

                @$join_table1 = $join_exp[2];
                @$joinTable1PK = CB::pk($join_table1);
                @$join_column1 = $join_exp[3];
                @$join_alias1 = $join_table1;

                if ($join_table1 && $join_column1) {

                    if (in_array($join_table1, $join_table_temp)) {
                        $join_alias_count += 1;
                        $join_alias1 = $join_table1.$join_alias_count;
                    }

                    $join_table_temp[] = $join_table1;

                    $result->leftjoin($join_table1.' as '.$join_alias1, $join_alias1.'.'.$joinTable1PK, '=', $join_alias.'.'.$join_column);
                    $result->addselect($join_alias1.'.'.$join_column1.' as '.$join_column1.'_'.$join_alias1);
                    $alias[] = $join_alias1;
                    $columns_table[$index]['type_data'] = CRUDBooster::getFieldType($join_table1, $join_column1);
                    $columns_table[$index]['field'] = $join_column1.'_'.$join_alias1;
                    $columns_table[$index]['field_with'] = $join_alias1.'.'.$join_column1;
                    $columns_table[$index]['field_raw'] = $join_column1;
                }
            } else {

                if(isset($field_array[1])) {                    
                    $result->addselect($table.'.'.$field.' as '.$table.'_'.$field);
                    $columns_table[$index]['type_data'] = CRUDBooster::getFieldType($table, $field);
                    $columns_table[$index]['field'] = $table.'_'.$field;
                    $columns_table[$index]['field_raw'] = $table.'.'.$field;
                }else{
                    $result->addselect($table.'.'.$field);
                    $columns_table[$index]['type_data'] = CRUDBooster::getFieldType($table, $field);
                    $columns_table[$index]['field'] = $field;
                    $columns_table[$index]['field_raw'] = $field;
                }
                
                $columns_table[$index]['field_with'] = $table.'.'.$field;
            }
        }

        $result->leftjoin('master_po_detail','master_po.id',"=",'master_po_detail.master_po_id');

        
        if (Request::get('q')) {
            $result->where(function ($w) use ($columns_table, $request) {
                foreach ($columns_table as $col) {
                    if (! $col['field_with']) {
                        continue;
                    }
                    if ($col['is_subquery']) {
                        continue;
                    }
                    $w->orwhere($col['field_with'], "like", "%".Request::get("q")."%");
                }
            });
        }

        if (Request::get('where')) {
            foreach (Request::get('where') as $k => $v) {
                $result->where($table.'.'.$k, $v);
            }
        }

        $filter_is_orderby = false;
        if (Request::get('filter_column')) {

            $filter_column = Request::get('filter_column');
            $result->where(function ($w) use ($filter_column, $fc) {
                foreach ($filter_column as $key => $fc) {

                    $value = @$fc['value'];
                    $type = @$fc['type'];

                    if ($type == 'empty') {
                        $w->whereNull($key)->orWhere($key, '');
                        continue;
                    }

                    if ($value == '' || $type == '') {
                        continue;
                    }

                    if ($type == 'between') {
                        continue;
                    }

                    switch ($type) {
                        default:
                            if ($key && $type && $value) {
                                $w->where($key, $type, $value);
                            }
                            break;
                        case 'like':
                        case 'not like':
                            $value = '%'.$value.'%';
                            if ($key && $type && $value) {
                                $w->where($key, $type, $value);
                            }
                            break;
                        case 'in':
                        case 'not in':
                            if ($value) {
                                $value = explode(',', $value);
                                if ($key && $value) {
                                    $w->whereIn($key, $value);
                                }
                            }
                            break;
                    }
                }
            });

            foreach ($filter_column as $key => $fc) {
                $value = @$fc['value'];
                $type = @$fc['type'];
                $sorting = @$fc['sorting'];

                if ($sorting != '') {
                    if ($key) {
                        $result->orderby($key, $sorting);
                        $filter_is_orderby = true;
                    }
                }

                if ($type == 'between') {
                    if ($key && $value) {
                        $result->whereBetween($key, $value);
                    }
                } else {
                    continue;
                }
            }
        }

       		// select `pod`.`id`,`po`.`po_date` AS `date`,`po`.`status` from `master_po` as `po` left join `master_po_detail` as `pod` on `pod`.`master_po_id` = `po`.`id` where `pod`.`status` != 'deleted' and `po`.`client_id` = 3 and `pod`.`master_products_id` = 157 group by `pod`.`id`  UNION ALL select `invd`.`id`,`inv`.`date`,`inv`.`status` from `master_invoice` as `inv` left join `master_invoice_detail` as `invd` on `invd`.`master_invoice_id` = `inv`.`id` where `invd`.`status` != 'deleted' and `inv`.`client_id` = 3 and `invd`.`master_products_id` = 157 group by `invd`.`id`

       		$query  = DB::table('master_invoice as inv')
        		->leftjoin('master_invoice_detail as invd','invd.master_invoice_id',"=",'inv.id');
        		$query->join('master_product_stock_detail as stock', function($join)
				 {
				   $join->on('invd.id', '=', 'stock.master_invoice_detail_id');
				   $join->where('stock.status', 1);
				   $join->where('stock.stock_status', '=', 'closing');

				 });
        		//->leftjoin('master_product_stock_detail as stock','invd.master_invoice_id',"=",'stock.id');
        		$query->where('invd.status','!=','deleted');
        		$query->select('invd.id','invd.status','inv.date','invd.product_name','invd.quantity','invd.price','invd.sub_total','invd.unit_type','inv.customer_name as cust_name','inv.invoice_no','stock.quantity as qty_total','stock.price as avg_price','invd.created_at');
        	if (CRUDBooster::myPrivilegeId() == 3) {
	         	//print_r($user);exit;
	         	$query->where('inv.client_id',CRUDBooster::myId());
	         }	
	        if (isset($this->custom_filter_selected) && !empty($this->custom_filter_selected)) {
	         	//print_r($this->client_id );exit;
	         	$query->where('inv.client_id',Request::get('custom_filter'));
	         }
	         if (isset($this->custom_filter_selected2) && !empty($this->custom_filter_selected2)) {
	         	//print_r($this->client_id );exit;
	         	$query->where('invd.master_products_id',Request::get('product_filter'));
	         }
	         if (Request::get('filter_column')) {
	         foreach ($filter_column as $key => $fc) {
                $value = @$fc['value'];
                $type = @$fc['type'];
                $sorting = @$fc['sorting'];

                if ($sorting != '') {
                    if ($key) {
                        $result->orderby($key, $sorting);
                        $filter_is_orderby = true;
                    }
                }

                if ($type == 'between') {
                    if ($key && $value) {
                        $query->whereBetween('inv.date', $value);
                    }
                } else {
                    continue;
                }
            }
        }
	         $invoice_data = $query->groupBy('invd.id');
        		
			//$invoice_data = $query->get();

			$result  = DB::table('master_po as po')
        		->leftjoin('master_po_detail as pod','pod.master_po_id',"=",'po.id');
        		$result->join('master_product_stock_detail as stock', function($join)
				 {
				   $join->on('pod.id', '=', 'stock.master_po_detail_id');
				   $join->where('stock.status', 1);
				   $join->where('stock.stock_status', '=', 'purchase');

				 });
        		$result->where('pod.status','!=','deleted');
        		$result->select('pod.id','pod.status','po.po_date as date','pod.product_name','pod.quantity','pod.price','pod.sub_total','pod.unit_type','po.vendor_name as cust_name','po.po_number as invoice_no','stock.quantity as qty_total','stock.price as avg_price','pod.created_at');
        		 $result->union($invoice_data);


        	if (CRUDBooster::myPrivilegeId() == 3) {
	         	//print_r($user);exit;
	         	$result->where('po.client_id',CRUDBooster::myId());
	         }	
	        if (isset($this->custom_filter_selected) && !empty($this->custom_filter_selected)) {
	         	//print_r($this->client_id );exit;
	         	$result->where('po.client_id',Request::get('custom_filter'));
	         }
	         if (isset($this->custom_filter_selected2) && !empty($this->custom_filter_selected2)) {
	         	//print_r($this->client_id );exit;
	         	$result->where('pod.master_products_id',Request::get('product_filter'));
	         }
	         if (Request::get('filter_column')) {
	         foreach ($filter_column as $key => $fc) {
                $value = @$fc['value'];
                $type = @$fc['type'];
                $sorting = @$fc['sorting'];

                if ($sorting != '') {
                    if ($key) {
                        $result->orderby($key, $sorting);
                        $filter_is_orderby = true;
                    }
                }

                if ($type == 'between') {
                    if ($key && $value) {
                        $result->whereBetween('po.po_date', $value);
                    }
                } else {
                    continue;
                }
            }
        }
        /* if ($filter_is_orderby == true) {
            $data['result'] = $result->paginate($limit);
        } else {*/
             $data['result'] = $result->orderby('created_at','ASC')->paginate($limit);
        //}

        	//$result->orderBy('date','DESC');
	        $result->groupBy('pod.id');
			$data['po_data'] = $result->get();

			if (isset($this->custom_filter_selected2) && !empty($this->custom_filter_selected2)) {

	         	$data['open_stock'] = DB::table('master_product_stock_detail')
	         		->select('quantity','price')
					->where('master_products_id',$this->custom_filter_selected2)
					->where('stock_status', '=', 'opening')
					->where('status', 1)
					->first();
			}
			


       //echo "<pre>" ;print_r($data['result']);exit;

        $data['columns'] = $columns_table;

        if ($this->index_return) {
            return $data;
        }

        //LISTING INDEX HTML
        $addaction = $this->data['addaction'];

        if ($this->sub_module) {
            foreach ($this->sub_module as $s) {
                $table_parent = CRUDBooster::parseSqlTable($this->table)['table'];
                $addaction[] = [
                    'label' => $s['label'],
                    'icon' => $s['button_icon'],
                    'url' => CRUDBooster::adminPath($s['path']).'?return_url='.urlencode(Request::fullUrl()).'&parent_table='.$table_parent.'&parent_columns='.$s['parent_columns'].'&parent_columns_alias='.$s['parent_columns_alias'].'&parent_id=['.(! isset($s['custom_parent_id']) ? "id" : $s['custom_parent_id']).']&foreign_key='.$s['foreign_key'].'&label='.urlencode($s['label']),
                    'color' => $s['button_color'],
                    'showIf' => $s['showIf'],
                ];
            }
        }

        $mainpath = CRUDBooster::mainpath();
        $orig_mainpath = $this->data['mainpath'];
        $title_field = $this->title_field;
        $html_contents = [];
        $page = (Request::get('page')) ? Request::get('page') : 1;
        $number = ($page - 1) * $limit + 1;
        foreach ($data['result'] as $row) {
            $html_content = [];

            if ($this->button_bulk_action) {

                $html_content[] = "<input type='checkbox' class='checkbox' name='checkbox[]' value='".$row->{$tablePK}."'/>";
            }

            if ($this->show_numbering) {
                $html_content[] = $number.'. ';
                $number++;
            }

            foreach ($columns_table as $col) {
                if ($col['visible'] === false) {
                    continue;
                }

                $value = @$row->{$col['field']};
                $title = @$row->{$this->title_field};
                $label = $col['label'];

                if (isset($col['image'])) {
                    if ($value == '') {
                        $value = "<a  data-lightbox='roadtrip' rel='group_{{$table}}' title='$label: $title' href='".asset('vendor/crudbooster/avatar.jpg')."'><img width='40px' height='40px' src='".asset('vendor/crudbooster/avatar.jpg')."'/></a>";
                    } else {
                        $pic = (strpos($value, 'http://') !== false) ? $value : asset($value);
                        $value = "<a data-lightbox='roadtrip'  rel='group_{{$table}}' title='$label: $title' href='".$pic."'><img width='40px' height='40px' src='".$pic."'/></a>";
                    }
                }

                if (@$col['download']) {
                    $url = (strpos($value, 'http://') !== false) ? $value : asset($value).'?download=1';
                    if ($value) {
                        $value = "<a class='btn btn-xs btn-primary' href='$url' target='_blank' title='Download File'><i class='fa fa-download'></i> Download</a>";
                    } else {
                        $value = " - ";
                    }
                }

                if ($col['str_limit']) {
                    $value = trim(strip_tags($value));
                    $value = str_limit($value, $col['str_limit']);
                }

                if ($col['nl2br']) {
                    $value = nl2br($value);
                }

                if ($col['callback_php']) {
                    foreach ($row as $k => $v) {
                        $col['callback_php'] = str_replace("[".$k."]", $v, $col['callback_php']);
                    }
                    @eval("\$value = ".$col['callback_php'].";");
                }

                //New method for callback
                if (isset($col['callback'])) {
                    $value = call_user_func($col['callback'], $row);
                }

                $datavalue = @unserialize($value);
                if ($datavalue !== false) {
                    if ($datavalue) {
                        $prevalue = [];
                        foreach ($datavalue as $d) {
                            if ($d['label']) {
                                $prevalue[] = $d['label'];
                            }
                        }
                        if ($prevalue && count($prevalue)) {
                            $value = implode(", ", $prevalue);
                        }
                    }
                }

                $html_content[] = $value;
            } //end foreach columns_table

            if ($this->button_table_action):

                $button_action_style = $this->button_action_style;
                $html_content[] = "<div class='button_action' style='text-align:right'>".view('crudbooster::components.action', compact('addaction', 'row', 'button_action_style', 'parent_field'))->render()."</div>";

            endif;//button_table_action

            foreach ($html_content as $i => $v) {
                $this->hook_row_index($i, $v);
                $html_content[$i] = $v;
            }

            $html_contents[] = $html_content;
        } //end foreach data[result]

        $html_contents = ['html' => $html_contents, 'data' => $data['result']];

        $data['html_contents'] = $html_contents;

        //echo"<pre>";print_r($data);exit;

        return view("reports.product", $data);
    }

     public function postExportData()
    {
        ini_set('memory_limit', '1024M');
        set_time_limit(180);

        $this->limit = Request::input('limit');
        $this->index_return = true;
        $filetype = Request::input('fileformat');
        $filename = Request::input('filename');
        $papersize = Request::input('page_size');
        $paperorientation = Request::input('page_orientation');

        $response = $this->getIndex();
       		 if (CRUDBooster::myPrivilegeId() == 3) {
	         	$this->client_id = crudbooster::myId();
	         }else{
	         	$this->client_id = $this->custom_filter_selected;
	         }	

	    $client_data=DB::table('cms_users as client')
	   			->leftJoin('master_cities as city','client.master_cities_id','=','city.id')
		    	->where('client.id',$this->client_id)
		    	//->select('client.*','city.city')
		    	->get();    
        $response['client_data'] = $client_data;

       //echo "<pre>"; print_r($_REQUEST['filter_column']);exit;

        $product=DB::table('master_products')
		    	->where('id',$this->custom_filter_selected2)
		    	->select('name')
		    	->first();    
        $response['product_name'] = $product->name;

      
        $response['invoice_type'] = "Sales Register";
//echo "<pre>";print_r($_REQUEST['filter_column']['master_po.po_date']);exit;
      
       $response['fromDate'] = $_REQUEST['filter_column']['master_po.po_date']['value'][0];
       $response['toDate'] = $_REQUEST['filter_column']['master_po.po_date']['value'][1];
        if (Request::input('default_paper_size')) {
            DB::table('cms_settings')->where('name', 'default_paper_size')->update(['content' => $papersize]);
        }

        switch ($filetype) {
            case "pdf":
                $view = view('reports.product_xsl', $response)->render();
                $pdf = App::make('dompdf.wrapper');
                $pdf->loadHTML($view);
                $pdf->setPaper($papersize, $paperorientation);

                return $pdf->stream($filename.'.pdf');
                break;
            case 'xls':
                Excel::create($filename, function ($excel) use ($response) {
                	
                    $excel->setTitle($filename)->setCreator("crudbooster.com")->setCompany(CRUDBooster::getSetting('appname'));
                    $excel->sheet($filename, function ($sheet) use ($response) {
                        $sheet->setOrientation($paperorientation);
                       //$sheet->loadview('crudbooster::export', $response);
                       $sheet->loadview('reports.product_xsl', $response); 
                    });
                })->export('xls');
                break;
            case 'csv':
                Excel::create($filename, function ($excel) use ($response) {
                    $excel->setTitle($filename)->setCreator("crudbooster.com")->setCompany(CRUDBooster::getSetting('appname'));
                    $excel->sheet($filename, function ($sheet) use ($response) {
                        $sheet->setOrientation($paperorientation);
                        $sheet->loadview('reports.product', $response);
                    });
                })->export('csv');
                break;
        }
    }

}