<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

(defined('EXT')) OR define('EXT', '.php');

function get_slug()
{  
  $ci = &get_instance(); 
  $last = $ci->uri->total_segments();
  if($last > 0) {
    return $ci->uri->segment($last);
  }
  else {
    return 'home';
  }
}

 
function add_row($id, $parent, $li_attr, $label)
{
    $this->data[$parent][] = array('id' => $id, 'li_attr' => $li_attr, 'label' => $label);
}

 
function generate_list($ul_attr = '')
{
    return $this->ul(0, $ul_attr);
    $this->data = array();
}
 

function render_block ($blocks) {
    foreach($blocks as $key => $block) { 
         echo '<div class="block '.$block->id.'">'. $block->content .'</div>';
    }          
}

 

function is_username_available($username)
{
  $ci = &get_instance();
  $ci->db->select('1', FALSE);
  $ci->db->where('LOWER(username)=', strtolower($username));

  $query = $ci->db->get('users');
  return $query->num_rows() == 0;
}

 

function is_email_available($email)
{
  $ci = &get_instance();
  $ci->db->select('1', FALSE);
  $ci->db->where('LOWER(email)=', strtolower($email));
  $ci->db->or_where('LOWER(new_email)=', strtolower($email));

  $query = $ci->db->get('users');
  return $query->num_rows() == 0;
  }



function is_array_check(array $test_var)
{
    foreach ($test_var as $key => $el) {
        if (is_array($el)) {
            return true;
        }
    }
    return false;
}
 
 
function uri_segment($seg)
{
  $CI =& get_instance();
  return @$CI->uri->segment($seg);
}

 
function post_status($post)
{
  return $post->status;
}

 
function post_url($post)
{
 $type = $post->post_type;
 return base_url($post->slug);
}

 
function post_title($post)
{   
   return $post->title;
}

function post_body($post)
{   
   return $post->body;
}

 
function post_id($post)
{
 return $post->id;
}


function blocks($section, $page) 
{  
  $blocks = array();
  $pages = array();
  $slugs = array($page,'all');
  $ci = &get_instance();
  $ci->db->select('blocks.*, blocks_pages.page, blocks_pages.mode');
  $ci->db->from('blocks');
  $ci->db->join('blocks_pages','blocks_pages.block_id = blocks.block_id','INNER');
  $ci->db->where('section', $section);
  $ci->db->where_in('page', $slugs);
  $query = $ci->db->get();
  $block_list = $query->result();

  foreach($block_list as $block) {
    if($block->mode == 'show' || $block->page == 'all') {
        $blocks[] = $block;
    }

    if($block->mode == 'hide') {
      $pages[] = $block->page;
    }    
  } 

  $ci->db->select('blocks.*, blocks_pages.page, blocks_pages.mode');
  $ci->db->from('blocks');
  $ci->db->join('blocks_pages','blocks_pages.block_id = blocks.block_id','INNER');
  $ci->db->where('section', $section);
  $query = $ci->db->get();
  $block_list = $query->result();
  
  foreach($block_list as $block) {
    if($block->mode == 'hide' && !in_array(get_slug(), $pages)) {
      $added = false;
      foreach($blocks as $b) {
        if($b->block_id == $block->block_id) {
          $added = true;
        }
      }
      if(!$added) {
        $blocks[] = $block;
      }      
    } 
  }

 
 if(count($blocks) > 0) { 

    foreach($blocks as $key => $block) {

      $parts = $block->id;
      $part = explode('_', $parts);
      if($block->type == 'Module' && count($part) > 1 && !is_numeric($part[1])) 
      {
        $row = array();
        $arr = explode('_', $block->id);
        $row['content'] = $ci->load->view($arr[0].'/'.$block->id, '', TRUE);
        $row['id'] = block_id($block->name, $key);
        $row['format'] = 'module';
        $row['weight'] = $block->weight; 
        $data['blocks'][] = (object) $row;
      }
      
      if($block->type == 'Custom') 
      {
        $row = array();
        $custom_block = $ci->db->where('id', $block->id)->get('blocks_custom')->row();
        $row['content'] = ($custom_block->format=='php') ? eval($custom_block->code) : $custom_block->code;
        $row['id'] = block_id($block->name, $key);
        $row['format'] = $custom_block->format;
        $row['weight'] = $block->weight; 
        $data['blocks'][] = (object) $row; 
      }

      if($block->type == 'Module' && is_numeric($part[1])) { 
        $row = array();
        $module_block = $ci->db->where('param', $block->id)->get('blocks_modules')->row();  
        $content = modules::run(strtolower($module_block->module).'/'.strtolower($module_block->module).'_block', $part[1]);       
        $row['content'] = block_content($module_block, $content);       
        $row['id'] = block_id($block->name, $key);
        $row['format'] = 'module';
        $row['weight'] = $block->weight; 
        $data['blocks'][] = (object) $row; 
      }

    }  

    uasort($data['blocks'], function($a, $b) { return strcasecmp($a->weight, $b->weight); });   
    $ci->load->view(config_item('active_theme').'/blocks/'.$section, $data);
  }
  
}


function block_id($name, $id) 
{
    $slices = explode(' ', $name);
    return strtolower(implode('_', $slices).'_'.($id + 1));
}


function block_content($block, $content) 
{
  if(!empty($block->settings))
  {
    $settings = unserialize($block->settings);
    return ($settings['title'] == 'yes') ? '<h2>' . $block->name . '</h2>' . $content : $content;
  }
  
  return $content;
}
 

function list_pages ($published = TRUE)
{
  $CI =& get_instance();
  @$CI->load->model('Page');
  return @$CI->Page_m->get(NULL, FALSE, TRUE);
}


function url_encode($data)
{
    return base64_encode(serialize($data));
}

function url_decode($data)
{
    return unserialize(base64_decode($data));
}
 

function theme_assets ()
{
   return base_url().'themes/'.config_item('active_theme').'/assets/';
}

function active_theme ()
{
  return FCPATH.'themes/'.config_item('active_theme').'/';
}

function gateways ()
{
   
  $gateways = array(
    'allow_paypal' => (config_item('paypal_active') == 'TRUE' && config_item('paypal_email') != '') ? 'Yes' : 'No',
    'allow_2checkout' => (config_item('two_checkout_active') == 'TRUE' && config_item('2checkout_publishable_key') != '' && config_item('2checkout_private_key') != '') ? 'Yes' : 'No',
    'allow_stripe' => (config_item('stripe_active') == 'TRUE' && config_item('stripe_public_key') != '' && config_item('stripe_private_key') != '') ? 'Yes' : 'No',
    'allow_coinpayments' => (config_item('coinpayments_active') == 'TRUE' && config_item('coinpayments_public_key') != '' && config_item('coinpayments_private_key') != '') ? 'Yes' : 'No',
    'allow_bitcoin' => (config_item('bitcoin_active') == 'TRUE' && config_item('bitcoin_address') != '' && config_item('bitcoin_api_key') != '') ? 'Yes' : 'No',
    'allow_payfast' => (config_item('payfast_active') == 'TRUE' && config_item('payfast_merchant_id') != '' && config_item('payfast_merchant_key') != '') ? 'Yes' : 'No',
    'allow_mollie' => (config_item('mollie_active') == 'TRUE' && config_item('mollie_api_key') != '') ? 'Yes' : 'No',
    'allow_razorpay' => (config_item('razorpay_active') == 'TRUE' && config_item('razorpay_api_key') != '') ? 'Yes' : 'No',
    'allow_instamojo' => (config_item('instamojo_active') == 'TRUE' && config_item('instamojo_api_key') != '' && config_item('instamojo_oath_token') != '') ? 'Yes' : 'No',
    'allow_paystack' => (config_item('paystack_active') == 'TRUE' && config_item('paystack_secret_key') != '') ? 'Yes' : 'No'
  );

    return $gateways;
}




function create_password()
{
    $symbols = '!@+#%*?!#?-/:=_';
    $numbers = "0123456789";
    $lowercase = "abcdefghijklmnopqrstuvwxyz";
    $uppercase = "ABCDEFGHIJKLMNOPQRSTUVYWXYZ";
    $str = "";
    $count = strlen($numbers) - 1;
    for( $i = 0; $i < 4; $i++ ) 
    {
        $str .= $numbers[rand(0, $count)];
    }
    $count = strlen($lowercase) - 1;
    for( $i = 0; $i < 4; $i++ ) 
    {
        $str .= $lowercase[rand(0, $count)];
    }
    $count = strlen($symbols) - 1;
    for( $i = 0; $i < 3; $i++ ) 
    {
        $str .= $symbols[rand(0, $count)];
    }
    $count = strlen($uppercase) - 1;
    for( $i = 0; $i < 3; $i++ ) 
    {
        $str .= $uppercase[rand(0, $count)];
    }
    $password = "";
    for( $i = 0; $i < 12; $i++ ) 
    {
        $randomnum = rand(0, strlen($str) - 1);
        $password .= $str[$randomnum];
        $str = substr($str, 0, $randomnum) . substr($str, $randomnum + 1);
    }
    return $password;
}