<?PHP


///////Validator//////////////////////
class ValidatorObj
{
  public $variable_name;
  public $validator_string;
  public $error_string;
  public $condition;
}

class FM_CustomValidator 
{
   function DoValidate(&$formars,&$error_hash)
   {
      return true;
   }
}

class FM_FormValidator extends FM_Module
{
   private $validator_array;
   private $errors;
   private $show_all_errors_together;
   private $error_hash;
   private $custom_validators;
   private $file_uploader;
   private $database;
   
   public function __construct()
   {
      parent::__construct();
      $this->validator_array = array();
      $this->show_all_errors_together=true;
      $this->error_hash = array();
      $this->custom_validators=array();
   }
   
   function SetFileUploader(&$uploader)
   {
		$this->file_uploader = &$uploader;
		$uploads = $this->file_uploader->getUploadFields();
		foreach($this->validator_array as $val_obj)
		{
			if(false !== array_search($val_obj->variable_name,$uploads))
			{
				$command = '';
				$command_value = '';
				$this->parse_validator_string($val_obj->validator_string,
					$command,$command_value);
					
				$this->file_uploader->setFieldValidations($val_obj->variable_name,
						$command,$command_value,$val_obj->error_string);
			}
		}
   }
   function SetDatabase(&$db)
   {
     $this->database = &$db;
   }
   function AddCustomValidator(&$customv)
   {
      array_push_ref($this->custom_validators,$customv);
   }

   function addValidation($variable,$validator,$error,$condition="")
   {
      $validator_obj = new ValidatorObj();
      $validator_obj->variable_name = $variable;
      $validator_obj->validator_string = $validator;
      $validator_obj->error_string = $error;
      $validator_obj->condition = $condition;
      array_push($this->validator_array,$validator_obj);
   }
    
    function Process(&$continue)
    {
      $bResult = true;
      
      if(!$this->common_objs->security_monitor->Validate($this->formvars))
      {
         $this->error_handler->ShowError("Form Security Check: Access Denied",/*show form*/false);
         $continue = false;
         return false;  
      }      

      if(false == $this->ValidateForm($this->formvars))
      {
         $bResult = false;
      }

      if(count($this->custom_validators) > 0)
      {
         foreach( $this->custom_validators as $custom_val)
         {
            if(false == $custom_val->DoValidate($this->formvars,$this->error_hash))
            {
               $bResult = false;
            }
         }
      }

      if(false ===  $this->ext_module->DoValidate($this->formvars,$this->error_hash))
      {
          $bResult = false;
      }

      if(!$bResult)
      {
         $continue=false;
         $this->error_handler->ShowInputError($this->error_hash,$this->formname);
         return false;        
      }
      else
      {
         $continue=true;
         return true;
      }
    }

   function ValidateCurrentPage($form_variables)
   {
      if(!$this->common_objs->formpage_renderer->IsPageNumSet())
      {
         return true;
      }

      $cur_page_num = 
         $this->common_objs->formpage_renderer->GetCurrentPageNum();
      
      $bResult = $this->ValidatePage($cur_page_num,$form_variables);
      
      if(false ===  $this->ext_module->DoValidatePage($this->formvars,$this->error_hash,$cur_page_num))
      {
          $bResult = false;
      }
      
      if(!$bResult)
      {
         $this->logger->LogInfo("Page Validations $cur_page_num: Errors ".var_export($this->error_hash,TRUE));
         $this->error_handler->ShowInputError($this->error_hash,$this->formname);
      }
      else
      {
         $this->logger->LogInfo("Page Validations $cur_page_num: succeeded");
      }
      return $bResult;
   }
   
   function IsThisElementInCurrentPage($varname)
   {
        $cur_page_num = 
                 $this->common_objs->formpage_renderer->GetCurrentPageNum();

        $elmnt_page = 
                    $this->config->element_info->GetPageNum($varname);
        
        return ($cur_page_num == $elmnt_page) ? true:false;
   }
   function ValidateForm($form_variables)
   {
      $bret = true;

      $vcount = count($this->validator_array);
      $this->logger->LogInfo("Validating form data: number of validations: $vcount" );

      foreach($this->validator_array as $val_obj)
      {
         $page_num = 
            $this->config->element_info->GetPageNum($val_obj->variable_name);
            
         //See if the page is shown
         if(!$this->common_objs->formpage_renderer->TestPageCondition($page_num,$form_variables))
         {
            continue;
         }
         
         if(!$this->ValidateOneObject($val_obj,$form_variables))
         {
            $bret = false;
            if(false == $this->show_all_errors_together)
            {
               break;   
            }
         }
      }
      return $bret;
   }
   
   function ValidateOneObject($val_obj,&$form_variables)
   {
      $error_string="";
      $bret = $this->ValidateObject($val_obj,$form_variables,$error_string);
      if(!$bret)
      {
         $this->error_hash[$val_obj->variable_name] = $error_string;
      }
      return $bret;
   }
   
   
   function ValidatePage($page_num,$form_variables)
   {
      $bret = true;

      $vcount = count($this->validator_array);
      

      foreach($this->validator_array as $val_obj)
      {
         $elmnt_page = 
            $this->config->element_info->GetPageNum($val_obj->variable_name);
         
         if($elmnt_page != $page_num)
         {
            continue;
         }
         
         if(!$this->ValidateOneObject($val_obj,$form_variables))
         {
            $this->logger->LogInfo("Validating variable ".$val_obj->variable_name." returns false" );
            $bret = false;
            if(false == $this->show_all_errors_together)
            {
               break;   
            }
         }
      }
      return $bret;      
   }

   function getErrors()
   {
      return $this->errors;
   }
   function parse_validator_string($validator_string,&$command,&$command_value)
   {
      $matches='';
      preg_match('/^([\w]+)(\=\s*(.*?))?\s*$/', $validator_string, $matches);
      
      $command = $matches[1];
      $command_value = empty($matches[3]) ? '' : $matches[3];
   }
   
   function ValidateObject($validatorobj,$formvariables,&$error_string)
   {
      $bret = true;

        //validation condition
        if(isset($validatorobj->condition) &&
            strlen($validatorobj->condition)>0)
        {
            if(false == $this->ValidateCondition(
                $validatorobj->condition,$formvariables))
            {
                return true;
            }
        }

      /*$splitted = explode("=",$validatorobj->validator_string);
      $command = $splitted[0];
      $command_value = '';

      if(isset($splitted[1]) && strlen($splitted[1])>0)
      {
         $command_value = $splitted[1];
      }*/
	  
	  $command = '';
      $command_value = '';
	  $this->parse_validator_string($validatorobj->validator_string,$command,$command_value);

      $default_error_message="";
      
      $input_value ="";

      if(isset($formvariables[$validatorobj->variable_name]))
      {
       $input_value = $formvariables[$validatorobj->variable_name];
      }

      $extra_info="";

      $bret = $this->ValidateCommand($command,$command_value,$input_value,
                           $default_error_message,
                           $validatorobj->variable_name,
                           $extra_info,
                           $formvariables);

      
      if(false == $bret)
      {
         if(isset($validatorobj->error_string) &&
            strlen($validatorobj->error_string)>0)
         {
            $error_string = $validatorobj->error_string;
         }
         else
         {
            $error_string = $default_error_message;
         }
         if(strlen($extra_info)>0)
         {
            $error_string .= "\n".$extra_info;
         }

      }//if
      return $bret;
   }
      
   function ValidateCondition($condn,$formvariables)
   {
        return sfm_validate_multi_conditions($condn,$formvariables);
   }

   function validate_req($input_value, &$default_error_message,$variable_name)
   {
       $bret = true;
         if(!isset($input_value))
        {
            $bret=false;
        }
        else
        {
            $input_value = trim($input_value);

            if(strlen($input_value) <=0)
          {
             $bret=false;
          }

            $type = $this->config->element_info->GetType($variable_name);
            if("datepicker" == $type)
            {
                $date_obj = new FM_DateObj($this->formvars,$this->config,$this->logger);
                if(!$date_obj->GetDateFieldInStdForm($variable_name))
                {
                    $bret=false;
                }
            }
        }   
        if(!$bret)
        {
            $default_error_message = sprintf("Please enter the value for %s",$variable_name);
        }

       return $bret; 
   }

   function validate_maxlen($input_value,$max_len,$variable_name,&$extra_info,&$default_error_message)
   {
      $bret = true;
      if(isset($input_value) )
      {
         $input_length = strlen($input_value);
         if($input_length > $max_len)
         {
            $bret=false;
            $default_error_message = sprintf("Maximum length exceeded for %s.",$variable_name);
         }
      }
      return $bret;
   }

   function validate_minlen($input_value,$min_len,$variable_name,&$extra_info,&$default_error_message)
   {
      $bret = true;
      if(isset($input_value) )
      {
         $input_length = strlen($input_value);
         if($input_length < $min_len)
         {
            $bret=false;
            //$extra_info = sprintf(E_VAL_MINLEN_EXTRA_INFO,$min_len,$input_length);
            $default_error_message = sprintf("Please enter input with length more than %d for %s",$min_len,$variable_name);
         }
      }
      return $bret;
   }

   function test_datatype($input_value,$reg_exp)
   {
      if(preg_match($reg_exp,$input_value))
      {
         return true;
      }
      return false;
   }
    /**
    Source: http://www.linuxjournal.com/article/9585?page=0,3
    */
    function validate_email($email)
    {
       $isValid = true;
       $atIndex = strrpos($email, "@");
       if (is_bool($atIndex) && !$atIndex)
       {
          $isValid = false;
       }
       else
       {
          $domain = substr($email, $atIndex+1);
          $local = substr($email, 0, $atIndex);
          $localLen = strlen($local);
          $domainLen = strlen($domain);
          if ($localLen < 1 || $localLen > 64)
          {
             // local part length exceeded
             $isValid = false;
          }
          else if ($domainLen < 1 || $domainLen > 255)
          {
             // domain part length exceeded
             $isValid = false;
          }
          else if ($local[0] == '.' || $local[$localLen-1] == '.')
          {
             // local part starts or ends with '.'
             $isValid = false;
          }
          else if (preg_match('/\\.\\./', $local))
          {
             // local part has two consecutive dots
             $isValid = false;
          }
          else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
          {
             // character not valid in domain part
             $isValid = false;
          }
          else if (preg_match('/\\.\\./', $domain))
          {
             // domain part has two consecutive dots
             $isValid = false;
          }
          else if(!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/',
                     str_replace("\\\\","",$local)))
          {
             // character not valid in local part unless 
             // local part is quoted
             if (!preg_match('/^"(\\\\"|[^"])+"$/',
                 str_replace("\\\\","",$local)))
             {
                $isValid = false;
             }
          }
       }
       return $isValid;
    }
    
    //function validate_email($email) 
    //{
    //  return preg_match("/^[_\.0-9a-zA-Z-]+@([0-9a-zA-Z][0-9a-zA-Z-]+\.)+[a-zA-Z]{2,6}$/i", $email);
      //return preg_match('/^((([a-z]|\d|[!#\$%&\'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+(\.([a-z]|\d|[!#\$%&\'\*\+\-\/=\?\^_`{\|}~]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])+)*)|((\x22)((((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(([\x01-\x08\x0b\x0c\x0e-\x1f\x7f]|\x21|[\x23-\x5b]|[\x5d-\x7e]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(\\([\x01-\x09\x0b\x0c\x0d-\x7f]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF]))))*(((\x20|\x09)*(\x0d\x0a))?(\x20|\x09)+)?(\x22)))@((([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|\d|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))\.)+(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])|(([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])([a-z]|\d|-|\.|_|~|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])*([a-z]|[\u00A0-\uD7FF\uF900-\uFDCF\uFDF0-\uFFEF])))$/i',$email);
    //}

    function make_number($input_value)
    {
        return str_replace(",","",$input_value);
    }

   function validate_for_numeric_input($input_value,&$validation_success,&$extra_info)
   {
      
      $more_validations=true;
      $validation_success = true;
      if(strlen($input_value)>0)
      {
         
         if(false == is_numeric($input_value))
         {
            $extra_info = "Numeric value required.";
            $validation_success = false;
            $more_validations=false;
         }
      }
      else
      {
         $more_validations=false;
      }
      return $more_validations;
   }

   function validate_lessthan($command_value,$input_value,
                $variable_name,&$default_error_message,&$extra_info )
   {
      $bret = true;
        $input_value = $this->make_number($input_value);
      if(false == $this->validate_for_numeric_input($input_value,
                                    $bret,$extra_info))
      {
         return $bret;
      }
      if($bret)
      {
         $lessthan = doubleval($command_value);
         $float_inputval = doubleval($input_value);
         if($float_inputval >= $lessthan)
         {
            $default_error_message = sprintf("Enter a value less than %f for %s",
                              $lessthan,
                              $variable_name);
            $bret = false;
         }//if
      }
      return $bret ;
   }
   function validate_greaterthan($command_value,$input_value,$variable_name,&$default_error_message,&$extra_info )
   {
      $bret = true;
        $input_value = $this->make_number($input_value);
      if(false == $this->validate_for_numeric_input($input_value,$bret,$extra_info))
      {
         return $bret;
      }
      if($bret)
      {
         $greaterthan = doubleval($command_value);
         $float_inputval = doubleval($input_value);
         if($float_inputval <= $greaterthan)
         {
            $default_error_message = sprintf("Enter a value greater than %f for %s",
                              $greaterthan,
                              $variable_name);
            $bret = false;
         }//if
      }
      return $bret ;
   }

    function validate_select($input_value,$command_value,&$default_error_message,$variable_name)
    {
       $bret=false;
      if(is_array($input_value))
      {
         foreach($input_value as $value)
         {
            if($value == $command_value)
            {
               $bret=true;
               break;
            }
         }
      }
      else
      {
         if($command_value == $input_value)
         {
            $bret=true;
         }
      }
        if(false == $bret)
        {
            $default_error_message = sprintf("You should select option %s for %s",
                                            $command_value,$variable_name);
        }
       return $bret;
    }

   function validate_dontselect($input_value,$command_value,&$default_error_message,$variable_name)
   {
      $bret=true;
      if(is_array($input_value))
      {
         foreach($input_value as $value)
         {
            if($value == $command_value)
            {
               $bret=false;
               $default_error_message = sprintf("Wrong option selected for %s",$variable_name);
               break;
            }
         }
      }
      else
      {
         if($command_value == $input_value)
         {
            $bret=false;
            $default_error_message = sprintf("Wrong option selected for %s",$variable_name);
         }
      }
     return $bret;
   }

   function ValidateComparison($input_value,$formvariables,
         $command_value,&$extra_info,&$default_error_message,$variable_name,$command)
   {
      $bret = true;
      if(isset($input_value) &&
      isset($formvariables[$command_value]))
      {
            $input_value = $this->make_number($input_value);
            $valueOther = $this->make_number($formvariables[$command_value]);

         if(true == $this->validate_for_numeric_input($input_value,$bret,$extra_info) &&
            true == $this->validate_for_numeric_input($valueOther,$bret,$extra_info))
         {
            $valueThis  = doubleval($input_value);
            $valueOther = doubleval($valueOther);
            switch($command)
            {
              case "ltelmnt":
                        {
                           if($valueThis >= $valueOther)
                           {
                              $bret = false;
                              $default_error_message = sprintf("Value of %s should be less than that of %s",$variable_name,$command_value);
                           }
                           break;
                        }
              case "leelmnt":
                        {
                           if($valueThis > $valueOther)
                           {
                              $bret = false;
                              $default_error_message = sprintf("Value of %s should be less than or equal to that of %s",$variable_name,$command_value);
                           }
                           break;
                        }
              case "gtelmnt":
                        {
                           if($valueThis <= $valueOther)
                           {
                              $bret = false;
                              $default_error_message = sprintf("Value of %s should be greater that of %s",$variable_name,$command_value);
                           }
                           break;
                        }
              case "geelmnt":
                        {
                           if($valueThis < $valueOther)
                           {
                              $bret = false;
                              $default_error_message = sprintf("Value of %s should be greater or equal to that of %s",$variable_name,$command_value);
                           }
                           break;                           
                        }

              
            }//switch
         }
      }
      return $bret;
   }

   function ValidateCommand($command,$command_value,$input_value,&$default_error_message,$variable_name,&$extra_info,$formvariables)
   {
      $bret=true;
      switch($command)
      {
         case 'required':
                  {
                     $bret = $this->validate_req($input_value, $default_error_message,$variable_name);
                     break;
                  }

         case 'maxlen':
                  {
                     $max_len = intval($command_value);
                     $bret = $this->validate_maxlen($input_value,$max_len,$variable_name,
                                    $extra_info,$default_error_message);
                     break;
                  }

         case 'minlen':
                  {
                     $min_len = intval($command_value);
                     $bret = $this->validate_minlen($input_value,$min_len,$variable_name,
                                 $extra_info,$default_error_message);
                     break;
                  }

         case 'alnum':
                  {
                     $bret= $this->test_datatype($input_value,"/^[A-Za-z0-9]*$/");
                     if(false == $bret)
                     {
                        $default_error_message = sprintf("Please provide an alpha-numeric input for %s",$variable_name);
                     }
                     break;
                  }

         case 'alnum_s':
                  {
                     $bret= $this->test_datatype($input_value,"/^[A-Za-z0-9\s]*$/");
                     if(false == $bret)
                     {
                        $default_error_message = sprintf("Please provide an alpha-numeric input for %s",$variable_name);
                     }
                     break;
                  }

         case 'num':
         case 'numeric':
                  {
                     if(isset($input_value) && strlen($input_value)>0)
                     {
                        if(!preg_match("/^[\-\+]?[\d\,]*\.?[\d]*$/",$input_value))
                        {
                           $bret=false;
                           $default_error_message = sprintf("Please provide numeric input for %s",$variable_name);
                        }
                     }
                     break;
                  }

         case 'alpha': 
                  {
                     $bret= $this->test_datatype($input_value,"/^[A-Za-z]*$/");
                     if(false == $bret)
                     {
                        $default_error_message = sprintf("Please provide alphabetic input for %s",$variable_name);
                     }
                     break;
                  }
         case 'alpha_s':
                  {
                     $bret= $this->test_datatype($input_value,"/^[A-Za-z\s]*$/");
                     if(false == $bret)
                     {
                        $default_error_message = sprintf("Please provide alphabetic input for %s",$variable_name);
                     }
                     break;
                  }
         case 'email':
                  {
                     if(isset($input_value) && strlen($input_value)>0)
                     {
                        $bret= $this->validate_email($input_value);
                        if(false == $bret)
                        {
                           $default_error_message = "Please provide a valid email address";
                        }
                     }
                     break;
                  }
         case "lt": 
         case "lessthan": 
                  {
                     $bret = $this->validate_lessthan($command_value,
                                       $input_value,
                                       $variable_name,
                                       $default_error_message,
                                       $extra_info );
                     break;
                  }
         case "gt": 
         case "greaterthan": 
                  {
                     $bret = $this->validate_greaterthan($command_value,
                                       $input_value,
                                       $variable_name,
                                       $default_error_message,
                                       $extra_info );
                     break;
                  }

         case "regexp":
                  {
                     if(isset($input_value) && strlen($input_value)>0)
                     {
                        if(!preg_match("$command_value",$input_value))
                        {
                           $bret=false;
                           $default_error_message = sprintf("Please provide a valid input for %s",$variable_name);
                        }
                     }
                     break;
                  }
        case "dontselect": 
        case "dontselectchk":
        case "dontselectradio":
                  {
                     $bret = $this->validate_dontselect($input_value,
                                                $command_value,
                                                $default_error_message,
                                                $variable_name);
                      break;
                  }//case

          case "shouldselchk":
          case "selectradio":
                      {
                            $bret = $this->validate_select($input_value,
                            $command_value,
                            $default_error_message,
                            $variable_name);
                            break;
                      }//case

        case "selmin":
                  {
                     $min_count = intval($command_value);
                            $bret = false;

                     if(is_array($input_value))
                            {
                         $bret = (count($input_value) >= $min_count )?true:false;
                            }
                     else
                     {
                                if(isset($input_value) && !empty($input_value) && $min_count == 1)
                                {
                                    $bret = true;
                                }
                     }
                            if(!$bret)
                            {
                        $default_error_message = sprintf("Please select minimum %d options for %s",
                                            $min_count,$variable_name);

                            }

                     break;
                  }//case
        case "selmax":
                  {
                     $max_count = intval($command_value);

                     if(isset($input_value))
                            {
                         $bret = (count($input_value) > $max_count )?false:true;
                            }

                     break;
                  }//case
       case "selone":
                  {
                     if(false == isset($input_value)||
                        strlen($input_value)<=0)
                     {
                        $bret= false;
                        $default_error_message = sprintf("Please select an option for %s",$variable_name);
                     }
                     break;
                  }
       case "eqelmnt":
                  {
                     if(isset($formvariables[$command_value]) &&
                        strcmp($input_value,$formvariables[$command_value])==0 )
                     {
                        $bret=true;
                     }
                     else
                     {
                        $bret= false;
                        $default_error_message = sprintf("Value of %s should be same as that of %s",$variable_name,$command_value);
                     }
                  break;
                  }
        case "ltelmnt":
        case "leelmnt":
        case "gtelmnt":
        case "geelmnt":
                  {
                     $bret= $this->ValidateComparison($input_value,$formvariables,
                              $command_value,$extra_info,$default_error_message,
                              $variable_name,
                              $command);
                  break;
                  }
        case "neelmnt":
                  {
                    if(!empty($input_value))
                    {
                         if(strcmp($input_value,$formvariables[$command_value]) !=0 )
                         {
                            $bret=true;
                         }
                         else
                         {
                            $bret= false;
                            $default_error_message = sprintf("Value of %s should not be same as that of %s",$variable_name,$command_value);
                         }
                     }
                     break;
                  }
          case "req_file":
          case "max_filesize":
          case "file_extn":
                  {
                            $bret= $this->ValidateFileUpload($variable_name,
                                    $command,
                                    $command_value,
                                    $default_error_message);
                            break;
                        }

          case "after_date":
                        {
                            $bret = $this->ValidateDate($command_value,$variable_name,false,$formvariables);
                        }
                        break;

          case "before_date":
                        {
                            $bret = $this->ValidateDate($command_value,$variable_name,true,$formvariables);
                        }
                        break;
          case "unique":
                        {
                            //$input_value, $default_error_message,$variable_name
                            if($this->database)
                            {
                                $bret = $this->database->IsFieldUnique($variable_name,$input_value);
                                if(!$bret)
                                {
                                    $default_error_message = "This value is already submitted";
                                }
                            }
                        }
                        break;
      }//switch
      return $bret;
   }//validdate command

    function ValidateDate($command_value,$variable_name,$before,$formvariables)
    {
        $bret = true;
        $date_obj = new FM_DateObj($formvariables,$this->config,$this->logger);

        $date_other = $date_obj->GetOtherDate($command_value);

        $date_this  = $date_obj->GetDateFieldInStdForm($variable_name);

        if(empty($formvariables[$variable_name]))
        {
            $bret=true;
        }
        else
        if(!$date_other || !$date_this)
        {
            $this->logger->LogError("Invalid date received. ".$formvariables[$variable_name]);
            $bret=false;
        }
        else
        {
            $bret = $before ? strcmp($date_this,$date_other) < 0: strcmp($date_this,$date_other)>0;
        } 
        if(!$bret)
        {
            $this->logger->LogError("$variable_name: Date validation failed");
        }
        return $bret;
    }
    
    function ValidateFileUpload($variable_name,$command,$command_value,&$default_error_message)
    {
        $bret=true;
        
        if($command != 'req_file' && !$this->IsThisElementInCurrentPage($variable_name))
        {
            return true;
        }
            
        
		$bret = $this->file_uploader->ValidateFileUpload($variable_name,$command,
							$command_value,$default_error_message,/*form_submitted*/true);
        return $bret;
    }//function
}//FM_FormValidator


class FM_DBUtil
{
    private $connection;
    public $fields;
    
    private $error_handler;
    private $logger;
    private $config;
	private $logged_in;
    
    public function __construct()
    {
        $this->fields = array();
		$this->logged_in = false;
    }    

    function Init(&$config,&$logger,&$error_handler)
    {
        $this->error_handler = &$error_handler;
        $this->logger = &$logger;
        $this->config = &$config;
    }
    
    function AddField($fieldname,$fieldtype,$dispname='')
    {
        array_push($this->fields,array('name'=>$fieldname,'type'=>$fieldtype,'dispname'=>$dispname));
    }    
    function GetFieldDetails($fieldname)
    {//TODO: can be optimized using name as key
        foreach($this->fields as $formfield)
        {
            if($formfield['name'] == $fieldname)
            {
                return $formfield;
            }
        }
        return null;
    }
    
    function GetFields()
    {
        return $this->fields;
    }
    function HandleError($error_str,$extra='')
    {
        $this->error_handler->HandleConfigError($error_str,$this->GetError()." $extra");
    }
    
    function Login()
    {
		if(true === $this->logged_in)
		{
			return true;
		}
		
        if( $this->config->passwords_encrypted )
        {
            $pwd = sfm_crypt_decrypt($this->config->fmdb_pwd,$this->config->encr_key);
        }
        else
        {
            $pwd = $this->config->fmdb_pwd;
        }

        $this->connection = mysqli_connect($this->config->fmdb_host,$this->config->fmdb_username,$pwd, $this->config->fmdb_database);

        if(!$this->connection)
        {   
            $this->error_handler->HandleConfigError("Database Login failed! \nPlease make sure that the DB login credentials provided are correct \n".
                mysqli_error($this->connection));
            return false;
        }
        
        if(!mysqli_query($this->connection, "SET NAMES 'UTF8'"))
        {
            $this->HandleError('Error setting utf8 encoding');
            return false;
        }
		
		if(!empty($this->config->default_timezone) && $this->config->default_timezone != 'default')
		{
			if(!mysqli_query($this->connection, "SET SESSION time_zone = '".$this->config->default_timezone."'"))
			{
				//$this->logger->LogError('Error setting default time zone in DB');
				$this->HandleError('Error setting TimeZone. Your Mysql server does not have timezone database.');
				return false;
			}
		}		
		$this->logged_in = true;
		
        return true;
    }
    
    function GetConnection()
    {
        return $this->connection;
    }
    
    function Close()
    {
        mysqli_close($this->connection);
    }

    function GetError()
    {
        return mysqli_error($this->connection);
    }

    function CreateTable($tablename,$fields)
    {
        $qry = "Create Table $tablename ".
                "(ID INT AUTO_INCREMENT PRIMARY KEY,";

        foreach($fields as $formfield)
        {
            $qry .= " ".$formfield['name']." ".$formfield['type'].",";
        }
        $qry = trim($qry,',');

        $qry .=") DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;";
    
        if(!mysqli_query($this->connection,$qry))
        {
            $this->HandleError('Error creating the table ',"\nquery was\n $qry");
            return false;
        }
        return true;
                
    }

    function AlterTable($tablename, $fieldstoadd,$changedfields)
    {
        $qry="ALTER TABLE $tablename ";
        
        foreach($fieldstoadd as $field)
        {
            $qry .= " ADD COLUMN ".$field['name']." ".$field['type'].",\n";
        }
        
        foreach($changedfields as $field)
        {
            $qry .= " CHANGE COLUMN ".$field['name']." ".$field['name']." ".$field['type'].",\n";
        }

        $qry = trim($qry,",\n");

        $this->logger->LogInfo("Altering table $tablename query is: $qry ");
        if(!mysqli_query($this->connection,$qry))
        {
            $this->HandleError('Error altering the table',"\nquery was\n $qry");
            return false;
        }

        return true;

    }
    
    function EscapeValue($str,$type)
    {
        if( function_exists( "mysqli_real_escape_string" ) )
        {
              $ret_str = mysqli_real_escape_string($this->connection, $str );
        }
        else
        {
              $ret_str = addslashes( $str );
        }

        $type = strtolower($type);
        if(strpos($type,'varchar') !== FALSE ||
           strpos($type,'blob') !== FALSE ||
           strpos($type,'text') !== FALSE
           )
        {
            
            $ret_str = "'".$ret_str."'";
        }  
        elseif(strpos($type,'date') !== FALSE)
        {
            $ret_str = "'".$str."'";
        }  
        else
        {//numeric types
            $strTmp = trim($str);
            if(!isset($strTmp) || $strTmp === '')
            {
                $ret_str='NULL';
            }
        }
        return $ret_str;
    }    

    function Ensuretable($tablename, $fields=null)
    {
        if(null == $fields)
        {
            $fields = $this->fields;
        }
        
        $result = @mysqli_query($this->connection, "SHOW COLUMNS FROM $tablename");   
        if(!$result || mysqli_num_rows($result) <= 0)
        {
            return $this->CreateTable($tablename, $fields);
        }
        
        $non_existantfields = array();
        $changedfields = array();

        foreach($fields as $formfield)
        {
            $field_found=false;
            $field_changed=false;
            while ($row = mysqli_fetch_assoc($result)) 
            {
                if(strcasecmp($row['Field'],$formfield['name'])==0)
                {
                    $field_found = true;
                    if(strcasecmp($row['Type'],$formfield['type']) !=0)
                    {
                        $field_changed = true;
                    }
                    break;
                } 
            }

            if(!$field_found)
            {
                array_push($non_existantfields,$formfield);
            }
            elseif($field_changed)
            {
                array_push($changedfields,$formfield);
            }
            mysqli_data_seek($result,0);
        }

        if(!empty($non_existantfields) || !empty($changedfields))
        {
            $this->logger->LogInfo("Need to alter table; fields have changed");
            return $this->AlterTable($tablename,$non_existantfields,$changedfields);
        }
        
        return true;
    } 

    function TruncateTable($tablename)
    {
        if(!mysqli_query($this->connection, "Truncate table $tablename"))
        {
            return false;
        }
        return true;
    }
    
    function IsRowExisting($tablename,$row_hash)
    {
        $field_list='';
        
        foreach($row_hash as $field => $val)
        {
            $field_list .= "$field='";
            $field_list .= mysqli_real_escape_string($this->connection, $val);
            $field_list .= "' and ";
        }
        $field_list = rtrim($field_list,'and ');
        $qry ="SELECT Count(*) FROM $tablename WHERE $field_list";
        
        $count = $this->GetSingleValue($qry);
        if(false === $count)
        {
            return false;
        }
        return ($count > 0)?true:false;
    }    
    
    function Insert($tablename,$row_hash)
    {
        $field_list='';
        $value_list='';
        foreach($row_hash as $field => $val)
        {
            $field_list .= $field.',';
            $val = mysqli_real_escape_string($this->connection, $val);
            $value_list .= "'$val',";
        }
        $field_list = rtrim($field_list,',');
        $value_list = rtrim($value_list,',');
        $qry = "INSERT INTO $tablename ($field_list) VALUES ($value_list)";
        
        $this->logger->LogInfo("Insert Query: $qry");
        
        if(!mysqli_query($this->connection, $qry))
        {
            $this->HandleError("Error inserting data to the table $qry \n".
                mysqli_error($this->connection));
            return false;
        }
        $id = mysqli_insert_id($this->connection); 
        return $id;
    }
    
    function DeleteFromTable($table,$where)
    {
        $result = mysqli_query($this->connection, "DELETE FROM $table WHERE $where");
        if(!$result)
        {
            return false;
        }
        return true;
    }
    
    function UpdateTable($table,$values,$where)
    {
        $result = mysqli_query($this->connection, "UPDATE $table Set $values WHERE $where");
        
        if(!$result)
        {
            return false;
        }
        return true;    
    }
    
    function IsTableExisting($tablename)
    {
        $result = mysqli_query($this->connection, "SHOW COLUMNS FROM $tablename");   
        if(!$result || mysqli_num_rows($result) <= 0)
        {
            return false;
        }
        return true;
    }

    function GetCount($tablename)
    {
        $qry = "Select Count(*) From $tablename";
        return $this->GetSingleValue($qry);
    }
    
    function GetSingleValue($qry)
    {
        $result = mysqli_query($this->connection, $qry);
        if(!$result || mysqli_num_rows($result) <= 0)
        {
            return false;
        }
        $row = mysqli_fetch_row($result);
        return $row[0];
    }
    
    function GetRecords($tablename,$fields,$where,$sidx,$sord,$start,$limit)
    {
        $where_clause='';
        if(!empty($where))
        {
            $where_clause = "Where $where";
        }
        $qry  = "Select $fields From $tablename $where_clause Order By $sidx $sord Limit $start,$limit";

        $rows = array();
        
        if(false === $this->RunQuery($qry,$rows))
        {
            return false;
        }
        return $rows;
    }
    
    function ReadData($tablename,&$rows,$where='',$fields=false)
    {
        $strfields='*';
        if(false !== $fields && !empty($fields))
        {
            $strfields = implode(',',$fields);
        }
        
        $qry = "Select $strfields From $tablename";
        if(!empty($where))
        {
            $qry .= " Where $where";
        }
        $qry .= " Order by ID asc";
        
        return $this->RunQuery($qry,$rows);
    }
    
    function RunQuery($qry,&$rows)
    {
        $result = mysqli_query($this->connection, $qry);
        
        if(!$result)
        {
            $this->HandleError("Error running query $qry \n"
                .mysqli_error($this->connection));
            return false;
        }
        $rows = array();
        
        while($rec = mysqli_fetch_assoc($result)) 
        {
            $rows[] = $rec;
        }
        
        return true;
    }
    

}

class FM_SimpleDB extends FM_Module
{
    private $dbutil;
    private $connection;
    private $file_uploader;
    private $uniquefields;
	
      
    public function __construct($tablename)
    {
        parent::__construct();
        $this->dbutil = new FM_DBUtil();
        $this->connection=null;
        $this->tablename = $tablename;
        $this->uniquefields = array();
    }
    function GetTableName()
    {
        return $this->tablename;
    }
    function AddUniqueFields()
    {
        $args = func_get_args();
        $this->uniquefields = array_merge($this->uniquefields,$args);
    }
    function OnInit()
    {
        $this->dbutil->Init($this->config,$this->logger,$this->error_handler);
    }
    
    function SetFileUploader(&$file_uploader)
    {
        $this->file_uploader = &$file_uploader;
    }

    function AddField($fieldname,$fieldtype,$dispname='')
    {
        $this->dbutil->AddField($fieldname,$fieldtype,$dispname);
    }
    
    function HandleError($error_str,$extra='')
    {
        $this->error_handler->HandleConfigError($error_str,$this->GetError()." $extra");
    }
    
    function Login()
    {
        $ret = $this->dbutil->Login();
        if($ret)
        {
            $this->connection = $this->dbutil->GetConnection();
        }
        return $ret;
    }

    function Close()
    {
        mysqli_close($this->connection);
    }

    function GetError()
    {
        return mysqli_error($this->connection);
    }

    function Ensuretable()
    {
        return $this->dbutil->Ensuretable($this->tablename);
    }
    
    function GetFieldValue($var_name,$field_type)
    {
        $field_value ='';
        
        $field_type = strtolower($field_type);

        if($this->config->element_info->GetType($var_name) == "datepicker" &&
          ($field_type == "datetime" || $field_type == "date"))
        {
            $date_obj = new FM_DateObj($this->formvars,$this->config,$this->logger);
            $date_value  = $date_obj->GetDateFieldInStdForm($var_name);
            $this->logger->LogInfo("Saving to DB; date in std form: $date_value");
            
            $field_value = $this->dbutil->EscapeValue($date_value,$field_type);
        }
        elseif(($this->config->submission_time_var == $var_name||
            $this->config->submission_date_var == $var_name) && 
        ($field_type == "datetime" || $field_type == "date"))
        {
            $field_value = 'NOW()';
        }
        else
        {
            $field_value_x = $this->common_objs->formvar_mx->GetFieldValueAsString($var_name,/*$use_disp_var*/false);
            $field_value = $this->dbutil->EscapeValue($field_value_x,$field_type);
        }
        return $field_value;
    }
    
    

    function InsertDataInTable()
    {
        $qry ="INSERT INTO $this->tablename (";

        $values ="";
        foreach($this->dbutil->fields as $formfield)
        {
            $qry .= $formfield['name'].",";

            $value = $this->GetFieldValue($formfield['name'],$formfield['type']);
            $values .= "$value,";
        }        
        $qry = trim($qry,",");
        $values = trim($values,",");

        $qry .=") VALUES (";
        $qry .= $values;
        $qry .= ");";


        if(!mysqli_query($this->connection, $qry))
        {
            $this->HandleError('Error inserting data to the table',"\nquery:$qry");
            return false;
        }
        return true;
        
    }

    function Install(&$continue)
    {
        if(!$this->Login())
        {
            $continue = false;
            return false;
        }

        if(!$this->Ensuretable())
        {
            $continue = false;
            return false;
        }
        return true;
    }
    
    function DoAppCommand($cmd,$val,&$app_command_obj)
    {
        $ret=false;
        switch($cmd)
        {
			case 'db_get_rec_count':
			{
               $this->GetRecCount($app_command_obj->response_sender);
               $ret=true;
			}
			break;

			case 'db_get_recs':
			{
               $this->GetDBRecs($app_command_obj->response_sender,$val);
               $ret=true;
			}
			break;
        }
        return $ret;
    }
    
    //ajax commands from the admin page
    function AfterVariablessInitialized()
    {
        
        if(!empty($_GET['sfm_adminpage']))
		{
			if('recs' == $_GET['sfm_adminpage'])
			{
				if(!empty($_GET['getfields']))
				{
					$this->GetFieldsJSON();
				}
				else if(!empty($_GET['getrec']))
				{
					$this->GetSingleRecJSON($_GET['getrec']);
				}
				else if(!empty($_GET['sfm_save_grid_opts']))
				{
					 $this->SaveGridOptions();
				}
				else if(!empty($_GET['printrec']))
				{
					$this->ShowPrintablePage($_GET['printrec']);
				}
				else
				{   
					$this->GetRecordsJSON();
				}
				return false;//handled
			}
			elseif('db-csv' == $_GET['sfm_adminpage'])
			{
				$attachments = false;
				if(!empty($_POST['attachments']))
				{
					if($_POST['attachments'] == 'yes')
					{
						$attachments = true;
					}
				}
				$this->get_csv_download($attachments);
				return false;//handled
			}
		}
        elseif(!empty($_GET['sfm_check_unique']))
		{
            $uniquefield = $this->getUniqueFieldName();
            if(false === $uniquefield || empty($_GET[$uniquefield]))
            {
                echo 'success';
                return false;//handled
            }
            
            $uniquevalue = $_GET[$uniquefield];
            
            if(true === $this->IsFieldUnique($uniquefield,$uniquevalue))
            {
                echo 'success';
            }
            else
            {
                echo 'msg_failed';
            }
            return false;//handled
        }
        return true;
    }
    
    function Process(&$continue)
    {
        if(!$this->Login())
        {
            $continue = false;
            return false;
        }
        if(!$this->Ensuretable())
        {
            $continue = false;
            return false;
        }
        
        if(NULL != $this->file_uploader)
        {
            $this->file_uploader->SaveUploadedFile();
        }

        if(!$this->InsertDataInTable())
        {
            $continue = false;
            return false;
        }
        
        return true;
    }
    
    function ValidateInstallation(&$app_command_obj)
    {
        if(false === $app_command_obj->TestDBLogin())
        {
            return false;
        }
        $continue=false;
        //make sure Table is present and all fields are installed
        if(false === $this->Install($continue))
        {
            $this->logger->LogInfo("SimpleDB ValidateInstallation : Install returns false");
        }
        return true;
    }
	
	function get_csv_download($attachments)
	{
		if(!$this->Login())
        {
            $error = 'Loging in to the Database failed';
			$this->logger->LogError($error);
			echo $error;
            return;
        }
		$rec_count = $this->dbutil->GetSingleValue("SELECT Count(*) from $this->tablename");
		
		if(false === $rec_count || $rec_count <= 0)
		{
			$error = 'No records in the table';
			$this->logger->LogError($error);
			echo $error;
            return;
		}
		
		header('Content-type: application/x-tar');
		$downloadname = $this->formname.'-db-'.date("Y-m-d").'.tar.gz';
		header('Content-disposition: attachment;filename='.$downloadname);
		
		$limit = 10000;
		for($r=0; $r < $rec_count ; $r += $limit)
		{
			$file_response = new FM_Response($this->config,$this->logger);
			if(false === $this->GetRecs($file_response,$r,$limit))
			{
				$this->logger->LogError("Error while exporting records");
				break;
			}
			$filename = $this->formname;
			
			if($r>0){ $filename .='-'.$r;}
			$filename .= '.csv';
			
			$file = "\xEF\xBB\xBF".$file_response->getResponseStr();//utf-8 BOM for MS Excel
			echo sfm_targz($filename,$file);
		}
		
		if(true == $attachments && NULL != $this->file_uploader)
        {
			$this->file_uploader->AttachFilesToDownload();
		}		
	}
    
    function getUniqueFieldName()
    {
        foreach ($this->uniquefields as $field) 
        {
            if(isset($_GET[$field])){ return $field; }
        }
        return false;
    }
    
    function IsFieldUnique($field_name,$field_value)
    {
        if(empty($field_value)){ return true; }//required validation should be separate 
        if(!$this->Login())
        {
            $this->logger->LogError("IsFieldUnique: Failed logging in to database");
            return true;
        }
        $result = @mysqli_query($this->connection,"SELECT Count(*) from $this->tablename WHERE $field_name='$field_value'");  
        if(!$result || mysqli_num_rows($result) <= 0)
        {
            $this->logger->LogInfo("IsFieldUnique: ".$this->GetError());
            return true;
        }
        $row = mysqli_fetch_row($result);
        if($row[0] <= 0)
        {
            return true;
        }
        return false;
    }
    
    function GetRecCount(&$response_sender)
    {
        if(!$this->Login())
        {
            $response_sender->addError('Loging in to the Database failed');
            return false;
        }

        $result = mysqli_query($this->connection,"SELECT Count(*) from $this->tablename");   

        if(!$result || mysqli_num_rows($result) <= 0)
        {
            $response_sender->addError(
            "Failed fetching the number of rows from the table : ".$this->GetError());
            return false;
        }
        
        $row = mysqli_fetch_row($result);

        $resp = "count:".$row[0];

        $response_sender->SetResponse($resp);
    }

    function GetDBRecs(&$response_sender,$val)
    {
        $parts = explode(',',$val);
        $offset=$parts[0];
        $count=$parts[1];
        $this->GetRecs($response_sender,$offset,$count);
    }

    function GetRecs(&$response_sender,$offset,$rec_count)
    {
       if(!$this->Login())
        {
            $response_sender->addError('Log-in to the Database failed');
            return false;
        }

        $sel_expr ="";

        $db_field_list = $this->getDBFormatFields();
        
        $sel_expr = implode($db_field_list,',');
        
        $qry = "Select $sel_expr FROM $this->tablename LIMIT $offset,$rec_count";

        $result = mysqli_query($this->connection, $qry);   

        if(!$result )
        {
            $response_sender->addError(
            "Failed fetching records from the table : ".$this->GetError());
            return false;
        }

        $response ='';
        $row='';
        foreach($this->dbutil->fields as $formfield)
        {
            $row .= $formfield['name'].",";
        }
        $row = trim($row,',');

        $response .= "$row\n";

        while($rec = mysqli_fetch_assoc($result)) 
        {
            $row='';
            foreach($this->dbutil->fields as $formfield)
            {
                $fname = $formfield['name'];
                $row .= sfm_csv_escape($rec[$fname]).",";
            }
            $row = trim($row,',');
            $response .= "$row\n";
        }

        $response_sender->SetEncrypt(false);
        $response_sender->SetResponse($response);
    }
    
    /* Admin Page Ajax Queries */
    function GetFieldsJSON()
    {
        echo json_encode($this->getFieldArray());
        return true;
    }
    
    function compareGridOpts($a,$b)
    {
        if($a['s_order'] == $b['s_order']){ return 0;}
        
        return ($a['s_order'] > $b['s_order']) ? 1 : -1;
    }
    
    function GetGridOptions()
    {
        $cookie_var = $this->formname.'_sfm_grid_options';

        if(empty($_COOKIE[$cookie_var]))
        {
            $this->logger->LogInfo("GetGridOptions COOKIE $cookie_var is empty!");
            return false;
        }
        $opts = stripslashes($_COOKIE[$cookie_var]);
        
        return unserialize($opts);
    }
    function SaveGridOptions()
    {
        $expire = time() + 60*60*24*30;
        
        $grid_opts = array();
        
        $grid_opts['colorder'] = $_POST['colorder'];
        $grid_opts['colwidths'] = $_POST['colwidths'];
        $grid_opts['colvisible'] = $_POST['colvisible'];
        
        $cookie_var = $this->formname.'_sfm_grid_options';
        
        $cookval = serialize($grid_opts);
        $ret = setcookie($cookie_var,$cookval,$expire);
        
    }
    
    function CreateGridForDB()
    {
        $grid4db = new FM_GridForDB();
        $grid4db->Init($this->config,$this->logger,$this->error_handler,$this->ext_module);
        $grid4db->Login();
        return $grid4db;
    }
    
    function ShowPrintablePage($id)
    {
         $table_code = "\n<table><tbody>\n";
         $grid4db = $this->CreateGridForDB();
         
         $fields = $this->getDBFormatFields();
         $rec = $grid4db->GetSingleRec($this->tablename,$id,$fields);
         
         $fields = $this->getFieldArray();
         $trec =$rec[0];
         foreach($fields as $f)
         {
             $dispname = empty($f['dispname'])?$f['name']:$f['dispname'];
             $val = $trec[$f['name']];
             $table_code .="<tr><td class='caption'>$dispname</td><td>$val</td></tr>\n";
         }
         $table_code .="\n</tbody></table>\n";
         echo str_replace('_PRINT_BODY_',$table_code,$this->config->GetPrintPreviewPage());
         
    }
    
    function GetSingleRecJSON($id)
    {
        $grid4db = $this->CreateGridForDB();
        $fields = $this->getDBFormatFields();
        $rec_json = $grid4db->GetSingleRecJSON($this->tablename,$id,$fields);
        
        echo $rec_json;
    }
    function getMySQLDateFormat()
    {
        $map = array(
        'd'=>'%e',
        'dd'=>'%d',
        'ddd'=>'%a',
        'dddd'=>'%W',
        'M'=>'%c',
        'MM'=>'%m',
        'MMM'=>'%b',
        'MMMM'=>'%M',
        'yy'=>'%y',
        'yyyy'=>'%Y',
        'm'=>'%i',
        'mm'=>'%i',
        'h'=>'%h',
        'hh'=>'%h',
        'H'=>'%H',
        'HH'=>'%H',
        's'=>'%S',
        'ss'=>'%S',
        't'=>'%p',
        'tt'=>'%p'
        );
        
        if(empty($this->config->locale_dateformat))
        {
            return '%Y-%m-%d';
        }
        
        $format = $this->config->locale_dateformat;
        
        $arr_ret = preg_split('/([^\w]+)/i', $format,-1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

        foreach($arr_ret as $k=>$v)
        {
            if(isset($map[$v]))
            {
                $arr_ret[$k] = $map[$v];
            }
        }
        $mysql_format = implode($arr_ret); 
        return $mysql_format;
    }
    function getDBFormatFields()
    {
        $fields = $this->getFieldArray();
        
        $field_list = array();
        $date_format = $this->getMySQLDateFormat();
        foreach($fields as $field)
        {
            $dbfield = $fieldname = $field['name'];
            $fieldobj = $this->dbutil->GetFieldDetails($fieldname);
            $db_field_type = $fieldobj['type'];
            
            if( $db_field_type == 'DATE' )
            {
               $dbfield = "DATE_FORMAT($fieldname,'$date_format') AS $fieldname";
            }
            else if($db_field_type == 'DATETIME')
            {
                $dbfield = "DATE_FORMAT($fieldname,'$date_format %r') AS $fieldname";
            }
            $field_list[] = $dbfield;
        }
        
        return $field_list;
    }
    function GetRecordsJSON()
    {
        $grid4db = $this->CreateGridForDB();
        
        //DB field list will have DB dependant formatting options like DATE_FORMAT
        $db_field_list = $this->getDBFormatFields();
        
        $fields = $this->getFieldArray();
        //Plain field names
        $field_names = array();
        foreach($fields as $field)
        {
            $field_names[] = $field['name'];
        }

        $resp = $grid4db->GetJSONResponse($this->tablename,$db_field_list,$field_names);
        if(false === $resp)
        {
            $this->error_handler->HandleConfigError("Error getting Records");
            return false;
        }
        echo $resp;
        return true;
    }   

    function getFieldArray()
    {
        $internal_fields=array(array('name'=>'ID'));
        $fields = array_merge($internal_fields,$this->dbutil->GetFields());
        
        $grid_opts = $this->GetGridOptions();
  
        $field_list = array();
        foreach($fields as $field)
        {
            $name = $field['name'];
            $f = array('name'=>$field['name']);
            if(!empty($field['dispname'])){ $f['dispname']=$field['dispname']; }
            
            $f['s_order']=999;
            if(false !== $grid_opts)
            {
                $f['s_order'] =(!empty($grid_opts['colorder'][$name])) ? ($grid_opts['colorder'][$name]) : 999;
                
                $f['width'] =(!empty($grid_opts['colwidths'][$name])) ? ($grid_opts['colwidths'][$name]) : 150;
                
                $f['visible'] = (!empty($grid_opts['colvisible'][$name])) ? ($grid_opts['colvisible'][$name]):true;
            }
            $field_list[] = $f;
        }  
        
        if(false !== $grid_opts)
        {
            usort($field_list,array($this, "compareGridOpts"));
        }
        
        return $field_list;
    }
}

class FM_GridResponse
{
  public $page;  
  public $total;
  public $records;
  public $rows;

  public function __construct()
  {
      $this->page = 0;  
      $this->total = 0;
      $this->records = 0;
      $this->rows = array();    
  }
};

class FM_GridForDB
{
    private $page;
    private $limit;
    private $sidx;
    private $sord;
    private $qtype; // 	The column selected during 'quick search'.
    private $query; //	The text used within a search. 
    
    private $dbutil;
    
    private $error_handler;
    private $logger;
    private $config;
    private $ext_module_holder;
    
    public function __construct()
    {
        $this->dbutil = new FM_DBUtil();
        $this->GetParams();
    }
    
    function Init(&$config,&$logger,&$error_handler,&$ext_modules)
    {
        $this->error_handler = &$error_handler;
        $this->logger = &$logger;
        $this->config = &$config;
        $this->ext_module_holder = &$ext_modules;
        $this->dbutil->Init($config, $logger, $error_handler);
    }
    
    function Login()
    {
        return $this->dbutil->Login();
    }
    
    function GetJSONResponse($tablename, $db_fields,$fieldnames)
    {
        $response = new FM_GridResponse();
        $response->records = $this->dbutil->GetCount($tablename);
        
        $total_pages = ceil($response->records/$this->limit);
        if($this->page > $total_pages){  $this->page = $total_pages;}
        
        $start = $this->limit * $this->page - $this->limit;
        if ($start<0) { $start = 0; }
        
        $response->page = $this->page;
        $response->total = $response->records;
        
        $strfields = implode(',',$db_fields);
        
        $where='';
        $qval   = trim($this->query);
        $qfield = trim($this->qtype);
        if(!empty($qval) && !empty($qfield))
        {
            $where=" $qfield LIKE '%$qval%'";
        }
        /*elseif(empty($qval) && !empty($qfield))
        {
            $where=" $qfield = ''";
        }*/
        
        $rows = $this->dbutil->GetRecords($tablename,$strfields,$where,
                    $this->sidx,$this->sord,$start,$this->limit);
        
        if(false === $rows)
        {
            return false;
        }
        foreach($rows as $rec)
        {
            convert_html_entities_in_formdata('',$rec);
            if(false === $this->ext_module_holder->BeforeSubmissionTableDisplay($rec))
            {
                continue;
            }
            
            $cell=array();
            foreach($fieldnames as $field_name)
            {
                $cell[] = isset($rec[$field_name])?$rec[$field_name]:'';
            }
            $response->rows[] = array('id'=>$rec['ID'],'cell'=>$cell);
        }
        
        return  json_encode($response);
    }
    function GetSingleRec($tablename,$id,$fields=false)
    {
        $rec = array();
        if(false === $this->dbutil->ReadData($tablename,$rec,"ID=$id",$fields))
        {
            return false;
        }
        if(empty($rec))
        {
            return false;
        }
        convert_html_entities_in_formdata('',$rec[0]);
        if(false === $this->ext_module_holder->BeforeDetailedPageDisplay($rec[0]))
        {
            $this->logger->LogError("Extension module returns error from BeforeDetailedPageDisplay");
            return false;
        }
        return $rec;
    }
    function GetSingleRecJSON($tablename,$id,$fields=false)
    {
        $rec = $this->GetSingleRec($tablename,$id,$fields);
        return json_encode($rec);
    }
    
    function GetParams()
    {
        $this->page  = $this->CheckAssign('page',0);
        $this->limit = $this->CheckAssign('rp',10);
        $this->sidx = $this->CheckAssign('sortname',1);
        $this->sord = $this->CheckAssign('sortorder','');
        $this->qtype = $this->CheckAssign('qtype','');
        $this->query = $this->CheckAssign('query','');
    }
    
    function CheckAssign($varname,$default)
    {
        return empty($_POST[$varname]) ? $default:$_POST[$varname];
    }
};

if (!function_exists('json_encode'))
{
  function json_encode($a=false)
  {
    if (is_null($a)) return 'null';
    if ($a === false) return 'false';
    if ($a === true) return 'true';
    if (is_scalar($a))
    {
      if (is_float($a))
      {
        // Always use "." for floats.
        return floatval(str_replace(",", ".", strval($a)));
      }

      if (is_string($a))
      {
        static $jsonReplaces = array(array("\\", "/", "\n", "\t", "\r", "\b", "\f", '"'), array('\\\\', '\\/', '\\n', '\\t', '\\r', '\\b', '\\f', '\"'));
        return '"' . str_replace($jsonReplaces[0], $jsonReplaces[1], $a) . '"';
      }
      else
        return $a;
    }
    $isList = true;
    for ($i = 0, reset($a); $i < count($a); $i++, next($a))
    {
      if (key($a) !== $i)
      {
        $isList = false;
        break;
      }
    }
    $result = array();
    if ($isList)
    {
      foreach ($a as $v) $result[] = json_encode($v);
      return '[' . join(',', $result) . ']';
    }
    else
    {
      foreach ($a as $k => $v) $result[] = json_encode($k).':'.json_encode($v);
      return '{' . join(',', $result) . '}';
    }
  }
}

//http://www.clker.com/blog/2008/03/27/creating-a-tar-gz-on-the-fly-using-php/

// Computes the unsigned Checksum of a file�s header
// to try to ensure valid file
// PRIVATE ACCESS FUNCTION

function _sfm_computeUnsignedChecksum($bytestring)
{
  for($i=0; $i<512; $i++)
    $unsigned_chksum += ord($bytestring[$i]);
  for($i=0; $i<8; $i++)
    $unsigned_chksum -= ord($bytestring[148 + $i]);
  $unsigned_chksum += ord(" ") * 8;
 
  return $unsigned_chksum;
}
 
// Generates a TAR file from the processed data
// PRIVATE ACCESS FUNCTION
function _sfm_tarSection($Name, $Data, $information=NULL)
{
  // Generate the TAR header for this file
 
  $header .= str_pad($Name,100,chr(0));
  $header .= str_pad("777",7,"0",STR_PAD_LEFT) . chr(0);
  $header .= str_pad(decoct($information["user_id"]),7,"0",STR_PAD_LEFT) . chr(0);
  $header .= str_pad(decoct($information["group_id"]),7,"0",STR_PAD_LEFT) . chr(0);
  $header .= str_pad(decoct(strlen($Data)),11,"0",STR_PAD_LEFT) . chr(0);
  $header .= str_pad(decoct(time(0)),11,"0",STR_PAD_LEFT) . chr(0);
  $header .= str_repeat(" ",8);
  $header .= "0";
  $header .= str_repeat(chr(0),100);
  $header .= str_pad("ustar",6,chr(32));
  $header .= chr(32) . chr(0);
  $header .= str_pad($information["user_name"],32,chr(0));
  $header .= str_pad($information["group_name"],32,chr(0));
  $header .= str_repeat(chr(0),8);
  $header .= str_repeat(chr(0),8);
  $header .= str_repeat(chr(0),155);
  $header .= str_repeat(chr(0),12);
 
  // Compute header checksum
  $checksum = str_pad(decoct(_sfm_computeUnsignedChecksum($header)),6,"0",STR_PAD_LEFT);
  for($i=0; $i<6; $i++) {
    $header[(148 + $i)] = substr($checksum,$i,1);
  }
  $header[154] = chr(0);
  $header[155] = chr(32);
 
  // Pad file contents to byte count divisible by 512
  $file_contents = str_pad($Data,(ceil(strlen($Data) / 512) * 512),chr(0));
 
  // Add new tar formatted data to tar file contents
  $tar_file = $header . $file_contents;
 
  return $tar_file;
}
 
function sfm_targz($Name, $Data)
{
  return gzencode(_sfm_tarSection($Name,$Data),9);
}

class FM_ShortUniqueID extends FM_ExtensionModule
{
   private $tablename;

   public function __construct($tablename)
   {
       parent::__construct();
       $this->tablename = $tablename;
        
       $this->dbutil = null;
   }

   function AfterVariablessInitialized()
   {
       $sessionvar = '_sfm_short_unique_id';
       if(empty($this->globaldata->session[$sessionvar]))
       {
          $this->PreprocessFormSubmissionX($this->formvars);
          $this->globaldata->session[$sessionvar] = $this->formvars[$this->config->unique_id_var];
       }
       else
       {
            $this->formvars[$this->config->unique_id_var] = $this->globaldata->session[$sessionvar];
       }
       
       return true;
   }
   function PreprocessFormSubmissionX(&$formvars)
   {
       if(!$this->LoginToDB())
       {
            return true;
       }
       $destdigits = 'abcdefghijklmnopqrstuvwxyz0123456789';
       
       mt_srand((double)microtime()*10000000);

       
       for($i=0;$i<100;$i++)
       {
           $idseed = (time() - mktime(0,0,0,1,1,2013))*mt_rand(10,100) + mt_rand(1000,10000);
           
           $newid = base_convert_arbitrary((string)$idseed, 10, 36,'0123456789',$destdigits);
           
           if($this->isUnique($newid))
           {
               $formvars[$this->config->unique_id_var] = $newid;
               break;
           }
       } 
       
       $this->dbutil->Close();
       return true;
   }
   
   function LoginToDB()
   {
        $this->dbutil = new FM_DBUtil();

        $this->dbutil->Init($this->config,$this->logger,$this->error_handler);
        if(!$this->dbutil->Login())
        {
            return false;
        }
        return true;
   }
   
   function isUnique($id)
   {
        $uniqidfield = $this->config->unique_id_var;
        
		$rec_count = $this->dbutil->GetSingleValue("SELECT Count(*) from $this->tablename where $uniqidfield='$id'");
		
		if(false === $rec_count || $rec_count <= 0)
		{
            return true;
		}
        return false;
   }
}

function base_convert_arbitrary($number, $fromBase, $toBase,$sourcedigits,$destdigits) 
{
    $length = strlen($number);
    $result = '';
 
    $nibbles = array();
    for ($i = 0; $i < $length; ++$i) {
        $nibbles[$i] = strpos($sourcedigits, $number[$i]);
    }
 
    do {
        $value = 0;
        $newlen = 0;
        for ($i = 0; $i < $length; ++$i) {
            $value = $value * $fromBase + $nibbles[$i];
            if ($value >= $toBase) {
                $nibbles[$newlen++] = (int)($value / $toBase);
                $value %= $toBase;
            }
            else if ($newlen > 0) {
                $nibbles[$newlen++] = 0;
            }
        }
        $length = $newlen;
        $result = $destdigits[$value].$result;
    }
    while ($newlen != 0);
    return $result;
}

class FM_ThankYouPage extends FM_Module
{
    private $page_templ;
    private $redir_url;

    public function __construct($page_templ="")
    {
        parent::__construct();
        $this->page_templ=$page_templ;
        $this->redir_url="";
    }
    
    function Process(&$continue)
    {
      $ret = true;
      if(false === $this->ext_module->FormSubmitted($this->formvars))
      {
         $this->logger->LogInfo("Extension Module returns false for FormSubmitted() notification");
         $ret = false;
      }
      else
      {
        $ret = $this->ShowThankYouPage();
      }
  
      if($ret)
      {
         $this->globaldata->SetFormProcessed(true);
      }
      
      return $ret;
    }
    
    function ShowThankYouPage($params='')
    {
      $ret = false;
      if(strlen($this->page_templ)>0)
      {
         $this->logger->LogInfo("Displaying thank you page");
         $ret = $this->ShowPage();
      }
      else
      if(strlen($this->redir_url)>0)
      {
         $this->logger->LogInfo("Redirecting to thank you URL");
         $ret = $this->Redirect($this->redir_url,$params);
      }    
      return $ret;
    }
    
    function SetRedirURL($url)
    {
        $this->redir_url=$url;
    }

    function ShowPage()
    {
        header("Content-Type: text/html");
        echo $this->ComposeContent($this->page_templ);
        return true;
    }
    
    function ComposeContent($content,$urlencode=false)
    {
        $merge = new FM_PageMerger();
        $html_conv = $urlencode?false:true;
        $tmpdatamap = $this->common_objs->formvar_mx->CreateFieldMatrix($html_conv);

        if($urlencode)
        {
            foreach($tmpdatamap as $name => $value)
            {
                $tmpdatamap[$name] = urlencode($value);
            }
        }
        
        $this->ext_module->BeforeThankYouPageDisplay($tmpdatamap);

        if(false == $merge->Merge($content,$tmpdatamap))
        {
            $this->logger->LogError("ThankYouPage: merge failed");
            return '';
        }

        return $merge->getMessageBody();
    }
    
    function Redirect($url,$params)
    {
        $has_variables = (FALSE === strpos($url,'?'))?false:true;
        
        if($has_variables)
        {
            $url = $this->ComposeContent($url,/*urlencode*/true);
            if(!empty($params))
            {
                $url .= '&'.$params;
            }
        }
        else if(!empty($params))
        {
            $url .= '?'.$params;
            $has_variables=true;
        }
        
        $from_iframe = isset($this->globaldata->session['sfm_from_iframe']) ? 
                    intval($this->globaldata->session['sfm_from_iframe']):0;
        
        if( $has_variables  || $from_iframe )
        {
            $url = htmlentities($url,ENT_QUOTES,"UTF-8");
            //The code below is put in so that it works with iframe-embedded forms also
            //$script = "window.open(\"$url\",\"_top\");";
            //$noscript = "<a href=\"$url\" target=\"_top\">Submitted the form successfully. Click here to redirect</a>";

            $page = <<<EOD
<html>
<head>
 <meta http-equiv='Content-Type' content='text/html; charset=utf-8'/>
<script language='JavaScript'>
function redirToURL()
{
    var url = document.getElementById('redirurl').href;
    window.open(url,'_top');
}
</script>
</head>
<body onload='redirToURL()'>
<a style='display:none' id='redirurl' href='$url' target='_top'>Redirect</a>
<noscript>
<a href='$url' target='_top'>Submitted the form successfully. Click here to redirect</a>
</noscript>
</body>
</html>
EOD;
            header('Content-Type: text/html; charset=utf-8');
            echo $page;
        }
        else
        {
            header("Location: $url");
        }
        return true;
    }
}//FM_ThankYouPage

define("CONST_PHP_TAG_START","<"."?"."PHP");

///////Global Functions///////
function sfm_redirect_to($url)
{
	header("Location: $url");
}
function sfm_make_path($part1,$part2)
{
    $part1 = rtrim($part1,"/\\");
    $ret_path = $part1."/".$part2;
    return $ret_path;
}
function magicQuotesRemove(&$array) 
{
   if(!get_magic_quotes_gpc())
   {
       return;
   }
   foreach($array as $key => $elem) 
   {
      if(is_array($elem))
      {
           magicQuotesRemove($elem);
      }
      else
      {
           $array[$key] = stripslashes($elem);
      }//else
   }//foreach
}

function CreateHiddenInput($name, $objvalue)
{
    $objvalue = htmlentities($objvalue,ENT_QUOTES,"UTF-8");
    $str_ret = " <input type='hidden' name='$name' value='$objvalue'>";
    return $str_ret;
}

function sfm_get_disp_variable($var_name)
{
    return 'sfm_'.$var_name.'_disp';
}
function convert_html_entities_in_formdata($skip_var,&$datamap,$br=true)
{
    foreach($datamap as $name => $value)
    {
        if(strlen($skip_var)>0 && strcmp($name,$skip_var)==0)
        {
            continue;
        }
        if(true == is_string($datamap[$name]))
        {
          if($br)
          {
            $datamap[$name] = nl2br(htmlentities($datamap[$name],ENT_QUOTES,"UTF-8"));
          }
          else
          {
            $datamap[$name] = htmlentities($datamap[$name],ENT_QUOTES,"UTF-8");
          }
        }
    }//foreach
}

function sfm_get_mime_type($filename) 
{
   $mime_types = array(
            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/plain',
            'css' => 'text/css',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',

            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );

    $ext = sfm_getfile_extension($filename);
    
    if (array_key_exists($ext, $mime_types)) 
    {
        return $mime_types[$ext];
    }
    elseif(function_exists('finfo_open')) 
    {
        $finfo = finfo_open(FILEINFO_MIME);
        $mimetype = finfo_file($finfo, $filename);
        finfo_close($finfo);
        return $mimetype;
    }
    else 
    {
        return 'application/octet-stream';
    }
}
    
function array_push_ref(&$target,&$value_array)
{
    if(!is_array($target))
    {
        return FALSE;
    }
    $target[]=&$value_array;
    return TRUE;
}

function sfm_checkConfigFileSign($conf_content,$strsign)
{
    $conf_content = substr($conf_content,strlen(CONST_PHP_TAG_START)+1);
    $conf_content = ltrim($conf_content); 

    if(0 == strncmp($conf_content,$strsign,strlen($strsign)))
    {
        return true;
    }
    return false;
}

function sfm_readfile($filepath)
{
    $retString = file_get_contents($filepath);
    return $retString;
}

function sfm_csv_escape($value)
{
    if(preg_match("/[\n\"\,\r]/i",$value))
    {
        $value = str_replace("\"","\"\"",$value);
        $value = "\"$value\"";
    }    
    return $value;
}

function sfm_crypt_decrypt($in_str,$key)
{
    $blowfish =& Crypt_Blowfish::factory('ecb');
    $blowfish->setKey($key);
    
    $bin_data = pack("H*",$in_str);
    $decr_str = $blowfish->decrypt($bin_data);
    if(PEAR::isError($decr_str))
    {
        return "";
    }
    $decr_str = trim($decr_str);
    return $decr_str;
}

function sfm_crypt_encrypt($str,$key)
{
    $blowfish =& Crypt_Blowfish::factory('ecb');
    $blowfish->setKey($key);

    $encr = $blowfish->encrypt($str);
    $retdata = bin2hex($encr);
    return $retdata;
}
function sfm_selfURL_abs()
{
    $s = '';
    if(!empty($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == 'on')
    {
        $s='s';
    }
     
    $protocol = 'http'.$s;
    $port = ($_SERVER["SERVER_PORT"] == '80') ? '' : (':'.$_SERVER["SERVER_PORT"]);
    return $protocol."://".$_SERVER['HTTP_HOST'].$port.$_SERVER['PHP_SELF'];
}
function strleft($s1, $s2) 
{ 
    return substr($s1, 0, strpos($s1, $s2)); 
}
function sfm_getfile_extension($path)
{
    $info = pathinfo($path);
    $ext='';
    if(isset($info['extension']))
    {
        $ext = strtolower($info['extension']);
    }
    return $ext;
}

function sfm_filename_no_ext($fullpath)
{
    $filename = basename($fullpath);

    $pos = strrpos($filename, '.');
    if ($pos === false)
    { // dot is not found in the filename
        return $filename; // no extension
    }
    else
    {
        $justfilename = substr($filename, 0, $pos);
        return $justfilename;
    }
}

function sfm_validate_multi_conditions($condns,$formvariables)
{
   $arr_condns = preg_split("/(\s*\&\&\s*)|(\s*\|\|\s*)/", $condns, -1, 
                     PREG_SPLIT_DELIM_CAPTURE|PREG_SPLIT_NO_EMPTY);
   
   $conn = '';
   $ret = false;
   
   foreach($arr_condns as $condn) 
   {
      $condn = trim($condn);
      if($condn == '&&' || $condn == '||')
      {
         $conn = $condn;
      }
      else
      {
         $res = sfm_validate_condition($condn,$formvariables);
         
         if(empty($conn))
         {
            $ret = $res ;
         }
         elseif($conn =='&&')
         {
           $ret = $ret && $res;
         }
         elseif($conn =='||')
         {
            $ret = $ret || $res;
         }
      }//else
   }
   return $ret ;
}

function sfm_compare_ip($ipcompare, $currentip)
{
   $arr_compare = explode('.',$ipcompare);
   $arr_current = explode('.',$currentip);

   $N = count($arr_compare); 
   
   for($i=0;$i<$N;$i++)
   {
      $piece1 = trim($arr_compare[$i]);
      
      if($piece1 == '*')
      {
       continue;
      }
      if(!isset($arr_current[$i]))
      {
         return false;
      }
      
      $piece2 = trim($arr_current[$i]);
      
      if($piece1 != $piece2)
      {
         return false;
      }
   }
   return true;
   
}

function sfm_validate_condition($condn,$formvariables)
{
  if(!preg_match("/([a-z_A-Z]*)\(([a-zA-Z0-9_]*),\"(.*)\"\)/",$condn,$res))
  {
      return false;
  }
  $type = strtolower(trim($res[1]));
  $arg1 = trim($res[2]);
  $arg2 = trim($res[3]);
  $bret=false;

  switch($type)
  {
      case "is_selected_radio":
      case "isequal":
      {
          if(isset($formvariables[$arg1]) &&
            strcasecmp($formvariables[$arg1],$arg2)==0 )
          {
              $bret=true;
          }
          break;
      }//case
      case "ischecked_single":
      {
          if(!empty($formvariables[$arg1]))
          {
              $bret=true;
          }
          break;
      }
      case "contains":
      {
          if(isset($formvariables[$arg1]) &&
            stristr($formvariables[$arg1],$arg2) !== FALSE )
          {
              $bret=true;
          }                
          break;
      }
      case "greaterthan":
      {
          if(isset($formvariables[$arg1]) &&
            floatval($formvariables[$arg1]) > floatval($arg2))
          {
              $bret=true;
          }                
          break;
      }
      case "lessthan":
      {
          if(isset($formvariables[$arg1]) &&
            floatval($formvariables[$arg1]) < floatval($arg2))
          {
              $bret=true;
          }                
          break;                
      }
      case "is_not_checked_single":
      {
          if(empty($formvariables[$arg1]) )
          {
              $bret=true;
          }
          break;
      }
      case "is_not_selected_radio":
      {
          if(!isset($formvariables[$arg1]) ||
            strcasecmp($formvariables[$arg1],$arg2) !=0 )
          {
              $bret=true;
          }
          break;
      }
      case "is_selected_list_item":
      case "is_checked_group":
      {
          if(isset($formvariables[$arg1]))
          {
              if(is_array($formvariables[$arg1]))
              {
                  foreach($formvariables[$arg1] as $chk)
                  {
                      if(strcasecmp($chk,$arg2)==0)
                      {
                          $bret=true;break;
                      }
                  }//foreach
              }
              else
              {
                  if(strcasecmp($formvariables[$arg1],$arg2)==0)
                  {
                      $bret=true;break;
                  }                        
              }//else
          }
          break;
      }//case]
  case "is_not_selected_list_item":
  case "is_not_checked_group":
      {
          $bret=true;
          if(isset($formvariables[$arg1]))
          {
              if(is_array($formvariables[$arg1]))
              {
                  foreach($formvariables[$arg1] as $chk)
                  {
                      if(strcasecmp($chk,$arg2)==0)
                      {
                          $bret=false;break;
                      }
                  }//foreach
              }
              else
              {
                  if(strcasecmp($formvariables[$arg1],$arg2)==0)
                  {
                      $bret=false;break;
                  }                        
              }//else
          }
          break;
      }//case
      case 'is_empty':
      {
          if(!isset($formvariables[$arg1]))
          {
              $bret=true;
          }
          else
          {
              $tmp_arg=trim($formvariables[$arg1]);
              if(empty($tmp_arg))
              {
                  $bret=true;
              }
          }
          break;
      }
      case 'is_not_empty':
      {
          if(isset($formvariables[$arg1]))
          {
              $tmp_arg=trim($formvariables[$arg1]);
              if(!empty($tmp_arg))
              {
                  $bret=true;
              }                    
          }
          break;
      }

  }//switch

  return $bret;
}
if (!function_exists('_'))
{
    function _($s)
    {
        return $s;
    }
}

class FM_ElementInfo
{
   private $elements;
   public $default_values;

   public function __construct()
   {
      $this->elements = array();
      $this->default_values = array();
   }
   function AddElementInfo($name,$type,$extrainfo,$page)
   {
      $this->elements[$name]["type"] = $type;
      $this->elements[$name]["extra"] = $extrainfo;
      $this->elements[$name]["page"]= $page;
   }
   function AddDefaultValue($name,$value)
   {
      
      if(isset($this->default_values[$name]))
      {
         if(is_array($this->default_values[$name]))
         {
            array_push($this->default_values[$name],$value);
         }
         else
         {
             $curvalue = $this->default_values[$name];
             $this->default_values[$name] = array($curvalue,$value);
         }
      }
      else
      {
        $this->default_values[$name] = $this->doStringReplacements($value);
      }
   }
   
   function doStringReplacements($strIn)
   {
     return str_replace(array('\n'),array("\n"),$strIn);
   } 
   
   function IsElementPresent($name)
   {
      return isset($this->elements[$name]);
   }
   function GetType($name)
   {
      if($this->IsElementPresent($name) && 
        isset($this->elements[$name]["type"]))
        {
            return $this->elements[$name]["type"];
        }
        else
        {
            return '';
        }
   }
   
   function IsUsingDisplayVariable($name)
   {
     $type = $this->GetType($name);
     $ret = false;
     if($type == 'datepicker' ||
        $type == 'decimal' ||
        $type == 'calcfield')
     {
        $ret = true;
     }
     return $ret;
   }
   function GetExtraInfo($name)
   {
     return $this->elements[$name]["extra"];
   }
   
   function GetPageNum($name)
   {
      return $this->elements[$name]["page"];
   }
   
   function GetElements($page,$type='')
   {
        $ret_arr = array();
        foreach($this->elements as $ename => $eprops)
        {
            if(($eprops['page'] == $page) && 
               (empty($type) || $type == $eprops['type']))
            {
                $ret_arr[$ename] = $eprops;
            }
        }
        return $ret_arr;
   }
   
   function GetAllElements()
   {
       return $this->elements;
   }
}

/////Config/////
class FM_Config
{
    public $formname;
    public $form_submit_variable; 
    public $form_page_code;
    public $error_display_variable;
    public $display_error_in_formpage;
    public $error_page_code;
    public $email_format_html;
    public $slashn;
    public $installed;
    public $log_flush_live;
    public $encr_key;
    public $form_id;
    public $sys_debug_mode;
    public $error_mail_to;
    public $use_smtp;
    public $smtp_host;
    public $smtp_uname;
    public $smtp_pwd;
    public $from_addr;
    public $variable_from;
    public $common_date_format;
    public $var_cur_form_page_num;
    public $var_form_page_count;
    public $var_page_progress_perc;
    public $element_info;
    public $print_preview_page;
    public $v4_email_headers;
    public $fmdb_host;
    public $fmdb_username;
    public $fmdb_pwd;
    public $fmdb_database;
    public $saved_message_templ;
	  public $default_timezone;
    public $enable_auto_field_table;
    

//User configurable (through extension modules)  
    public  $form_file_folder;//location to save csv file, log file etc
    public  $load_values_from_url;
    public  $allow_nonsecure_file_attachments;
    public  $file_upload_folder;
    public  $debug_mode;    
    public  $logfile_size;   
    public  $bypass_spammer_validations;
    public  $passwords_encrypted;
	  public  $enable_p2p_header;
    public  $enable_session_id_url;
    public  $locale_name;
    public  $locale_dateformat;
    public  $array_disp_seperator;//used for imploding arrays before displaying
    
   public function __construct()
   {
      $this->form_file_folder="";
      $this->installed = false;

      $this->form_submit_variable   ="sfm_form_submitted";
      $this->form_page_code="<HTML><BODY><H1>Error! code 104</h1>%sfm_error_display_loc%</body></HTML>";
      $this->error_display_variable = "sfm_error_display_loc";
      $this->show_errors_single_box = false;
      $this->self_script_variable = "sfm_self_script";
      $this->form_filler_variable="sfm_form_filler_place";
      $this->confirm_file_list_var = "sfm_file_uploads";

      $this->config_update_var = "sfm_conf_update";

      $this->config_update_val = "sfm_conf_update_val";

      $this->config_form_id_var = "sfm_form_id";

      $this->visitor_ip_var = "_sfm_visitor_ip_";
	  
	  $this->unique_id_var = "_sfm_unique_id_";
      
      $this->form_page_session_id_var = "_sfm_form_page_session_id_";
      //identifies a particular display of the form page. refreshing the page
      // or opening a new browser tab creates a different id
	  
      $this->submission_time_var ="_sfm_form_submision_time_";

      $this->submission_date_var = "_sfm_form_submision_date_";

      $this->referer_page_var = "_sfm_referer_page_";

      $this->user_agent_var = "_sfm_user_agent_";

      $this->visitors_os_var = "_sfm_visitor_os_";

      $this->visitors_browser_var = "_sfm_visitor_browser_";
      
      $this->var_cur_form_page_num='sfm_current_page';
      
      $this->var_form_page_count = 'sfm_page_count';
      
      $this->var_page_progress_perc = 'sfm_page_progress_perc';
      
      $this->form_id_input_var = '_sfm_form_id_iput_var_';
      
      $this->form_id_input_value = '_sfm_form_id_iput_value_';

      $this->display_error_in_formpage=true;
      $this->error_page_code  ="<HTML><BODY><H1>Error!</h1>%sfm_error_display_loc%</body></HTML>";
      $this->email_format_html=false;
      $this->slashn = "\r\n";
      $this->saved_message_templ = "Saved Successfully. {link}";
      $this->reload_formvars_var="rd";
      
      $this->log_flush_live=false;

      $this->encr_key="";
      $this->form_id="";
      $this->error_mail_to="";
      $this->sys_debug_mode = false;
      $this->debug_mode = false;
      $this->element_info = new FM_ElementInfo();

      $this->use_smtp = false;
      $this->smtp_host='';
      $this->smtp_uname='';
      $this->smtp_pwd='';
      $this->smtp_port='';
      $this->from_addr='';
      $this->variable_from=false;
      $this->v4_email_headers=true;
      $this->common_date_format = 'Y-m-d';
      $this->load_values_from_url = false;
      
      $this->hidden_input_trap_var='';
      
      $this->allow_nonsecure_file_attachments = false;
      
      $this->bypass_spammer_validations=false;
      
      $this->passwords_encrypted=true;
	    $this->enable_p2p_header = true;
      $this->enable_session_id_url=true;
      
	    $this->default_timezone = 'default';
      
      $this->array_disp_seperator ="\n";
      
      $this->enable_auto_field_table=false;
   }
    
   function set_encrkey($key)
   {
     $this->encr_key=$key;
   }
    
   function set_form_id($form_id)
   {
     $this->form_id = $form_id;
   }

   function set_error_email($email)
   {
      $this->error_mail_to = $email;
   }

   function get_form_id()
   {
     return $this->form_id;
   }

   function setFormPage($formpage)
   {
      $this->form_page_code = $formpage;
   }

   function setDebugMode($enable)
   {
      $this->debug_mode = $enable;
      $this->log_flush_live = $enable?true:false;
   }

   function getCommonDateTimeFormat()
   {
     return $this->common_date_format." H:i:s T(O \G\M\T)";
   }

   function getFormConfigIncludeFileName($script_path,$formname)
   {
     $dir_name = dirname($script_path);

     $conf_file = $dir_name."/".$formname."_conf_inc.php";

     return $conf_file;
   }

   function getConfigIncludeSign()
   {
     return "//{__Simfatic Forms Config File__}";
   }
   
   function get_uploadfiles_folder()
    {
        $upload_folder = '';
        if(!empty($this->file_upload_folder))
        {
            $upload_folder = $this->file_upload_folder;
        }
        else
        {
            $upload_folder = sfm_make_path($this->getFormDataFolder(),"uploads_".$this->formname);
        }
        return $upload_folder;
    }
    function getFormDataFolder()
    {
      return $this->form_file_folder;
    }
   function InitSMTP($host,$uname,$pwd,$port)
   {
     $this->use_smtp = true;
     $this->smtp_host=$host;
     $this->smtp_uname=$uname;
     $this->smtp_pwd=$pwd;
     $this->smtp_port = $port;
   }
   
   function SetPrintPreviewPage($page)
   {
      $this->print_preview_page = $page;
   }
   function GetPrintPreviewPage()
   {
      return $this->print_preview_page;
   }
   
   function setFormDBLogin($host,$uname,$pwd,$database)
   {
    $this->fmdb_host = $host;
    $this->fmdb_username = $uname;
    $this->fmdb_pwd = $pwd;
    $this->fmdb_database = $database;
   }
   function  IsDBSupportRequired()
   {
        if(!empty($this->fmdb_host) && !empty($this->fmdb_username))
        {
            return true;
        }
        return false;
   }
   
   function IsSMTP()
   {
        return $this->use_smtp;
   }
   function GetPreParsedVar($varname)
   {
        return 'sfm_'.$varname.'_parsed';
   }
   function GetDispVar($varname)
   {
        return 'sfm_'.$varname.'_disp';
   }
   function SetLocale($locale_name,$date_format)
   {
        $this->locale_name = $locale_name;
        $this->locale_dateformat = $date_format;
        //TODO: use setLocale($locale_name) or locale_set_default
        //also, use strftime instead of date()
        $this->common_date_format = $this->toPHPDateFormat($date_format);
   }
   
    function toPHPDateFormat($stdDateFormat)
    {
        $map = array(
        'd'=>'j',
        'dd'=>'d',
        'ddd'=>'D',
        'dddd'=>'l',
        'M'=>'n',
        'MM'=>'m',
        'MMM'=>'M',
        'MMMM'=>'F',
        'yy'=>'y',
        'yyyy'=>'Y',
        'm'=>'i',
        'mm'=>'i',
        'h'=>'g',
        'hh'=>'h',
        'H'=>'H',
        'HH'=>'G',
        's'=>'s',
        'ss'=>'s',
        't'=>'A',
        'tt'=>'A'
        );
        
        if(empty($stdDateFormat))
        {
            return 'Y-m-d';
        }
        
        $arr_ret = preg_split('/([^\w]+)/i', $stdDateFormat,-1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

        foreach($arr_ret as $k=>$v)
        {
            if(isset($map[$v]))
            {
                $arr_ret[$k] = $map[$v];
            }
        }
        $php_format = implode($arr_ret); 
        return $php_format;
    }   
}


/* By Grant Burton @ BURTONTECH.COM (11-30-2008): IP-Proxy-Cluster Fix */
function checkIP($ip) 
{
   if (!empty($ip) && ip2long($ip)!=-1 && ip2long($ip)!=false) 
   {
       $private_ips = array (
       array('0.0.0.0','2.255.255.255'),
       array('10.0.0.0','10.255.255.255'),
       array('127.0.0.0','127.255.255.255'),
       array('169.254.0.0','169.254.255.255'),
       array('172.16.0.0','172.31.255.255'),
       array('192.0.2.0','192.0.2.255'),
       array('192.168.0.0','192.168.255.255'),
       array('255.255.255.0','255.255.255.255')
       );

       foreach ($private_ips as $r) 
       {
           $min = ip2long($r[0]);
           $max = ip2long($r[1]);
           if ((ip2long($ip) >= $min) && (ip2long($ip) <= $max)) return false;
       }
       return true;
   }
   else 
   { 
       return false;
   }
}

function determineIP() 
{
   if(isset($_SERVER["HTTP_CLIENT_IP"]) && checkIP($_SERVER["HTTP_CLIENT_IP"])) 
   {
       return $_SERVER["HTTP_CLIENT_IP"];
   }
   if(isset($_SERVER["HTTP_X_FORWARDED_FOR"]))
   {
       foreach (explode(",",$_SERVER["HTTP_X_FORWARDED_FOR"]) as $ip) 
       {
           if (checkIP(trim($ip))) 
           {
               return $ip;
           }
       }
   }
   
   if(isset($_SERVER["HTTP_X_FORWARDED"]) && checkIP($_SERVER["HTTP_X_FORWARDED"])) 
   {
       return $_SERVER["HTTP_X_FORWARDED"];
   } 
   elseif(isset($_SERVER["HTTP_X_CLUSTER_CLIENT_IP"]) && checkIP($_SERVER["HTTP_X_CLUSTER_CLIENT_IP"])) 
   {
       return $_SERVER["HTTP_X_CLUSTER_CLIENT_IP"];
   } 
   elseif(isset($_SERVER["HTTP_FORWARDED_FOR"]) && checkIP($_SERVER["HTTP_FORWARDED_FOR"])) 
   {
       return $_SERVER["HTTP_FORWARDED_FOR"];
   } 
   elseif(isset($_SERVER["HTTP_FORWARDED"]) && checkIP($_SERVER["HTTP_FORWARDED"])) 
   {
       return $_SERVER["HTTP_FORWARDED"];
   } 
   else 
   {
       return $_SERVER["REMOTE_ADDR"];
   }
}

//////GlobalData//////////
class FM_GlobalData
{
   public $get_vars;
   public $post_vars;
   public $server_vars;
   public $files;
   public $formvars;
   public $saved_data_varname;
   public $config;
   public $form_page_submitted;//means a submit button is pressed; need not be the last (final)submission
   public $form_processed;
   public $session;
   

   public function __construct(&$config)
   {
      $this->get_vars   =NULL;
      $this->post_vars =NULL;    
      $this->server_vars   =NULL;
      $this->files=NULL;
      $this->formvars=NULL;
      $this->saved_data_varname="sfm_saved_formdata_var";
      $this->config = &$config;
      $this->form_processed = false;
      $this->form_page_submitted = false;
      $this->form_page_num=-1;
      $this->LoadServerVars();
   }
   
   function LoadServerVars()
   {
        global $HTTP_GET_VARS, $HTTP_POST_VARS, $HTTP_SERVER_VARS,$HTTP_POST_FILES;
        $parser_version = phpversion();
        if ($parser_version <= "4.1.0") 
        {
            $this->get_vars   = $HTTP_GET_VARS;
            $this->post_vars  = $HTTP_POST_VARS;
            $this->server_vars= $HTTP_SERVER_VARS;
            $this->files = $HTTP_POST_FILES;
        }
        if ($parser_version >= "4.1.0")
        {
            $this->get_vars    = $_GET;
            $this->post_vars   = $_POST;
            $this->server_vars= $_SERVER;
            $this->files = $_FILES;
        }   
        $this->server_vars['REMOTE_ADDR'] = determineIP();        
   }
   
   function GetGlobalVars() 
   {
		if($this->is_submission($this->post_vars))
		{
			$this->formvars = $this->get_post_vars();
            $this->form_page_submitted = true;
		}
		elseif($this->is_submission($this->get_vars))
		{
			$this->formvars = $this->get_get_vars();
            $this->form_page_submitted = true;
		}
		else
		{
            $this->form_page_submitted = false;
			$this->formvars = array();
		}
        magicQuotesRemove($this->formvars);
        
        if($this->form_page_submitted)
        {
            $this->CollectInternalVars();
            $this->NormalizeFormVars();
        }

        if(isset($this->formvars[$this->saved_data_varname]))
        {
            $this->LoadFormDataFromSession();
        }
           
        $this->formvars[$this->config->visitor_ip_var] = 
                        $this->server_vars['REMOTE_ADDR'];
						
		$visitor_unique_id = $this->get_unique_id();
        $this->formvars[$this->config->unique_id_var]= $visitor_unique_id; 
        $this->formvars[$this->config->form_page_session_id_var] = md5($visitor_unique_id.uniqid(''));

        $this->formvars[$this->config->submission_time_var]= 
                        date($this->config->getCommonDateTimeFormat());

        $this->formvars[$this->config->submission_date_var] = date($this->config->common_date_format);

        $this->formvars[$this->config->referer_page_var] =  $this->get_form_referer();

        $ua ='';
        if(!empty($this->server_vars['HTTP_USER_AGENT']))
        {
            $ua = $this->server_vars['HTTP_USER_AGENT'];
        }
        else
        {
            $this->server_vars['HTTP_USER_AGENT']='';
        }

        $this->formvars[$this->config->user_agent_var] = $ua;

        $this->formvars[$this->config->visitors_os_var] = $this->DetectOS($ua);

        $this->formvars[$this->config->visitors_browser_var] = $this->DetectBrowser($ua);
   }
   
   function GetCurrentPageNum()
   {
      $page_num = 0;
      if($this->form_page_num >= 0)
      {
         $page_num = $this->form_page_num;
      }   
      return $page_num;
   }
    function NormalizeFormVarsBeforePageDisplay(&$var_map,$page_num)
    {
         $arr_elements = 
            $this->config->element_info->GetElements($page_num);
            
         foreach($arr_elements as $ename => $e)
         {
            $disp_var = $this->config->GetDispVar($ename);
            if(!empty($var_map[$disp_var]))
            {
                $var_map[$ename] = $var_map[$disp_var];
            }
         }    
    }
    
    function CollectInternalVars()
    {
        /*
        TODO: N9UVSWkdQeZF
        Collect & move all internal variables here.
        This way, it won't mess up the formvars vector
        To Do Add:
        sfm_prev_page
        sfm_save_n_close
        sfm_prev_page
        sfm_confirm_edit
        sfm_confirm
        config->form_submit_variable
        sfm_saved_formdata_var
        */
        if(isset($this->formvars['sfm_form_page_num']) && 
        is_numeric($this->formvars['sfm_form_page_num']))
        {
            $this->form_page_num = intval($this->formvars['sfm_form_page_num']);
            unset($this->formvars['sfm_form_page_num']);
        }
    }
    
    function NormalizeFormVars()
    {
     //for boolean inputs like checkbox, the absense of 
     //the element means false. Explicitely setting this false here
     //to help in later form value processing
         $arr_elements = 
            $this->config->element_info->GetElements($this->GetCurrentPageNum());
            
         foreach($arr_elements as $ename => $e)
         {
            $preparsed_var = $this->config->GetPreParsedVar($ename);
            if(isset($this->formvars[$preparsed_var]))
            {
                $disp_var = $this->config->GetDispVar($ename);
                $this->formvars[$disp_var] = $this->formvars[$ename];
                $this->formvars[$ename] = $this->formvars[$preparsed_var];
            }
            if(isset($this->formvars[$ename])){continue;}
            
            switch($e['type'])
            {
                case 'single_chk':
                {
                    $this->formvars[$ename] = false;
                    break;
                }
                case 'chk_group':
                case 'multiselect':
                {
                    $this->formvars[$ename] = array();
                    break;
                }
                default:
                {
                    $this->formvars[$ename]='';
                }
            }
         }
    }
    
	function is_submission($var_array)
	{
		if(empty($var_array)){ return false;}
		
		if(isset($var_array[$this->config->form_submit_variable])//full submission
			|| isset($var_array['sfm_form_page_num']))//partial- page submission
		{
			return true;
		}
		return false;
	}
    
    function RecordVariables()
    {
     if(!empty($this->get_vars['sfm_from_iframe']))
     {
         $this->session['sfm_from_iframe']= $this->get_vars['sfm_from_iframe'];
     }
     
     $this->session['sfm_referer_page'] = $this->get_referer();
    }
    
    function GetVisitorUniqueKey()
    {
      $seed = $this->config->get_form_id().
               $this->server_vars['SERVER_NAME'].
               $this->server_vars['REMOTE_ADDR'].
               $this->server_vars['HTTP_USER_AGENT'];
      return md5($seed);
    }
    function get_unique_id()
	{
	    if(empty($this->session['sfm_unique_id']))
        {
			$this->session['sfm_unique_id'] = 
				md5($this->GetVisitorUniqueKey().uniqid('',true));
		}
		return  $this->session['sfm_unique_id'];
	}
    function get_form_referer()
    {
        if(isset($this->session['sfm_referer_page']))
        {
           return  $this->session['sfm_referer_page'];
        }
        else
        {
            return $this->get_referer();
        }
    }
    function InitSession()
    {
        $id=$this->config->get_form_id();
        if(!isset($_SESSION[$id]))
        {
            $_SESSION[$id]=array();
        }
        $this->session = &$_SESSION[$id];
    }
    
    function DestroySession()
    {
        $id=$this->config->get_form_id();
        unset($_SESSION[$id]);
    } 
    
    function RemoveSessionValue($name)
    {
        unset($_SESSION[$this->config->get_form_id()][$name]);
    }
    
    function RecreateSessionValues($arr_session)
    {
      foreach($arr_session as $varname => $values)
      {
         $this->session[$varname] = $values;
      }        
    }
    function SetFormVar($name,$value)
    {
        $this->formvars[$name] = $value;
    }
    
    function LoadFormDataFromSession()
    {
        $varname = $this->formvars[$this->saved_data_varname];

         if(isset($this->session[$varname]))
         {
            $this->formvars = 
               array_merge($this->formvars,$this->session[$varname]);

            unset($this->session[$varname]);
            unset($this->session[$this->saved_data_varname]);
         }
    }

    function SaveFormDataToSession()
    {
        $varname = "sfm_form_var_".rand(1,1000)."_".rand(2,2000);

        $this->session[$varname] = $this->formvars;

        unset($this->session[$varname][$this->config->form_submit_variable]);

        return $varname;
    }
    
    function get_post_vars()
    {
        return $this->post_vars;
    }
    function get_get_vars()
    {
        return $this->get_vars;
    }

    function get_php_self() 
    {
        $from_iframe = isset($this->session['sfm_from_iframe']) ?  intval($this->session['sfm_from_iframe']):0;
        $sid=0;
        if($from_iframe)
        {
            $sid =  session_id();
        }
        else
        {
            if(empty($this->session['sfm_rand_sid']))
            {
                $this->session['sfm_rand_sid'] = rand(1,9999);
            }
            $sid = $this->session['sfm_rand_sid'];
        }
        $url = $this->server_vars['PHP_SELF']."?sfm_sid=$sid";
        return $url;
    }

    function get_referer()
    {
        if(isset($this->server_vars['HTTP_REFERER']))
        {
            return $this->server_vars['HTTP_REFERER'];
        }
        else
        {
            return '';
        }
    }
    
    function SetFormProcessed($processed)
    {
      $this->form_processed = $processed;
    }
    
    function IsFormProcessingComplete()
    {
      return $this->form_processed;
    }
    
    function IsButtonClicked($button_name)
    {
        if(isset($this->formvars[$button_name]))
        {
            return true;
        }
        if(isset($this->formvars[$button_name."_x"])||
           isset($this->formvars[$button_name."_y"]))
        {
            if($this->formvars[$button_name."_x"] == 0 &&
            $this->formvars[$button_name."_y"] == 0)
            {//Chrome & safari bug
                return false;
            }
         return true;
        }
      return false;
    }
    function ResetButtonValue($button_name)
    {
        unset($this->formvars[$button_name]);
        unset($this->formvars[$button_name."_x"]);
        unset($this->formvars[$button_name."_y"]);
    }

    function DetectOS($user_agent)
    {
        //code by Andrew Pociu
        $OSList = array
        (
            'Windows 3.11' => 'Win16',

            'Windows 95' => '(Windows 95)|(Win95)|(Windows_95)',

            'Windows 98' => '(Windows 98)|(Win98)',

            'Windows 2000' => '(Windows NT 5\.0)|(Windows 2000)',

            'Windows XP' => '(Windows NT 5\.1)|(Windows XP)',

            'Windows Server 2003' => '(Windows NT 5\.2)',

            'Windows Vista' => '(Windows NT 6\.0)',

            'Windows 7' => '(Windows NT 7\.0)|(Windows NT 6\.1)',
            
            'Windows 8' => '(Windows NT 6\.2)',

            'Windows NT 4.0' => '(Windows NT 4\.0)|(WinNT4\.0)|(WinNT)|(Windows NT)',

            'Windows ME' => '(Windows 98)|(Win 9x 4\.90)|(Windows ME)',

            'Open BSD' => 'OpenBSD',

            'Sun OS' => 'SunOS',

            'Linux' => '(Linux)|(X11)',

            'Mac OS' => '(Mac_PowerPC)|(Macintosh)',

            'QNX' => 'QNX',

            'BeOS' => 'BeOS',

            'OS/2' => 'OS/2',

            'Search Bot'=>'(nuhk)|(Googlebot)|(Yammybot)|(Openbot)|(Slurp)|(MSNBot)|(Ask Jeeves/Teoma)|(ia_archiver)'
        );

        foreach($OSList as $CurrOS=>$Match)
        {
            if (preg_match("#$Match#i", $user_agent))
            {
                break;
            }
        }

        return $CurrOS;        
    }


    function DetectBrowser($agent) 
    {
        $ret ="";
        $browsers = array("firefox", "msie", "opera", "chrome", "safari",
                            "mozilla", "seamonkey",    "konqueror", "netscape",
                            "gecko", "navigator", "mosaic", "lynx", "amaya",
                            "omniweb", "avant", "camino", "flock", "aol");

        $agent = strtolower($agent);
        foreach($browsers as $browser)
        {
            if (preg_match("#($browser)[/ ]?([0-9.]*)#", $agent, $match))
            {
                $br = $match[1];
                $ver = $match[2];
                if($br =='safari' && preg_match("#version[/ ]?([0-9.]*)#", $agent, $match))
                {
                    $ver = $match[1];
                }
                $ret = ($br=='msie')?'Internet Explorer':ucfirst($br);
                $ret .= " ". $ver;
                break ;
            }
        }
        return $ret;
    }

}

/////Logger/////
class FM_Logger
{
   private $config;
   private $log_file_path;
   private $formname;
   private $log_filename;
   private $whole_log;
   private $is_enabled;
   private $logfile_size;
   private $msg_log_enabled;
   private $log_source;
   

   public function __construct(&$config,$formname)
   {
      $this->config = &$config;
      $this->formname = $formname;
      $this->log_filename="";
      $this->whole_log="";
      $this->is_enabled = false;
      $this->log_flushed = false;
      $this->logfile_size=100;//In KBs
      $this->msg_log_enabled = true;
      $this->log_source = '';
   }   
   
   function EnableLogging($enable)
   {
      $this->is_enabled = $enable;
   }
   function SetLogSource($logSource)
   {
    $this->log_source = $logSource;
   }
   function CreateFileName()
   {
     $ret=false;
     $filename ="";
     if(strlen($this->log_filename)> 0)
     {
         $filename = $this->log_filename;
     }
     else
     if(strlen($this->config->get_form_id())>0)
     {
         $form_id_part = substr($this->config->get_form_id(),0,8);

         $filename = $this->formname.'-'.$form_id_part.'-log.php';
     }
     else
     {
         return false;
     }

     if(strlen($this->config->form_file_folder)>0)
     {
         $this->log_file_path = sfm_make_path($this->config->form_file_folder,
                                     $filename);
         $ret = true;
     }
     else
     {
         $this->log_file_path ="";
         $ret=false;
     }
     return $ret;
   }
   
   function LogString($string,$type)
   {
      $bret = false;
      $t_log = "\n";
      $t_log .= $_SERVER['REMOTE_ADDR']."|";

      $t_log .= date("Y-m-d h:i:s A|");
      $t_log .= $this->log_source.'|';
      $t_log .= "$type| ";
      $string = str_replace("\n","\\n",$string);      
      $t_log .= $string;

      if($this->is_enabled && $this->config->debug_mode)
      {
         $bret = $this->writeToFile($t_log);
      }

      $this->whole_log .= $t_log;
      return $bret;
   }

    function FlushLog()
    {
        if($this->is_enabled && 
        !$this->log_flushed &&
        !$this->config->debug_mode)
        {
            $this->writeToFile($this->get_log());
            $this->log_flushed = true;
        }
    }

    function print_log()
    {
        echo $this->whole_log;
    }

   function get_log()
   {
      return $this->whole_log;
   }

    function get_log_file_path()
    {
        if(strlen($this->log_file_path)<=0)
        {
            if(!$this->CreateFileName())
            {
                return "";
            }
        }
        return $this->log_file_path;
    }
   
    function writeToFile($t_log)
    {
        $this->get_log_file_path();

        if(strlen($this->log_file_path)<=0){ return false;}

        $fp =0;
        $create_file=false;

        if(file_exists($this->log_file_path))
        {
            $maxsize= $this->logfile_size * 1024;
            if(filesize($this->log_file_path) >= $maxsize)
             {
                $create_file = true;
             }
        }
        else
        {
           $create_file = true;
        }

        $ret = true;
        $file_maker = new SecureFileMaker($this->GetFileSignature());
        if(true == $create_file)
        {
            $ret = $file_maker->CreateFile($this->log_file_path,$t_log);
        }
        else
        {
            $ret = $file_maker->AppendLine($this->log_file_path,$t_log);
        }
      
      return $ret;
    }

    function GetFileSignature()
    {
        return "--Simfatic Forms Log File--";
    }

   function LogError($string)
   {
      return $this->LogString($string,"error");
   }
   
   function LogInfo($string)
   {
      if(false == $this->msg_log_enabled)     
      {
         return true;
      }
      return $this->LogString($string,"info");
   }
}

class FM_ErrorHandler
{
   private $logger;
   private $config;
   private $globaldata;
   private $formname;
   private $sys_error;
   private $formvars;
   private $common_objs;

   public $disable_syserror_handling;
   
    public function __construct(&$logger,&$config,&$globaldata,$formname,&$common_objs)
    {
      $this->logger = &$logger;
      $this->config = &$config;
      $this->globaldata = &$globaldata;
      $this->formname  = $formname;
      $this->sys_error="";
      $this->enable_error_formpagemerge=true;
      $this->common_objs = &$common_objs;
    }
   
   function SetFormVars(&$formvars)
   {
      $this->formvars = &$formvars;
   }

    function InstallConfigErrorCatch()
    {
        set_error_handler(array(&$this, 'sys_error_handler'));
    }

   function DisableErrorFormMerge()
   {
    $this->enable_error_formpagemerge = false;
   }
   
   function GetLastSysError()
   {
      return $this->sys_error;
   }

   function IsSysError()
   {
      if(strlen($this->sys_error)>0){return true;}
      else { return false;}
   }
   
   function GetSysError()
   {
      return $this->sys_error;
   }

   function sys_error_handler($errno, $errstr, $errfile, $errline)
   {
        if(defined('E_STRICT') && $errno == E_STRICT)
        {
            return true;
        }
        switch($errno)
        {
            case E_ERROR:
            case E_PARSE:
            case E_CORE_ERROR:
            case E_COMPILE_ERROR:
            case E_USER_ERROR:
            {
                $this->sys_error = "Error ($errno): $errstr\n file:$errfile\nline: $errline \n\n";

                if($this->disable_syserror_handling == true)
                {
                 return false;
                }
                $this->HandleConfigError($this->sys_error);
                exit;
                break;
            }
           default:
           {
                $this->logger->LogError("Error/Warning reported: $errstr\n file:$errfile\nline: $errline \n\n");
           }
        }
        return true;
   }
    
   function ShowError($error_code,$show_form=true)
   {
      if($show_form)
      {
         $this->DisplayError($error_code);
      }
      else
      {
         echo "<html><head>Error</head><body><h3>$error_code</h3></body></html>";
      }
   }
   function ShowErrorEx($error_code,$error_extra_info)
   {
      $error_extra_info = trim($error_extra_info);
      $this->DisplayError($error_code."\n".$error_extra_info);
   }
    function ShowInputError($error_hash,$formname)
    {
        $this->DisplayError("",$error_hash,$formname);
    }
    function NeedSeperateErrorPage($error_hash)
    {
        if(null == $error_hash)
        {
            if(false === strpos($this->config->form_page_code,
                $this->config->error_display_variable))
            {
                return true;
            }
        }

        return false;
    }

   function DisplayError($str_error,$error_hash=null,$formname="")
   {
      $str_error = trim($str_error);
      $this->logger->LogError($str_error);

      if(!$this->enable_error_formpagemerge)
      {
         $this->sys_error = $str_error;
         return;
      }        

      $str_error = nl2br($str_error);  

      $var_map = array(
                 $this->config->error_display_variable => $str_error
                 );

      
      
      if(null != $error_hash)
      {
         if($this->config->show_errors_single_box)
         {
             $this->CombineErrors($var_map,$error_hash);
         }   
         else
         {
             foreach($error_hash as $inpname => $inp_err)
             {
                 $err_var = $formname."_".$inpname."_errorloc";
                 $var_map[$err_var] = $inp_err;
             }
         }
      }
      
      
      if(!isset($this->common_objs->formpage_renderer))
      {
         $this->logger->LogError('Form page renderer not initialized');
      }
      else
      {
         $this->logger->LogInfo("Error display: Error map ".var_export($var_map,TRUE));
         $this->common_objs->formpage_renderer->DisplayCurrentPage($var_map);
      }

   }

    function CombineErrors(&$var_map,&$error_hash)
    {
        $error_str='';
        foreach($error_hash as $inpname => $inp_err)
        {
            $error_str .="\n<li>".$inp_err;
        }        

        if(!empty($error_str))
        {
            $error_str="\n<ul>".$error_str."\n</ul>";
        }

        $var_map[$this->config->error_display_variable]=
            $var_map[$this->config->error_display_variable].$error_str;

    }

   function EmailError($error_code)
   {
      $this->logger->LogInfo("Sending Error Email To: ".$this->config->error_mail_to);    
      $mailbody = sprintf(_("Error occured in form %s.\n\n%s\n\nLog:\n%s"),$this->formname,$error_code,$this->logger->get_log());
      $subj =  sprintf(_("Error occured in form %s."),$this->formname);

      $from = empty($this->config->from_addr) ? 'form.error@simfatic-forms.com' : $this->config->from_addr;
      $from = $this->formname.'<'.$from.'>';
      @mail($this->config->error_mail_to, $subj, $mailbody, 
         "From: $from");
   }  

   function NotifyError($error_code)
   {
        $this->logger->LogError($error_code);
        if(strlen($this->config->error_mail_to)>0)
        {
            $this->EmailError($error_code);
        }        
   }

   function HandleConfigError($error_code,$extrainfo="")
   {
        $logged = $this->logger->LogError($error_code);
        
        if(strlen($this->config->error_mail_to)>0)
        {
            $this->EmailError($error_code);
        }
        
        if(!$this->enable_error_formpagemerge)
        {
         $this->sys_error = "$error_code \n $extrainfo";
         return;
        }        
        $disp_error = $this->FormatError($logged,$error_code,$extrainfo);

        $this->DisplayError($disp_error);
   }
   
   function FormatError($logged,$error_code,$extrainfo)
   {
        $disp_error = "<p align='left'>";
        $disp_error .= _("There was a configuration error.");

        $extrainfo .= "\n server: ".$_SERVER["SERVER_SOFTWARE"];

        $error_code_disp ='';
        $error_code_disp_link ='';

        if($this->config->debug_mode)
        {
            $error_code_disp = $error_code.$extrainfo;
        }
        else
        {
            if($logged)
            {
                $error_code_disp .= _("The error is logged.");
            }
            else
            {
                $error_code_disp .= _("Could not log the error");
            }

            $error_code_disp .= "<br/>"._("Enable debug mode ('Form processing options' page) for displaying errors.");
        }

        $link = sprintf(_(" <a href='http://www.simfatic.com/forms/troubleshoot/checksol.php?err=%s'>Click here</a> for troubleshooting information."),
                        urlencode($error_code_disp));

        $disp_error .= "<br/>".$error_code_disp."<br/>$link";

        $disp_error .= "</p>";    
        
        return $disp_error;
   }   
}


class FM_FormFiller 
{
   private $filler_js_code;
   private $config;
   private $logger;

   public function __construct(&$config,&$logger)
   {
      $this->filler_js_code="";
      $this->form_filler_variable = "sfm_fill_the_form";
      $this->logger = &$logger;
      $this->config = &$config;
   }
   function GetFillerJSCode()
   {
      return $this->filler_js_code;
   }
   function GetFormFillerScriptEmbedded($formvars)
   {
      $ret_code="";
      if($this->CreateFormFillerScript($formvars))
      {
         $self_script = htmlentities($this->globaldata->get_php_self());
         $ret_code .= "<script language='JavaScript' src='$self_script?sfm_get_ref_file=form-filler-helper.js'></script>\n";
      
         $ret_code .= "<script language='JavaScript'>\n";
         $ret_code .= "\n$util_code\n";
         $ret_code .= $this->filler_js_code;
         $ret_code .= "\n</script>";
      }
      return $ret_code;
   }

   function CreateServerSideVector($formvars,&$outvector)
   {
      foreach($formvars as $name => $value)
      {
         /*if(!$this->config->element_info->IsElementPresent($name)||
         !isset($value))
         {
            continue; 
         }*/
         
         switch($this->config->element_info->GetType($name))
         {
            case "text":
            case "multiline":
            case "decimal":
            case "calcfield":
            case "datepicker":
            case "hidden":
               {
                  $outvector[$name] = $value;
                  break;
               }
            case "single_chk":
            case "radio_group":
            case "multiselect":
            case "chk_group":
               {
                  $this->SetGroupItemValue($outvector,$name,$value,"checked");
                  break;
               } 
            case "listbox":
               {
                  $this->SetGroupItemValue($outvector,$name,$value,"selected");
                  break;
               }
            default:
               {
                  $outvector[$name] = $value;
                  break;               
               }
         }//switch
      }//foreach
   }

   function SetGroupItemValue(&$outvector,$name,$value,$set_val)
   {
      if(is_array($value))
      {
         foreach($value as $val_item)
         {  
            $entry = md5($name.$val_item);
            $outvector[$entry]=$set_val;
         }
         $outvector[$name] = implode(',',$value);
      }
      else
      {
         $entry = md5($name.$value);
         $outvector[$entry]=$set_val;
         $outvector[$name] = $value;
      }
      
   }

   function CreateFormFillerScript($formvars)
   {
      
      $func_body="";
      foreach($formvars as $name => $value)
      {
         if(!$this->config->element_info->IsElementPresent($name)||
         !isset($value))
         {
            continue; 
         }
         switch($this->config->element_info->GetType($name))
         {
            case "text":
            case "multiline":
                case "decimal":
                case "calcfield":
                case "datepicker":
               {
                  $value = str_replace("\n","\\n",$value);
                  $value = str_replace("'","\\'",$value);
                  $func_body .= "formobj.elements['$name'].value = '$value';\n";
                  break;
               }
            case "single_chk":
               {
                  if(strlen($value) > 0 && strcmp($value,"off")!=0)
                  {
                     $func_body .= "formobj.elements['$name'].checked = true;\n";
                  }
                  break;
               }
            
            case "multiselect":
            case "chk_group":
               {
                  $name_tmp="$name"."[]";
                  foreach($value as $item)
                  {  
                     $func_body .= "SFM_SelectChkItem(formobj.elements['$name_tmp'],'$item');\n";
                  }
                  break;
               }
            case "radio_group":
               {
                  $func_body .= "SFM_SelectChkItem(formobj.elements['$name'],'$value');\n";
                  break;
               }
            case "listbox":
               {
                  if(is_array($value))
                  {
                     $name_tmp="$name"."[]";
                     foreach($value as $item)
                     {
                        $func_body .= "SFM_SelectListItem(formobj.elements['$name_tmp'],'$item');\n";
                     }
                  }
                  else
                  {
                     $func_body .= "formobj.elements['$name'].value = '$value';\n";
                  }
                  break;
               }
         }
      }//foreach

      $bret=false;
      $this->filler_js_code="";
      if(strlen($func_body)>0)
      {
         $function_name = "sfm_".$this->formname."formfiller"; 

         $this->filler_js_code .= "function $function_name (){\n";
         $this->filler_js_code .= " var formobj= document.forms['".$this->formname."'];\n";
         $this->filler_js_code .= $func_body;
         $this->filler_js_code .= "}\n";
         $this->filler_js_code .= "$function_name ();";
         $bret= true;
      }
      return $bret;
   }

}


class FM_FormVarMx
{
    private $logger;
    private $config;
    private $globaldata;
    private $formvars;
    private $html_vars;

    public function __construct(&$config,&$logger,&$globaldata)
    {
      $this->config = &$config;
      $this->logger = &$logger;
      $this->globaldata = &$globaldata;
      $this->formvars = &$this->globaldata->formvars;
      $this->html_vars = array();
    }
     
    function AddToHtmlVars($html_var)
    {
        $this->html_vars[] = $html_var;
    }
    function IsHtmlVar($var)
    {
        return (false === array_search($var,$this->html_vars)) ? false:true;
    }
    function CreateFieldMatrix($html=true)
    {
        $datamap = $this->formvars;
        foreach($datamap as $name => $value)
        {
            $value = $this->GetFieldValueAsString($name,/*$use_disp_var*/true);
            if($html && (false == $this->IsHtmlVar($name)) )
            {
                $datamap[$name] = nl2br(htmlentities($value,ENT_QUOTES,"UTF-8"));
            }
            else
            {
                $datamap[$name] = $value;
            }
        }
        
        if(true == $this->config->enable_auto_field_table)
        {
            $datamap['_sfm_non_blank_field_table_'] = $this->CreateFieldTable($datamap);
        }
        
        return $datamap;
    }
    
    function CreateFieldTable(&$datamap)
    {
        $ret_table ="<div class='sfm_table_container'><table cellspacing='0' cellpadding='5'><tbody>";
         $arr_elements = 
            $this->config->element_info->GetAllElements();
         foreach($arr_elements as $ename => $e)
         {
            if(isset($datamap[$ename]) && strlen($datamap[$ename]) > 0 )
            {
               $value = $datamap[$ename];
               
               $ret_table .= "<tr><td class='FieldName'>$ename</td><td class='FieldValue'>$value</td></tr>\n";
            }
         }    
         $ret_table .= "</tbody></table></div>";
         return $ret_table;
    }
    
    function GetFieldValueAsString($var_name,$use_disp_var=false)
    {
        $ret_val ='';
        if(isset($this->formvars[$var_name]))
        {
            $ret_val = $this->formvars[$var_name];
        }

        if(is_array($ret_val))
        {
            $ret_val = implode($this->config->array_disp_seperator,$ret_val);
        }
        else if($use_disp_var && $this->config->element_info->IsUsingDisplayVariable($var_name))
        {
            $disp_var_name = sfm_get_disp_variable($var_name);
            if(!empty($this->formvars[$disp_var_name]))
            {
                $ret_val = $this->formvars[$disp_var_name];
            }
        }
        return $ret_val;
    }    
}

class FM_FormPageRenderer
{
   private $config;
   private $logger;
   private $globaldata;
   private $arr_form_pages;
   private $security_monitor;
   private $ext_module;
   
   public function __construct(&$config,&$logger,&$globaldata,&$security_monitor)
   {
      $this->config = &$config;
      $this->logger = &$logger;
      $this->globaldata = &$globaldata;
      $this->security_monitor = &$security_monitor;
      
      $this->arr_form_pages = array();
      $this->ext_module = null;
   }
   
   function InitExtensionModule(&$extmodule)
   {
        $this->ext_module = &$extmodule;
   }
   
   function SetFormPage($page_num,$templ,$condn='')
   {
      $this->arr_form_pages[$page_num] = array();
      $this->arr_form_pages[$page_num]['templ'] = $templ;
      $this->arr_form_pages[$page_num]['condn'] = $condn;
   }
   
   function GetNumPages()
   {
      return count($this->arr_form_pages);
   }
   
   function GetCurrentPageNum()
   {
      return $this->globaldata->GetCurrentPageNum();
   }
   function GetLastPageNum()
   {
      return ($this->GetNumPages()-1);
   }
   
   function IsPageNumSet()
   {
      return ($this->globaldata->form_page_num >= 0);
   }
   
   function DisplayCurrentPage($addnl_vars=NULL)
   {
      $this->DisplayFormPage($this->getCurrentPageNum(),$addnl_vars);
   }
   
   function DisplayNextPage($addnl_vars,&$display_thankyou)
   {
      if($this->IsPageNumSet() && 
         $this->getCurrentPageNum() < $this->GetLastPageNum())
      {
         $nextpage = $this->GetNextPageNum($addnl_vars);
         
         if($nextpage < $this->GetNumPages())
         {
            $this->DisplayFormPage($nextpage,$addnl_vars);
            return;
         }
         else
         {
            $display_thankyou =true;
            return;
         }
      }
      
      $this->DisplayFormPage(0,$addnl_vars);
   }
   
   function DisplayFirstPage($addnl_vars)
   {
        $this->DisplayFormPage(0,$addnl_vars);
   }
   
   function DisplayPrevPage($addnl_vars)
   {
      if($this->IsPageNumSet())
      {
         $curpage = $this->getCurrentPageNum();
         
         $prevpage = $curpage-1;
         
         for(;$prevpage>=0;$prevpage--)
         {
            if($this->TestPageCondition($prevpage,$addnl_vars))
            {
               break;
            }
         }
         
         if($prevpage >= 0)
         {
            $this->DisplayFormPage($prevpage,$addnl_vars);   
            return;
         }
      }
      
      $this->DisplayFormPage(0,$addnl_vars);   
   }   
   
   function GetNextPageNum($addnl_vars)
   {
      $nextpage = 0;
      
      if($this->IsPageNumSet() )
      {
         $nextpage =  $this->getCurrentPageNum() + 1;
         
         for(;$nextpage < $this->GetNumPages(); $nextpage ++)
         {
            if($this->TestPageCondition($nextpage,$addnl_vars))
            {
                  break;
            }
         }
      }    
      return $nextpage;
   }
   
   function IsNextPageAvailable($addnl_vars)
   {
      if($this->GetNextPageNum($addnl_vars) < $this->GetNumPages())
      {
         return true;
      }
      return false;
   }
   
   function TestPageCondition($pagenum,$addnl_vars)
   {
      $condn = $this->arr_form_pages[$pagenum]['condn'];
      
      if(empty($condn))
      {
         return true;
      }
      elseif(sfm_validate_multi_conditions($condn,$addnl_vars))
      {
         return true;
      }
      $this->logger->LogInfo("TestPageCondition condn: returning false");
      return false;
   }
   
   function DisplayFormPage($page_num,$addnl_vars=NULL)
   {
      $fillerobj = new FM_FormFiller($this->config,$this->logger);
      
      $var_before_proc = array();
      if(!is_null($addnl_vars))
      {
         $var_before_proc = array_merge($var_before_proc,$addnl_vars);
      }
      $var_before_proc = array_merge($var_before_proc,$this->globaldata->formvars);
      
      $this->globaldata->NormalizeFormVarsBeforePageDisplay($var_before_proc,$page_num);
      
      if($this->ext_module && false === $this->ext_module->BeforeFormDisplay($var_before_proc,$page_num))
      {
         $this->logger->LogError("Extension Module 'BeforeFormDisplay' returned false! ");
         return false;
      }
      
      
      $var_map = array();
      $fillerobj->CreateServerSideVector($var_before_proc,$var_map);

      $var_map[$this->config->self_script_variable]  = $this->globaldata->get_php_self();
      
      $var_map['sfm_css_rand'] = rand();
      $var_map[$this->config->var_cur_form_page_num] = $page_num+1;
      
      $var_map[$this->config->var_form_page_count] = $this->GetNumPages();
      
      $var_map[$this->config->var_page_progress_perc] = ceil((($page_num)*100)/$this->GetNumPages());
      
      $this->security_monitor->AddSecurityVariables($var_map);

      $page_templ='';
      if(!isset($this->arr_form_pages[$page_num]))
      {
         $this->logger->LogError("Page $page_num not initialized");
      }
      else
      {
        $page_templ = $this->arr_form_pages[$page_num]['templ'];
      }
      
      ob_clean();
      $merge = new FM_PageMerger();
      
      convert_html_entities_in_formdata(/*skip var*/$this->config->error_display_variable,$var_map,/*nl2br*/false);
      if(false == $merge->Merge($page_templ,$var_map))
      {
         return false;
      }                
      $strdisp = $merge->getMessageBody();
      echo $strdisp;
      return true;    
   }
}

class FM_SecurityMonitor
{
   private $config;
   private $logger;
   private $globaldata;
   private $banned_ip_arr;
   private $session_input_id;
   
   
   public function __construct(&$config,&$logger,&$globaldata)
   {
      $this->config = &$config;
      $this->logger = &$logger;
      $this->globaldata = &$globaldata;   
      $this->banned_ip_arr = array();
      $this->session_input_id = '_sfm_session_input_id_';
      $this->session_input_value = '_sfm_session_input_value_';
   }
   
   function AddBannedIP($ip)
   {
      $this->banned_ip_arr[] = $ip;
   }
   
   function IsBannedIP()
   {
      $ip = $this->globaldata->server_vars['REMOTE_ADDR'];
      
      $n = count($this->banned_ip_arr);
      
      for($i=0;$i<$n;$i++)
      {
         if(sfm_compare_ip($this->banned_ip_arr[$i],$ip))
         {
            $this->logger->LogInfo("Banned IP ($ip) attempted the form. Returned error.");
            return true;
         }
      }
      return false;
   
   }
   
   function GetFormIDInputName()
   {
      if(!empty($this->globaldata->session[$this->session_input_id]))
      {
        return $this->globaldata->session[$this->session_input_id];
      }
      $idname = $this->globaldata->GetVisitorUniqueKey();
      $idname = str_replace('-','',$idname);
      $idname = 'id_'.substr($idname,0,20);
      
      $this->globaldata->session[$this->session_input_id] = $idname;
      return $idname;
   }
   
   function GetFormIDInputValue()
   {
      if(!empty($this->globaldata->session[$this->session_input_value]))
      {
        return $this->globaldata->session[$this->session_input_value];
      }
      $value = $this->globaldata->GetVisitorUniqueKey();
      
      $value = substr(md5($value),5,25);
      
      $this->globaldata->session[$this->session_input_value] = $value;
      
      return $value;
   }
   
   function AddSecurityVariables(&$varmap)
   {
      $varmap[$this->config->form_id_input_var] = $this->GetFormIDInputName();
      $varmap[$this->config->form_id_input_value] = $this->GetFormIDInputValue();
   }
   
   function Validate($formdata)
   {
      $formid_input_name = $this->GetFormIDInputName();
      
      $this->logger->LogInfo("Form ID input name: $formid_input_name ");
      
      if($this->IsBannedIP())
      {
         $this->logger->LogInfo("Is Banned IP");
         return false;
      }
      if(true == $this->config->bypass_spammer_validations)
	  {
			return true;
	  }
      if(!isset($formdata[$formid_input_name]))
      {
         $this->logger->LogError("Form ID input is not set");
         return false;
      }
      elseif($formdata[$formid_input_name] != $this->GetFormIDInputValue())
      {
         $this->logger->LogError("Spammer attempt foiled! Form ID input value not correct. expected:".
            $this->GetFormIDInputValue()." Received:".$formdata[$formid_input_name]);
            
         return false;
      }
      
      if(!empty($this->config->hidden_input_trap_var) && 
         !empty($formdata[$this->config->hidden_input_trap_var]) )
      {
         $this->logger->LogError("Hidden input trap value is not empty. Spammer attempt foiled!");
         return false;
      }
     $this->logger->LogInfo("Sec Monitor Validate returning true");
     return true;
   }
}

class FM_Module
{
    protected $config;
    protected $formvars;
    protected $logger;
    protected $globaldata;
    protected $error_handler;
    protected $formname;
    protected $ext_module;
    protected $common_objs;

    public function __construct()
    {
    }

    function Init(&$config,&$formvars,&$logger,&$globaldata,
         &$error_handler,$formname,&$ext_module,&$common_objs)
    {
      $this->config = &$config;
      $this->formvars = &$formvars;
      $this->logger = &$logger;
      $this->globaldata =&$globaldata;
      $this->error_handler = &$error_handler;
      $this->formname = $formname;
      $this->ext_module = &$ext_module;
      $this->common_objs = &$common_objs;
      $this->OnInit();
    }

    function OnInit()
    {
    }
    
    function AfterVariablessInitialized()
    {
        return true;
    }

    function Process(&$continue)
    {
        return true;
    }

    function ValidateInstallation(&$app_command_obj)
    {
        return true;
    }
    
    function DoAppCommand($cmd,$val,&$app_command_obj)
    {
        //Return true to indicate 'handled'
        return false;
    }
    
    function Destroy()
    {

    }
    function getFormDataFolder()
    {
      if(strlen($this->config->form_file_folder)<=0)
      {
         $this->error_handler->HandleConfigError("Config Error: No Form data folder is set; but tried to access form data folder");
         exit;
      }
      return $this->config->form_file_folder;
    }
}

///////PageMerger////////////////////
class FM_PageMerger
{
   var $message_body;
   
   public function __construct()
   {
      $this->message_body="";
   }

   function Merge($content,$variable_map)
   {
      $this->message_body = $this->mergeStr($content,$variable_map);
      
      return(strlen($this->message_body)>0?true:false);
   }  
   
   function mergeStr($template,$variable_map)
   {
        $ret_str = $template;
        $N = 0;
        $m = preg_match_all("/%([\w]*)%/", $template,$matches,PREG_PATTERN_ORDER);

        if($m > 0 || count($matches) > 1)
        {
            $N = count($matches[1]);
        }

        $source_arr = array();
        $value_arr = array();

        for($i=0;$i<$N;$i++)
        {
            $val = "";
            $key = $matches[1][$i];
            if(isset($variable_map[$key]))
            {
                if(is_array($variable_map[$key]))
                {
                    $val = implode(",",$variable_map[$key]);
                }
            else
                {
                    $val = $variable_map[$key];
                }
            }
            else
            if(strlen($key)<=0)
            {
                $val ='%';
            }
            $source_arr[$i] = $matches[0][$i];
            $value_arr[$i] = $val;
        }
        
        $ret_str = str_replace($source_arr,$value_arr,$template);
        
        return $ret_str;
   }

   function mergeArray(&$arrSource, $variable_map)
   {
        foreach($arrSource as $key => $value)
        {
            if(!empty($value) && false !== strpos($value,'%'))
            {
                $arrSource[$key] = $this->mergeStr($value,$variable_map);
            }
        }
   }
   function getMessageBody()
   {
      return $this->message_body;
   }
}

class FM_ExtensionModule
{
    protected $config;
    protected $formvars;
    protected $logger;
    protected $globaldata;
    protected $error_handler;
    protected $formname;

    public function __construct()
    {
        
    }

    function Init(&$config,&$formvars,&$logger,&$globaldata,&$error_handler,$formname)
    {
        $this->config = &$config;
        $this->formvars = &$formvars;
        $this->logger = &$logger;
        $this->globaldata =&$globaldata;
        $this->error_handler = &$error_handler;
        $this->formname = $formname;
    }
    function BeforeStartProcessing()
    {
        return true;
    }
    function AfterVariablessInitialized()
    {
        return true;
    }
   function BeforeFormDisplay(&$formvars,$pagenum=0)
   {
      return true;
   }
    function LoadDynamicList($listname,&$rows)
    {
        //return true if this overload loaded the list
        return false;
    }
    function LoadCascadedList($listname,$parent,&$rows)
    {
        return false;
    }
    function DoValidate(&$formvars, &$error_hash)
    {
        return true;
    }
    
    function DoValidatePage(&$formvars, &$error_hash,$page)
    {
        return true;
    }
    
    function PreprocessFormSubmission(&$formvars)
    {
        return true;
    }
    
   function BeforeConfirmPageDisplay(&$formvars)
   {
      return true;      
   }

   function FormSubmitted(&$formvars)
   {
      return true;
   }

    function BeforeThankYouPageDisplay(&$formvars)
    {
        return true;
    }
    
    function BeforeSendingFormSubmissionEMail(&$receipient,&$subject,&$body)
    {
        return true;
    }
    
    function BeforeSendingAutoResponse(&$receipient,&$subject,&$body)
    {
        return true;
    }
    function BeforeSubmissionTableDisplay(&$fields)
    {
        return true;
    }
    function BeforeDetailedPageDisplay(&$rec)
    {
        return true;
    }
	function HandleFilePreview($filepath)
	{
		return false;
	}
}

class FM_ExtensionModuleHolder
{
    private $modules;

    private $config;
    private $formvars;
    private $logger;
    private $globaldata;
    private $error_handler;
    private $formname;

    function Init(&$config,&$formvars,&$logger,&$globaldata,&$error_handler,$formname)
    {
        $this->config = &$config;
        $this->formvars = &$formvars;
        $this->logger = &$logger;
        $this->globaldata =&$globaldata;
        $this->error_handler = &$error_handler;
        $this->formname = $formname;
        $this->InitModules();
    }

   public function __construct()
   {
      $this->modules = array();
   }
   
   function AddModule(&$module)
   {
      array_push_ref($this->modules,$module);
   }
   
   function InitModules()
   {
      $N = count($this->modules);

        for($i=0;$i<$N;$i++)
        {
            $mod = &$this->modules[$i];
            $mod->Init($this->config,$this->formvars,
                $this->logger,$this->globaldata,
                $this->error_handler,$this->formname);
        }      
   }
    
   function Delegate($method,$params)
   {
        $N = count($this->modules);
        for($i=0;$i<$N;$i++)
        {
            $mod = &$this->modules[$i];
            $ret_c = call_user_func_array(array(&$mod, $method), $params);
            if(false === $ret_c)
            {
                return false;
            }
        }
        return true;
   }
   
   function DelegateFalseDefault($method,$params)
   {
        $N = count($this->modules);
        for($i=0;$i<$N;$i++)
        {
            $mod = &$this->modules[$i];
            $ret_c = call_user_func_array(array(&$mod, $method), $params);
            if(true === $ret_c)
            {
                return true;
            }
        }
        return false;
   }
   
   function DelegateEx($method,$params)
   {
        $N = count($this->modules);
        $ret = true;
        for($i=0;$i<$N;$i++)
        {
            $mod = &$this->modules[$i];
            $ret_c = call_user_func_array(array($mod, $method), $params);
            $ret = $ret && $ret_c;
        }
        return $ret;
   }

   function AfterVariablessInitialized()
    {
        return $this->Delegate('AfterVariablessInitialized',array()); 
    }   
    
    function BeforeStartProcessing()
    {
        return $this->Delegate('BeforeStartProcessing',array());    
    }
    
    function BeforeFormDisplay(&$formvars,$pagenum)
    {
        return $this->Delegate('BeforeFormDisplay',array(&$formvars,$pagenum));    
    }   
    function LoadDynamicList($listname,&$rows)
    {
        return $this->DelegateFalseDefault('LoadDynamicList',array($listname,&$rows));
    }
    
    function LoadCascadedList($listname,$parent,&$rows)
    {
        return $this->DelegateFalseDefault('LoadCascadedList',array($listname,$parent,&$rows));
    }    
    function DoValidatePage(&$formvars, &$error_hash,$page)
    {
        return $this->DelegateEx('DoValidatePage',array(&$formvars, &$error_hash,$page));
    }       

    function DoValidate(&$formvars, &$error_hash)
    {
        return $this->DelegateEx('DoValidate',array(&$formvars, &$error_hash));
    }
    
    function PreprocessFormSubmission(&$formvars)
    {
        return $this->Delegate('PreprocessFormSubmission',array(&$formvars));
    }

    function BeforeConfirmPageDisplay(&$formvars)
    {
      return $this->Delegate('BeforeConfirmPageDisplay',array(&$formvars));
    }

    function FormSubmitted(&$formvars)
    {
      return $this->Delegate('FormSubmitted',array(&$formvars));
    }

    function BeforeThankYouPageDisplay(&$formvars)
    {
      return $this->Delegate('BeforeThankYouPageDisplay',array(&$formvars));
    }
    
    
    function BeforeSendingFormSubmissionEMail(&$receipient,&$subject,&$body)
    {
       return $this->Delegate('BeforeSendingFormSubmissionEMail',array(&$receipient,&$subject,&$body));
    }
   
    function BeforeSendingAutoResponse(&$receipient,&$subject,&$body)
    {
        return $this->Delegate('BeforeSendingAutoResponse',array(&$receipient,&$subject,&$body));
    }

    function BeforeSubmissionTableDisplay(&$fields)
    {
        return $this->Delegate('BeforeSubmissionTableDisplay',array(&$fields));
    }

    function BeforeDetailedPageDisplay(&$rec)
    {
        return $this->Delegate('BeforeDetailedPageDisplay',array(&$rec));
    }

    function HandleFilePreview($filepath)
    {
        return $this->Delegate('HandleFilePreview',array($filepath));
    }    
}

///////Form Installer////////////////////
class SFM_AppCommand
{
    private $config;
    private $logger;
    private $error_handler;   
    private $globaldata;
    public  $response_sender;
    private $app_command;
    private $command_value;
    private $email_tested;
    private $dblogin_tested;

    public function __construct(&$globals, &$config,&$logger,&$error_handler)
    {
      $this->globaldata = &$globals;
      $this->config = &$config;
      $this->logger = &$logger;
      $this->error_handler = &$error_handler;    
      $this->response_sender = new FM_Response($this->config,$this->logger);
      $this->app_command='';
      $this->command_value='';
      
      $this->email_tested=false;
      $this->dblogin_tested=false;
    }
    
    function IsAppCommand()
    {
        return empty($this->globaldata->post_vars[$this->config->config_update_var])?false:true;
    }
     
    function Execute(&$modules)
    {
        $continue = false;
        if(!$this->IsAppCommand())
        {
            return true;
        }
        $this->config->debug_mode = true;
        $this->error_handler->disable_syserror_handling=true;
        $this->error_handler->DisableErrorFormMerge();
        
        if($this->DecodeAppCommand())
        {
            switch($this->app_command)
            {
                case 'ping':
                {
                    $this->DoPingCommand($modules);
                    break;
                }
                case 'log_file':
                {
                    $this->GetLogFile();
                    break;
                }
                default:
                {
                    $this->DoCustomModuleCommand($modules);
                    break;
                }
            }//switch
        }//if
        
        $this->ShowResponse();
        return $continue;
    }
    
    function DoPingCommand(&$modules)
    {
        if(!$this->Ping())
        {
            return false;
        }
        
        
        $N = count($modules);

        for($i=0;$i<$N;$i++)
        {
            $mod = &$modules[$i];
            if(!$mod->ValidateInstallation($this))
            {
               $this->logger->LogError("ValidateInstallation: module $i returns false!");
               return false;
            }
        }    
        return true;
    }
    
    function GetLogFile()
    {
		$log_file_path=$this->logger->get_log_file_path();

        $this->response_sender->SetNeedToRemoveHeader();
        
        return $this->response_sender->load_file_contents($log_file_path);
    }
    
    function DecodeAppCommand()
    {
        if(!$this->ValidateConfigInput())
        {
            return false;
        }
        $cmd = $this->globaldata->post_vars[$this->config->config_update_var];
        
        

        $this->app_command = $this->Decrypt($cmd);

        

        $val = "";
        if(isset($this->globaldata->post_vars[$this->config->config_update_val]))
        {
            $val = $this->globaldata->post_vars[$this->config->config_update_val];
            $this->command_value = $this->Decrypt($val);
        }
        
        return true;
    }
    
    function DoCustomModuleCommand(&$modules)
    {
        $N = count($modules);

        for($i=0;$i<$N;$i++)
        {
            $mod = &$modules[$i];
            if($mod->DoAppCommand($this->app_command,$this->command_value,$this))
            {
               break;
            }
        }
    }
    
    function IsPingCommand()
    {
        return ($this->app_command == 'ping')?true:false;
    }
    
    function Ping()
    {
        $ret = false;
        $installed="no";
        if(true == $this->config->installed)
        {
            $installed="yes";
            $ret=true;
        }
        $this->response_sender->appendResponse("is_installed",$installed);
        return $ret;
    }
    
    function TestSMTPEmail()
    {
        if(!$this->config->IsSMTP())
        {
            return true;
        }
        
        $initial_sys_error = $this->error_handler->IsSysError();
        
        $mailer = new FM_Mailer($this->config,$this->logger,$this->error_handler);
        //Note: this is only to test the SMTP settings. It tries to send an email with subject test Email
        // If there is any error in SMTP settings, this will throw error
        $ret = $mailer->SendMail('tests@simfatic.com','tests@simfatic.com','Test Email','Test Email',false);
        
        if($ret && !$initial_sys_error && $this->error_handler->IsSysError())
        {
            $ret = false;
        }
        
        if(!$ret)
        {
            $this->logger->LogInfo("SFM_AppCommand: Ping-> error sending email ");
            $this->response_sender->appendResponse('email_smtp','error');
        }
        
        $this->email_tested = true;
        
        return $ret;
    }
    function rem_file($filename,$base_folder)
    {
        $filename = trim($filename);
        if(strlen($filename)>0)
        {
          $filepath = sfm_make_path($base_folder,$filename);
          $this->logger->LogInfo("SFM_AppCommand: Removing file $filepath");
          
          $success=false;
          if(unlink($filepath))
          {
            $this->response_sender->appendResponse("result","success");
            $this->logger->LogInfo("SFM_AppCommand: rem_file removed file $filepath");
            $success=true;
          }
          $this->response_sender->appendResultResponse($success);
        }    
    }
    function IsEmailTested()
    {
        return $this->email_tested;
    }
    function TestDBLogin()
    {
        if($this->IsDBLoginTested())
        {
            return true;
        }
        $dbutil = new FM_DBUtil();
        $dbutil->Init($this->config,$this->logger,$this->error_handler);
        $dbtest_result = $dbutil->Login();

        if(false === $dbtest_result)
        {
            $this->logger->LogInfo("SFM_AppCommand: Ping-> dblogin error");
            $this->response_sender->appendResponse('dblogin','error');
        }
        $this->dblogin_tested = true;
        return $dbtest_result;
    }    
    
    function IsDBLoginTested()
    {
        return $this->dblogin_tested;
    }
    
    function ValidateConfigInput()
    {
        $ret=false;
        if(!isset($this->config->encr_key) ||
            strlen($this->config->encr_key)<=0)
        {
            $this->addError("Form key is not set");
        }
        else
        if(!isset($this->config->form_id) ||
            strlen($this->config->form_id)<=0)
        {
            $this->addError("Form ID is not set");
        }
        else
        if(!isset($this->globaldata->post_vars[$this->config->config_form_id_var]))
        {
            $this->addError("Form ID is not set");
        }
        else
        {
            $form_id = $this->globaldata->post_vars[$this->config->config_form_id_var];
            $form_id = $this->Decrypt($form_id);
            if(strcmp($form_id,$this->config->form_id)!=0)
            {
                $this->addError("Form ID Does not match");
            }
            else
            {
                $this->logger->LogInfo("SFM_AppCommand:ValidateConfigInput succeeded");
                $ret=true;
            }
        }
        return $ret;
    }    
    function Decrypt($str)
    {
        return sfm_crypt_decrypt($str,$this->config->encr_key);
    }    
    function ShowResponse()
    {
		if($this->error_handler->IsSysError())
		{
			$this->addError($this->error_handler->GetSysError());
		}
        $this->response_sender->ShowResponse();
    }    
    function addError($error)
    {
        $this->response_sender->addError($error);
    }    
}


class FM_Response
{
    private $error_str;
    private $response;
    private $encr_response;
    private $extra_headers;
    private $sfm_headers;

    public function __construct(&$config,&$logger)
    {
        $this->error_str="";
        $this->response="";
        $this->encr_response=true;
        $this->extra_headers = array();
        $this->sfm_headers = array();
        $this->logger = &$logger;
		$this->config = &$config;
    }

    function addError($error)
    {
        $this->error_str .= $error;
        $this->error_str .= "\n";
    }
	
	function isError()
	{
		return empty($this->error_str)?false:true;
	}
	function getError()
	{
		return $this->error_str;
	}
	
	function getResponseStr()
	{
		return $this->response;
	}
	
    function straighten_val($val)
    {
        $ret = str_replace("\n","\\n",$val);
        return $ret;
    }

    function appendResponse($name,$val)
    {
        $this->response .= "$name: ".$this->straighten_val($val);
        $this->response .= "\n";
    }

    function appendResultResponse($is_success)
    {
        if($is_success)
        {
            $this->appendResponse("result","success");
        }
        else
        {
            $this->appendResponse("result","failed");
        }
    }
    
    function SetEncrypt($encrypt)
    {
        $this->encr_response = $encrypt;
    }

    function AddResponseHeader($name,$val,$replace=false)
    {
        $header = "$name: $val";
        $this->extra_headers[$header] = $replace;
    }

    function AddSFMHeader($option)
    {
        $this->sfm_headers[$option]=1;
    }


    function SetNeedToRemoveHeader()
    {
        $this->AddSFMHeader('remove-header-footer');
    }

    function ShowResponse()
    {
        $err=false;
        ob_clean();
        if(strlen($this->error_str)>0) 
        {
            $err=true;
            $this->appendResponse("error",$this->error_str);
            $this->AddSFMHeader('sforms-error');
            $log_str = sprintf("FM_Response: reporting error:%s",$this->error_str);
            $this->logger->LogError($log_str);
        }
        
        $resp="";
        if(($this->encr_response || true == $err) && 
           (false == $this->config->sys_debug_mode))
        {
            $this->AddResponseHeader('Content-type','application/sforms-e');

            $resp = $this->Encrypt($this->response);
        }
        else
        {
            $resp = $this->response;
        }

        $cust_header = "SFM_COMM_HEADER_START{\n";
        foreach($this->sfm_headers as $sfm_header => $flag)
        {
             $cust_header .=  $sfm_header."\n";
        }
        $cust_header .= "}SFM_COMM_HEADER_END\n";

        $resp = $cust_header.$resp;

        $this->AddResponseHeader('pragma','no-cache',/*replace*/true);
		$this->AddResponseHeader('cache-control','no-cache');        
        $this->AddResponseHeader('Content-Length',strlen($resp));

        foreach($this->extra_headers as $header_str => $replace)
        {
            
            header($header_str, false);
        }


        print($resp);
        if(true == $this->config->sys_debug_mode)
        {
            $this->logger->print_log();
        }
    }

    function Encrypt($str)
    {
        //echo " Encrypt $str ";
        //$blowfish = new Crypt_Blowfish($this->config->encr_key);
        $retdata = sfm_crypt_encrypt($str,$this->config->encr_key);
        /*$blowfish =& Crypt_Blowfish::factory('ecb');
        $blowfish->setKey($this->config->encr_key);

        $encr = $blowfish->encrypt($str);
        $retdata = bin2hex($encr);*/
        return $retdata;
    }

    function load_file_contents($filepath)
    {
        $filename = basename($filepath);

        $this->encr_response=false;
        

        $fp = fopen($filepath,"r");

        if(!$fp)
        {
            $err = sprintf("Failed opening file %s",$filepath);
            $this->addError($err);
            return false;
        }

        $this->AddResponseHeader('Content-Disposition',"attachment; filename=\"$filename\"");

        $this->response = file_get_contents($filepath);
        
        return true;
    }
    
    function SetResponse($response)
    {
        $this->response = $response;
    }
}


class FM_CommonObjs
{
   public $formpage_renderer;
   public $security_monitor;
   public $formvar_mx;

   public function __construct(&$config,&$logger,&$globaldata)
   {
     $this->security_monitor = 
         new FM_SecurityMonitor($config,$logger,$globaldata); 
         
      $this->formpage_renderer = 
         new  FM_FormPageRenderer($config,$logger,$globaldata, $this->security_monitor);
         
      $this->formvar_mx = new FM_FormVarMx($config,$logger,$globaldata);
   }
   function InitFormVars(&$formvars)
   {
        $this->formvar_mx->InitFormVars($formvars);
   }
   function InitExtensionModule(&$extmodule)
   {
     $this->formpage_renderer->InitExtensionModule($extmodule);
   }
}

////SFM_FormProcessor////////////////
class SFM_FormProcessor
{
   private $globaldata;
   private $formvars;
   private $formname;
   private $logger;
   private $config;
   private $error_handler;
   private $modules;
   private $ext_module_holder;
   private $common_objs;

   public function __construct($formname)
   {
      ob_start();
	  
      $this->formname = $formname;
      $this->config = new FM_Config();
      $this->config->formname = $formname;
      
      $this->globaldata = new FM_GlobalData($this->config);

      $this->logger = new FM_Logger($this->config,$formname);
      $this->logger->SetLogSource("form:$formname");
      
      $this->common_objs = new FM_CommonObjs($this->config,$this->logger,$this->globaldata);
      
      $this->error_handler  = new FM_ErrorHandler($this->logger,$this->config,
                        $this->globaldata,$formname,$this->common_objs);
                        
      $this->error_handler->InstallConfigErrorCatch();
      $this->modules=array();
      $this->ext_module_holder = new FM_ExtensionModuleHolder();
      $this->common_objs->InitExtensionModule($this->ext_module_holder);
      $this->SetDebugMode(true);//till it is disabled explicitely
      
   }
	function initTimeZone($timezone)
	{
		$this->config->default_timezone = $timezone;
		
		if (!empty($timezone) && $timezone != 'default') 
		{
			//for >= PHP 5.1
			if(function_exists("date_default_timezone_set")) 
			{
				date_default_timezone_set($timezone);
			} 
			else// for PHP < 5.1 
			{
				@putenv("PHP_TZ=".$timezone);
				@putenv("TZ=" .$timezone);
			}
		}//if
		else
		{
            if(function_exists("date_default_timezone_set"))
            {
                date_default_timezone_set(date_default_timezone_get());
            }
		}
	}

	function init_session()
    {
	
        $ua = empty($_SERVER['HTTP_USER_AGENT']) ? '' : $_SERVER['HTTP_USER_AGENT'];
        
		if(true === $this->config->enable_p2p_header && 
		   false !== stristr($ua, 'MSIE'))
		{
			header('P3P: CP="CURa ADMa DEVa PSAo PSDo OUR BUS UNI PUR INT DEM STA PRE COM NAV OTC NOI DSP COR"');
		}
        
		session_start();
        $this->globaldata->InitSession();
        
        if(empty($this->globaldata->session['sfm_form_user_identificn']) )
        {
            if(!empty($_GET['sfm_sid']) &&
            true === $this->config->enable_session_id_url)
            {//session not loaded properly; load from sid passed through URL
                $this->logger->LogInfo('getting session ID from URL');
                session_destroy();
                session_id($_GET['sfm_sid']);
                session_start();
                $this->globaldata->InitSession();
                if(empty($this->globaldata->session['sfm_form_user_identificn'])||
                   $this->globaldata->session['sfm_form_user_identificn'] != $this->globaldata->GetVisitorUniqueKey())
                {//safety check. If the user is not same something wrong.
                    
                    $this->logger->LogInfo('sfm_form_user_identificn still does not match:'.
                            $this->globaldata->session['sfm_form_user_identificn']);
                            
                    session_regenerate_id(FALSE);
                    session_unset();
                    $this->globaldata->InitSession();
                }
            }
            
            $this->globaldata->session['sfm_form_user_identificn'] = $this->globaldata->GetVisitorUniqueKey();
        }
        
	}
	
    function setEmailFormatHTML($ishtml)
    {
        $this->config->email_format_html = $ishtml;
    }
    function setFormFileFolder($folder)
    {
        $this->config->form_file_folder = $folder;
    }
    
    function setIsInstalled($installed)
    {
        $this->config->installed = $installed;   
    }

    function SetSingleBoxErrorDisplay($enabled)
    {
        $this->config->show_errors_single_box = $enabled;
    }

   function setFormPage($page_num,$formpage_code,$condn='')
   {
      $this->common_objs->formpage_renderer->setFormPage($page_num,$formpage_code,$condn);
   }
    
    function setFormID($id)
    {
        $this->config->set_form_id($id);
    }
    
    function setLocale($name,$date_format)
    {
        $this->config->SetLocale($name,$date_format);
    }
    
    function setFormKey($key)
    {
        $this->config->set_encrkey($key);
    }
	
	function DisableAntiSpammerSecurityChecks()
	{
		$this->config->bypass_spammer_validations=true;
	}
	
    function InitSMTP($host,$uname,$pwd,$port)
    {
        $this->config->InitSMTP($host,$uname,$pwd,$port);
    }
    function setFormDBLogin($host,$uname,$pwd,$database)
    {
        $this->config->setFormDBLogin($host,$uname,$pwd,$database);
    }

   function EnableLogging($enable)
   {
      $this->logger->EnableLogging($enable);
   }

   function SetErrorEmail($email)
   {
      $this->config->set_error_email($email);
   }
   
   function SetPasswordsEncrypted($encrypted)
   {
    $this->config->passwords_encrypted = $encrypted;
   }
   
   function SetPrintPreviewPage($preview_file)
   {
      $this->config->SetPrintPreviewPage($preview_file);
   }
   function AddElementInfo($name,$type,$extra_info,$page=0)
   {
      $this->config->element_info->AddElementInfo($name,$type,$extra_info,$page);
   }
   function AddDefaultValue($name,$value)
   {
      $this->config->element_info->AddDefaultValue($name,$value);
   }

   function SetDebugMode($enable)
   {
      $this->config->setDebugMode($enable);
   }

    function SetFromAddress($from)
    {
        $this->config->from_addr = $from;
    }

    function SetVariableFrom($enable)
    {
        $this->config->variable_from = $enable;
    }

    function SetHiddenInputTrapVarName($varname)
    {
      $this->config->hidden_input_trap_var = $varname;
    }    

    function EnableLoadFormValuesFromURL($enable)
    {
        $this->config->load_values_from_url = $enable;
    }
    
    function EnableAutoFieldTable($enable)
    {
        $this->config->enable_auto_field_table = $enable;
    }
    
    function BanIP($ip)
    {
      $this->common_objs->security_monitor->AddBannedIP($ip);
    }
    
    function SetSavedMessageTemplate($msg_templ)
    {
       $this->config->saved_message_templ = $msg_templ;
    }
     
   function GetVars()
   {
        $this->globaldata->GetGlobalVars();

        $this->formvars = &$this->globaldata->formvars;

        $this->logger->LogInfo("GetVars:formvars ".@print_r($this->formvars,true)."\n");

        if(!isset($this->formname) ||
           strlen($this->formname)==0)
        {
           $this->error_handler->HandleConfigError("Please set the form name","");
           return false;            
        }
        $this->error_handler->SetFormVars($this->formvars);
        return true;
   }
   
    function addModule(&$module)
    {
        array_push_ref($this->modules,$module);
    }

   function AddExtensionModule(&$module)
   {
      $this->ext_module_holder->AddModule($module);
   }

   function getmicrotime()
    { 
        list($usec, $sec) = explode(" ",microtime()); 
        return ((float)$usec + (float)$sec); 
    } 
    
   function AfterVariablessInitialized()
   {
        $N = count($this->modules);
        for($i=0;$i<$N;$i++)
        {
            if(false === $this->modules[$i]->AfterVariablessInitialized())
            {
                return false;
            }
        }
        if(false === $this->ext_module_holder->AfterVariablessInitialized())
        {
            return false;
        }
        return true;
   }
    
   function DoAppCommand()
   {
        $continue=true;
        $app_command = new SFM_AppCommand($this->globaldata,$this->config,$this->logger,$this->error_handler);
        $continue = $app_command->Execute($this->modules);
        return $continue;
   }
   
   function ProcessForm()
   {
        $timestart = $this->getmicrotime();
        
		$this->init_session();
		
        $N = count($this->modules);

        for($i=0;$i<$N;$i++)
        {
            $mod = &$this->modules[$i];
            $mod->Init($this->config,$this->globaldata->formvars,
                $this->logger,$this->globaldata,
                $this->error_handler,$this->formname,
            $this->ext_module_holder,$this->common_objs);
        }
        
        $this->ext_module_holder->Init($this->config,$this->globaldata->formvars,
               $this->logger,$this->globaldata,
               $this->error_handler,$this->formname);
      
        
        do
        {
            if(false === $this->ext_module_holder->BeforeStartProcessing())
            {
                $this->logger->LogInfo("Extension module returns false for BeforeStartProcessing. Stopping.");
                break;
            }
            
            if(false == $this->GetVars())
            {
                $this->logger->LogError("GetVars() Failed");
                break;
            }
            
            if(false === $this->DoAppCommand())
            {
                break;
            }
            
            if(false === $this->AfterVariablessInitialized() )
            {
                break;
            }
            
            for($i=0;$i<$N;$i++)
            {
                $mod = &$this->modules[$i];
                $continue = true;

                $mod->Process($continue);
                if(!$continue){break;}
            }

            for($i=0;$i<$N;$i++)
            {
                $mod = &$this->modules[$i];
                $mod->Destroy();
            }
            
            if($this->globaldata->IsFormProcessingComplete())
            {
                $this->globaldata->DestroySession();
            }
        }while(0);
        
        $timetaken  = $this->getmicrotime()-$timestart;

        $this->logger->FlushLog();

        ob_end_flush();
        return true;
   }
   
   function showVars()
   {
      foreach($this->formvars as $name => $value)
      {
         echo "$name $value <br>";
      }
   }
   
}


class SecureFileMaker
{
    private $signature_line;
    private $file_pos;

     public function __construct($signature)
     {
        $this->signature_line = $signature;
     }

     function CreateFile($filepath, $first_line)
     {
        $fp = fopen($filepath,"w");
        if(!$fp)
        {
          return false;
        }

        $header = $this->get_header()."\n";
        $first_line = trim($first_line);
        $header .= $first_line."\n";

        if(!fwrite($fp,$header))
        {
            return false;
        }

        $footer = $this->get_footer();

        if(!fwrite($fp,$footer))
        {
            return false;
        }

        fclose($fp);

        return true;
     }
     
     function get_header()
     {
        return "<?PHP /* $this->signature_line";
     }

     function get_footer()
     {
        return "$this->signature_line */ ?>";
     }

    function gets_backward($fp)
    {
        $ret_str="";
        $t="";
        while ($t != "\n") 
        {
            if(0 != fseek($fp, $this->file_pos, SEEK_END))
            {
              rewind($fp);
              break;
            }
            $t = fgetc($fp);
            
            $ret_str = $t.$ret_str;
            $this->file_pos --;
        }
        return $ret_str;
    }

    function AppendLine($file_path,$insert_line)
    {
        $fp = fopen($file_path,"r+");

        if(!$fp)
        {
            return false;
        }
        $all_lines="";

        $this->file_pos = -1;
        fseek($fp,$this->file_pos,SEEK_END);
        

        while(1)
        {
            $pos = ftell($fp);
            if($pos <= 0)
            {
                break;
            }
            $line = $this->gets_backward($fp);
            $cmpline = trim($line);

            $all_lines .= $line;

            if(strcmp($cmpline,$this->get_footer())==0)
            {
              break;
            }
        }
        
        $all_lines = trim($all_lines);
        $insert_line = trim($insert_line);

        $all_lines = "$insert_line\n$all_lines";

        if(!fwrite($fp,$all_lines))
        {
            return false;
        }

        fclose($fp);
        return true;
    }

    function ReadNextLine($fp)
    {
        while(!feof($fp))
        {
            $line = fgets($fp);
            $line = trim($line);

            if(strcmp($line,$this->get_header())!=0 &&
               strcmp($line,$this->get_footer())!=0)
            {
                return $line;
            }
        }
        return "";
    }
}

/**
 * PEAR, the PHP Extension and Application Repository
 *
 * PEAR class and PEAR_Error class
 *
 * PHP versions 4 and 5
 *
 * @category   pear
 * @package    PEAR
 * @author     Sterling Hughes <sterling@php.net>
 * @author     Stig Bakken <ssb@php.net>
 * @author     Tomas V.V.Cox <cox@idecnet.com>
 * @author     Greg Beaver <cellog@php.net>
 * @copyright  1997-2010 The Authors
 * @license    http://opensource.org/licenses/bsd-license.php New BSD License
 * @link       http://pear.php.net/package/PEAR
 * @since      File available since Release 0.1
 */

/**#@+
 * ERROR constants
 */
define('PEAR_ERROR_RETURN',     1);
define('PEAR_ERROR_PRINT',      2);
define('PEAR_ERROR_TRIGGER',    4);
define('PEAR_ERROR_DIE',        8);
define('PEAR_ERROR_CALLBACK',  16);
/**
 * WARNING: obsolete
 * @deprecated
 */
define('PEAR_ERROR_EXCEPTION', 32);
/**#@-*/

if (substr(PHP_OS, 0, 3) == 'WIN') {
    define('OS_WINDOWS', true);
    define('OS_UNIX',    false);
    define('PEAR_OS',    'Windows');
} else {
    define('OS_WINDOWS', false);
    define('OS_UNIX',    true);
    define('PEAR_OS',    'Unix'); // blatant assumption
}

$GLOBALS['_PEAR_default_error_mode']     = PEAR_ERROR_RETURN;
$GLOBALS['_PEAR_default_error_options']  = E_USER_NOTICE;
$GLOBALS['_PEAR_destructor_object_list'] = array();
$GLOBALS['_PEAR_shutdown_funcs']         = array();
$GLOBALS['_PEAR_error_handler_stack']    = array();

@ini_set('track_errors', true);

/**
 * Base class for other PEAR classes.  Provides rudimentary
 * emulation of destructors.
 *
 * If you want a destructor in your class, inherit PEAR and make a
 * destructor method called _yourclassname (same name as the
 * constructor, but with a "_" prefix).  Also, in your constructor you
 * have to call the PEAR constructor: $this->PEAR();.
 * The destructor method will be called without parameters.  Note that
 * at in some SAPI implementations (such as Apache), any output during
 * the request shutdown (in which destructors are called) seems to be
 * discarded.  If you need to get any debug information from your
 * destructor, use error_log(), syslog() or something similar.
 *
 * IMPORTANT! To use the emulated destructors you need to create the
 * objects by reference: $obj =& new PEAR_child;
 *
 * @category   pear
 * @package    PEAR
 * @author     Stig Bakken <ssb@php.net>
 * @author     Tomas V.V. Cox <cox@idecnet.com>
 * @author     Greg Beaver <cellog@php.net>
 * @copyright  1997-2006 The PHP Group
 * @license    http://opensource.org/licenses/bsd-license.php New BSD License
 * @version    Release: 1.10.1
 * @link       http://pear.php.net/package/PEAR
 * @see        PEAR_Error
 * @since      Class available since PHP 4.0.2
 * @link        http://pear.php.net/manual/en/core.pear.php#core.pear.pear
 */
class PEAR
{
    /**
     * Whether to enable internal debug messages.
     *
     * @var     bool
     * @access  private
     */
    var $_debug = false;

    /**
     * Default error mode for this object.
     *
     * @var     int
     * @access  private
     */
    var $_default_error_mode = null;

    /**
     * Default error options used for this object when error mode
     * is PEAR_ERROR_TRIGGER.
     *
     * @var     int
     * @access  private
     */
    var $_default_error_options = null;

    /**
     * Default error handler (callback) for this object, if error mode is
     * PEAR_ERROR_CALLBACK.
     *
     * @var     string
     * @access  private
     */
    var $_default_error_handler = '';

    /**
     * Which class to use for error objects.
     *
     * @var     string
     * @access  private
     */
    var $_error_class = 'PEAR_Error';

    /**
     * An array of expected errors.
     *
     * @var     array
     * @access  private
     */
    var $_expected_errors = array();

    /**
     * List of methods that can be called both statically and non-statically.
     * @var array
     */
    protected static $bivalentMethods = array(
        'setErrorHandling' => true,
        'raiseError' => true,
        'throwError' => true,
        'pushErrorHandling' => true,
        'popErrorHandling' => true,
    );

    /**
     * Constructor.  Registers this object in
     * $_PEAR_destructor_object_list for destructor emulation if a
     * destructor object exists.
     *
     * @param string $error_class  (optional) which class to use for
     *        error objects, defaults to PEAR_Error.
     * @access public
     * @return void
     */
    function __construct($error_class = null)
    {
        $classname = strtolower(get_class($this));
        if ($this->_debug) {
            print "PEAR constructor called, class=$classname\n";
        }

        if ($error_class !== null) {
            $this->_error_class = $error_class;
        }

        while ($classname && strcasecmp($classname, "pear")) {
            $destructor = "_$classname";
            if (method_exists($this, $destructor)) {
                global $_PEAR_destructor_object_list;
                $_PEAR_destructor_object_list[] = &$this;
                if (!isset($GLOBALS['_PEAR_SHUTDOWN_REGISTERED'])) {
                    register_shutdown_function("_PEAR_call_destructors");
                    $GLOBALS['_PEAR_SHUTDOWN_REGISTERED'] = true;
                }
                break;
            } else {
                $classname = get_parent_class($classname);
            }
        }
    }

    /**
     * Only here for backwards compatibility.
     * E.g. Archive_Tar calls $this->PEAR() in its constructor.
     *
     * @param string $error_class Which class to use for error objects,
     *                            defaults to PEAR_Error.
     */
    public function PEAR($error_class = null)
    {
        self::__construct($error_class);
    }

    /**
     * Destructor (the emulated type of...).  Does nothing right now,
     * but is included for forward compatibility, so subclass
     * destructors should always call it.
     *
     * See the note in the class desciption about output from
     * destructors.
     *
     * @access public
     * @return void
     */
    function _PEAR() {
        if ($this->_debug) {
            printf("PEAR destructor called, class=%s\n", strtolower(get_class($this)));
        }
    }

    public function __call($method, $arguments)
    {
        if (!isset(self::$bivalentMethods[$method])) {
            trigger_error(
                'Call to undefined method PEAR::' . $method . '()', E_USER_ERROR
            );
        }
        return call_user_func_array(
            array(get_class(), '_' . $method),
            array_merge(array($this), $arguments)
        );
    }

    public static function __callStatic($method, $arguments)
    {
        if (!isset(self::$bivalentMethods[$method])) {
            trigger_error(
                'Call to undefined method PEAR::' . $method . '()', E_USER_ERROR
            );
        }
        return call_user_func_array(
            array(get_class(), '_' . $method),
            array_merge(array(null), $arguments)
        );
    }

    /**
    * If you have a class that's mostly/entirely static, and you need static
    * properties, you can use this method to simulate them. Eg. in your method(s)
    * do this: $myVar = &PEAR::getStaticProperty('myclass', 'myVar');
    * You MUST use a reference, or they will not persist!
    *
    * @param  string $class  The calling classname, to prevent clashes
    * @param  string $var    The variable to retrieve.
    * @return mixed   A reference to the variable. If not set it will be
    *                 auto initialised to NULL.
    */
    public static function &getStaticProperty($class, $var)
    {
        static $properties;
        if (!isset($properties[$class])) {
            $properties[$class] = array();
        }

        if (!array_key_exists($var, $properties[$class])) {
            $properties[$class][$var] = null;
        }

        return $properties[$class][$var];
    }

    /**
    * Use this function to register a shutdown method for static
    * classes.
    *
    * @param  mixed $func  The function name (or array of class/method) to call
    * @param  mixed $args  The arguments to pass to the function
    *
    * @return void
    */
    public static function registerShutdownFunc($func, $args = array())
    {
        // if we are called statically, there is a potential
        // that no shutdown func is registered.  Bug #6445
        if (!isset($GLOBALS['_PEAR_SHUTDOWN_REGISTERED'])) {
            register_shutdown_function("_PEAR_call_destructors");
            $GLOBALS['_PEAR_SHUTDOWN_REGISTERED'] = true;
        }
        $GLOBALS['_PEAR_shutdown_funcs'][] = array($func, $args);
    }

    /**
     * Tell whether a value is a PEAR error.
     *
     * @param   mixed $data   the value to test
     * @param   int   $code   if $data is an error object, return true
     *                        only if $code is a string and
     *                        $obj->getMessage() == $code or
     *                        $code is an integer and $obj->getCode() == $code
     *
     * @return  bool    true if parameter is an error
     */
    public static function isError($data, $code = null)
    {
        if (!is_a($data, 'PEAR_Error')) {
            return false;
        }

        if (is_null($code)) {
            return true;
        } elseif (is_string($code)) {
            return $data->getMessage() == $code;
        }

        return $data->getCode() == $code;
    }

    /**
     * Sets how errors generated by this object should be handled.
     * Can be invoked both in objects and statically.  If called
     * statically, setErrorHandling sets the default behaviour for all
     * PEAR objects.  If called in an object, setErrorHandling sets
     * the default behaviour for that object.
     *
     * @param object $object
     *        Object the method was called on (non-static mode)
     *
     * @param int $mode
     *        One of PEAR_ERROR_RETURN, PEAR_ERROR_PRINT,
     *        PEAR_ERROR_TRIGGER, PEAR_ERROR_DIE,
     *        PEAR_ERROR_CALLBACK or PEAR_ERROR_EXCEPTION.
     *
     * @param mixed $options
     *        When $mode is PEAR_ERROR_TRIGGER, this is the error level (one
     *        of E_USER_NOTICE, E_USER_WARNING or E_USER_ERROR).
     *
     *        When $mode is PEAR_ERROR_CALLBACK, this parameter is expected
     *        to be the callback function or method.  A callback
     *        function is a string with the name of the function, a
     *        callback method is an array of two elements: the element
     *        at index 0 is the object, and the element at index 1 is
     *        the name of the method to call in the object.
     *
     *        When $mode is PEAR_ERROR_PRINT or PEAR_ERROR_DIE, this is
     *        a printf format string used when printing the error
     *        message.
     *
     * @access public
     * @return void
     * @see PEAR_ERROR_RETURN
     * @see PEAR_ERROR_PRINT
     * @see PEAR_ERROR_TRIGGER
     * @see PEAR_ERROR_DIE
     * @see PEAR_ERROR_CALLBACK
     * @see PEAR_ERROR_EXCEPTION
     *
     * @since PHP 4.0.5
     */
    protected static function _setErrorHandling(
        $object, $mode = null, $options = null
    ) {
        if ($object !== null) {
            $setmode     = &$object->_default_error_mode;
            $setoptions  = &$object->_default_error_options;
        } else {
            $setmode     = &$GLOBALS['_PEAR_default_error_mode'];
            $setoptions  = &$GLOBALS['_PEAR_default_error_options'];
        }

        switch ($mode) {
            case PEAR_ERROR_EXCEPTION:
            case PEAR_ERROR_RETURN:
            case PEAR_ERROR_PRINT:
            case PEAR_ERROR_TRIGGER:
            case PEAR_ERROR_DIE:
            case null:
                $setmode = $mode;
                $setoptions = $options;
                break;

            case PEAR_ERROR_CALLBACK:
                $setmode = $mode;
                // class/object method callback
                if (is_callable($options)) {
                    $setoptions = $options;
                } else {
                    trigger_error("invalid error callback", E_USER_WARNING);
                }
                break;

            default:
                trigger_error("invalid error mode", E_USER_WARNING);
                break;
        }
    }

    /**
     * This method is used to tell which errors you expect to get.
     * Expected errors are always returned with error mode
     * PEAR_ERROR_RETURN.  Expected error codes are stored in a stack,
     * and this method pushes a new element onto it.  The list of
     * expected errors are in effect until they are popped off the
     * stack with the popExpect() method.
     *
     * Note that this method can not be called statically
     *
     * @param mixed $code a single error code or an array of error codes to expect
     *
     * @return int     the new depth of the "expected errors" stack
     * @access public
     */
    function expectError($code = '*')
    {
        if (is_array($code)) {
            array_push($this->_expected_errors, $code);
        } else {
            array_push($this->_expected_errors, array($code));
        }
        return count($this->_expected_errors);
    }

    /**
     * This method pops one element off the expected error codes
     * stack.
     *
     * @return array   the list of error codes that were popped
     */
    function popExpect()
    {
        return array_pop($this->_expected_errors);
    }

    /**
     * This method checks unsets an error code if available
     *
     * @param mixed error code
     * @return bool true if the error code was unset, false otherwise
     * @access private
     * @since PHP 4.3.0
     */
    function _checkDelExpect($error_code)
    {
        $deleted = false;
        foreach ($this->_expected_errors as $key => $error_array) {
            if (in_array($error_code, $error_array)) {
                unset($this->_expected_errors[$key][array_search($error_code, $error_array)]);
                $deleted = true;
            }

            // clean up empty arrays
            if (0 == count($this->_expected_errors[$key])) {
                unset($this->_expected_errors[$key]);
            }
        }

        return $deleted;
    }

    /**
     * This method deletes all occurences of the specified element from
     * the expected error codes stack.
     *
     * @param  mixed $error_code error code that should be deleted
     * @return mixed list of error codes that were deleted or error
     * @access public
     * @since PHP 4.3.0
     */
    function delExpect($error_code)
    {
        $deleted = false;
        if ((is_array($error_code) && (0 != count($error_code)))) {
            // $error_code is a non-empty array here; we walk through it trying
            // to unset all values
            foreach ($error_code as $key => $error) {
                $deleted =  $this->_checkDelExpect($error) ? true : false;
            }

            return $deleted ? true : PEAR::raiseError("The expected error you submitted does not exist"); // IMPROVE ME
        } elseif (!empty($error_code)) {
            // $error_code comes alone, trying to unset it
            if ($this->_checkDelExpect($error_code)) {
                return true;
            }

            return PEAR::raiseError("The expected error you submitted does not exist"); // IMPROVE ME
        }

        // $error_code is empty
        return PEAR::raiseError("The expected error you submitted is empty"); // IMPROVE ME
    }

    /**
     * This method is a wrapper that returns an instance of the
     * configured error class with this object's default error
     * handling applied.  If the $mode and $options parameters are not
     * specified, the object's defaults are used.
     *
     * @param mixed $message a text error message or a PEAR error object
     *
     * @param int $code      a numeric error code (it is up to your class
     *                  to define these if you want to use codes)
     *
     * @param int $mode      One of PEAR_ERROR_RETURN, PEAR_ERROR_PRINT,
     *                  PEAR_ERROR_TRIGGER, PEAR_ERROR_DIE,
     *                  PEAR_ERROR_CALLBACK, PEAR_ERROR_EXCEPTION.
     *
     * @param mixed $options If $mode is PEAR_ERROR_TRIGGER, this parameter
     *                  specifies the PHP-internal error level (one of
     *                  E_USER_NOTICE, E_USER_WARNING or E_USER_ERROR).
     *                  If $mode is PEAR_ERROR_CALLBACK, this
     *                  parameter specifies the callback function or
     *                  method.  In other error modes this parameter
     *                  is ignored.
     *
     * @param string $userinfo If you need to pass along for example debug
     *                  information, this parameter is meant for that.
     *
     * @param string $error_class The returned error object will be
     *                  instantiated from this class, if specified.
     *
     * @param bool $skipmsg If true, raiseError will only pass error codes,
     *                  the error message parameter will be dropped.
     *
     * @return object   a PEAR error object
     * @see PEAR::setErrorHandling
     * @since PHP 4.0.5
     */
    protected static function _raiseError($object,
                         $message = null,
                         $code = null,
                         $mode = null,
                         $options = null,
                         $userinfo = null,
                         $error_class = null,
                         $skipmsg = false)
    {
        // The error is yet a PEAR error object
        if (is_object($message)) {
            $code        = $message->getCode();
            $userinfo    = $message->getUserInfo();
            $error_class = $message->getType();
            $message->error_message_prefix = '';
            $message     = $message->getMessage();
        }

        if (
            $object !== null &&
            isset($object->_expected_errors) &&
            count($object->_expected_errors) > 0 &&
            count($exp = end($object->_expected_errors))
        ) {
            if ($exp[0] == "*" ||
                (is_int(reset($exp)) && in_array($code, $exp)) ||
                (is_string(reset($exp)) && in_array($message, $exp))
            ) {
                $mode = PEAR_ERROR_RETURN;
            }
        }

        // No mode given, try global ones
        if ($mode === null) {
            // Class error handler
            if ($object !== null && isset($object->_default_error_mode)) {
                $mode    = $object->_default_error_mode;
                $options = $object->_default_error_options;
            // Global error handler
            } elseif (isset($GLOBALS['_PEAR_default_error_mode'])) {
                $mode    = $GLOBALS['_PEAR_default_error_mode'];
                $options = $GLOBALS['_PEAR_default_error_options'];
            }
        }

        if ($error_class !== null) {
            $ec = $error_class;
        } elseif ($object !== null && isset($object->_error_class)) {
            $ec = $object->_error_class;
        } else {
            $ec = 'PEAR_Error';
        }

        if ($skipmsg) {
            $a = new $ec($code, $mode, $options, $userinfo);
        } else {
            $a = new $ec($message, $code, $mode, $options, $userinfo);
        }

        return $a;
    }

    /**
     * Simpler form of raiseError with fewer options.  In most cases
     * message, code and userinfo are enough.
     *
     * @param mixed $message a text error message or a PEAR error object
     *
     * @param int $code      a numeric error code (it is up to your class
     *                  to define these if you want to use codes)
     *
     * @param string $userinfo If you need to pass along for example debug
     *                  information, this parameter is meant for that.
     *
     * @return object   a PEAR error object
     * @see PEAR::raiseError
     */
    protected static function _throwError($object, $message = null, $code = null, $userinfo = null)
    {
        if ($object !== null) {
            $a = &$object->raiseError($message, $code, null, null, $userinfo);
            return $a;
        }

        $a = &PEAR::raiseError($message, $code, null, null, $userinfo);
        return $a;
    }

    public static function staticPushErrorHandling($mode, $options = null)
    {
        $stack       = &$GLOBALS['_PEAR_error_handler_stack'];
        $def_mode    = &$GLOBALS['_PEAR_default_error_mode'];
        $def_options = &$GLOBALS['_PEAR_default_error_options'];
        $stack[] = array($def_mode, $def_options);
        switch ($mode) {
            case PEAR_ERROR_EXCEPTION:
            case PEAR_ERROR_RETURN:
            case PEAR_ERROR_PRINT:
            case PEAR_ERROR_TRIGGER:
            case PEAR_ERROR_DIE:
            case null:
                $def_mode = $mode;
                $def_options = $options;
                break;

            case PEAR_ERROR_CALLBACK:
                $def_mode = $mode;
                // class/object method callback
                if (is_callable($options)) {
                    $def_options = $options;
                } else {
                    trigger_error("invalid error callback", E_USER_WARNING);
                }
                break;

            default:
                trigger_error("invalid error mode", E_USER_WARNING);
                break;
        }
        $stack[] = array($mode, $options);
        return true;
    }

    public static function staticPopErrorHandling()
    {
        $stack = &$GLOBALS['_PEAR_error_handler_stack'];
        $setmode     = &$GLOBALS['_PEAR_default_error_mode'];
        $setoptions  = &$GLOBALS['_PEAR_default_error_options'];
        array_pop($stack);
        list($mode, $options) = $stack[sizeof($stack) - 1];
        array_pop($stack);
        switch ($mode) {
            case PEAR_ERROR_EXCEPTION:
            case PEAR_ERROR_RETURN:
            case PEAR_ERROR_PRINT:
            case PEAR_ERROR_TRIGGER:
            case PEAR_ERROR_DIE:
            case null:
                $setmode = $mode;
                $setoptions = $options;
                break;

            case PEAR_ERROR_CALLBACK:
                $setmode = $mode;
                // class/object method callback
                if (is_callable($options)) {
                    $setoptions = $options;
                } else {
                    trigger_error("invalid error callback", E_USER_WARNING);
                }
                break;

            default:
                trigger_error("invalid error mode", E_USER_WARNING);
                break;
        }
        return true;
    }

    /**
     * Push a new error handler on top of the error handler options stack. With this
     * you can easily override the actual error handler for some code and restore
     * it later with popErrorHandling.
     *
     * @param mixed $mode (same as setErrorHandling)
     * @param mixed $options (same as setErrorHandling)
     *
     * @return bool Always true
     *
     * @see PEAR::setErrorHandling
     */
    protected static function _pushErrorHandling($object, $mode, $options = null)
    {
        $stack = &$GLOBALS['_PEAR_error_handler_stack'];
        if ($object !== null) {
            $def_mode    = &$object->_default_error_mode;
            $def_options = &$object->_default_error_options;
        } else {
            $def_mode    = &$GLOBALS['_PEAR_default_error_mode'];
            $def_options = &$GLOBALS['_PEAR_default_error_options'];
        }
        $stack[] = array($def_mode, $def_options);

        if ($object !== null) {
            $object->setErrorHandling($mode, $options);
        } else {
            PEAR::setErrorHandling($mode, $options);
        }
        $stack[] = array($mode, $options);
        return true;
    }

    /**
    * Pop the last error handler used
    *
    * @return bool Always true
    *
    * @see PEAR::pushErrorHandling
    */
    protected static function _popErrorHandling($object)
    {
        $stack = &$GLOBALS['_PEAR_error_handler_stack'];
        array_pop($stack);
        list($mode, $options) = $stack[sizeof($stack) - 1];
        array_pop($stack);
        if ($object !== null) {
            $object->setErrorHandling($mode, $options);
        } else {
            PEAR::setErrorHandling($mode, $options);
        }
        return true;
    }

    /**
    * OS independent PHP extension load. Remember to take care
    * on the correct extension name for case sensitive OSes.
    *
    * @param string $ext The extension name
    * @return bool Success or not on the dl() call
    */
    public static function loadExtension($ext)
    {
        if (extension_loaded($ext)) {
            return true;
        }

        // if either returns true dl() will produce a FATAL error, stop that
        if (
            function_exists('dl') === false ||
            ini_get('enable_dl') != 1
        ) {
            return false;
        }

        if (OS_WINDOWS) {
            $suffix = '.dll';
        } elseif (PHP_OS == 'HP-UX') {
            $suffix = '.sl';
        } elseif (PHP_OS == 'AIX') {
            $suffix = '.a';
        } elseif (PHP_OS == 'OSX') {
            $suffix = '.bundle';
        } else {
            $suffix = '.so';
        }

        return @dl('php_'.$ext.$suffix) || @dl($ext.$suffix);
    }
}

function _PEAR_call_destructors()
{
    global $_PEAR_destructor_object_list;
    if (is_array($_PEAR_destructor_object_list) &&
        sizeof($_PEAR_destructor_object_list))
    {
        reset($_PEAR_destructor_object_list);

        $destructLifoExists = PEAR::getStaticProperty('PEAR', 'destructlifo');

        if ($destructLifoExists) {
            $_PEAR_destructor_object_list = array_reverse($_PEAR_destructor_object_list);
        }

        while (list($k, $objref) = each($_PEAR_destructor_object_list)) {
            $classname = get_class($objref);
            while ($classname) {
                $destructor = "_$classname";
                if (method_exists($objref, $destructor)) {
                    $objref->$destructor();
                    break;
                } else {
                    $classname = get_parent_class($classname);
                }
            }
        }
        // Empty the object list to ensure that destructors are
        // not called more than once.
        $_PEAR_destructor_object_list = array();
    }

    // Now call the shutdown functions
    if (
        isset($GLOBALS['_PEAR_shutdown_funcs']) &&
        is_array($GLOBALS['_PEAR_shutdown_funcs']) &&
        !empty($GLOBALS['_PEAR_shutdown_funcs'])
    ) {
        foreach ($GLOBALS['_PEAR_shutdown_funcs'] as $value) {
            call_user_func_array($value[0], $value[1]);
        }
    }
}

/**
 * Standard PEAR error class for PHP 4
 *
 * This class is supserseded by {@link PEAR_Exception} in PHP 5
 *
 * @category   pear
 * @package    PEAR
 * @author     Stig Bakken <ssb@php.net>
 * @author     Tomas V.V. Cox <cox@idecnet.com>
 * @author     Gregory Beaver <cellog@php.net>
 * @copyright  1997-2006 The PHP Group
 * @license    http://opensource.org/licenses/bsd-license.php New BSD License
 * @version    Release: 1.10.1
 * @link       http://pear.php.net/manual/en/core.pear.pear-error.php
 * @see        PEAR::raiseError(), PEAR::throwError()
 * @since      Class available since PHP 4.0.2
 */
class PEAR_Error
{
    var $error_message_prefix = '';
    var $mode                 = PEAR_ERROR_RETURN;
    var $level                = E_USER_NOTICE;
    var $code                 = -1;
    var $message              = '';
    var $userinfo             = '';
    var $backtrace            = null;

    /**
     * PEAR_Error constructor
     *
     * @param string $message  message
     *
     * @param int $code     (optional) error code
     *
     * @param int $mode     (optional) error mode, one of: PEAR_ERROR_RETURN,
     * PEAR_ERROR_PRINT, PEAR_ERROR_DIE, PEAR_ERROR_TRIGGER,
     * PEAR_ERROR_CALLBACK or PEAR_ERROR_EXCEPTION
     *
     * @param mixed $options   (optional) error level, _OR_ in the case of
     * PEAR_ERROR_CALLBACK, the callback function or object/method
     * tuple.
     *
     * @param string $userinfo (optional) additional user/debug info
     *
     * @access public
     *
     */
    function __construct($message = 'unknown error', $code = null,
                        $mode = null, $options = null, $userinfo = null)
    {
        if ($mode === null) {
            $mode = PEAR_ERROR_RETURN;
        }
        $this->message   = $message;
        $this->code      = $code;
        $this->mode      = $mode;
        $this->userinfo  = $userinfo;

        $skiptrace = PEAR::getStaticProperty('PEAR_Error', 'skiptrace');

        if (!$skiptrace) {
            $this->backtrace = debug_backtrace();
            if (isset($this->backtrace[0]) && isset($this->backtrace[0]['object'])) {
                unset($this->backtrace[0]['object']);
            }
        }

        if ($mode & PEAR_ERROR_CALLBACK) {
            $this->level = E_USER_NOTICE;
            $this->callback = $options;
        } else {
            if ($options === null) {
                $options = E_USER_NOTICE;
            }

            $this->level = $options;
            $this->callback = null;
        }

        if ($this->mode & PEAR_ERROR_PRINT) {
            if (is_null($options) || is_int($options)) {
                $format = "%s";
            } else {
                $format = $options;
            }

            printf($format, $this->getMessage());
        }

        if ($this->mode & PEAR_ERROR_TRIGGER) {
            trigger_error($this->getMessage(), $this->level);
        }

        if ($this->mode & PEAR_ERROR_DIE) {
            $msg = $this->getMessage();
            if (is_null($options) || is_int($options)) {
                $format = "%s";
                if (substr($msg, -1) != "\n") {
                    $msg .= "\n";
                }
            } else {
                $format = $options;
            }
            die(sprintf($format, $msg));
        }

        if ($this->mode & PEAR_ERROR_CALLBACK && is_callable($this->callback)) {
            call_user_func($this->callback, $this);
        }

        if ($this->mode & PEAR_ERROR_EXCEPTION) {
            trigger_error("PEAR_ERROR_EXCEPTION is obsolete, use class PEAR_Exception for exceptions", E_USER_WARNING);
            eval('$e = new Exception($this->message, $this->code);throw($e);');
        }
    }

    /**
     * Only here for backwards compatibility.
     *
     * Class "Cache_Error" still uses it, among others.
     *
     * @param string $message  Message
     * @param int    $code     Error code
     * @param int    $mode     Error mode
     * @param mixed  $options  See __construct()
     * @param string $userinfo Additional user/debug info
     */
    public function PEAR_Error(
        $message = 'unknown error', $code = null, $mode = null,
        $options = null, $userinfo = null
    ) {
        self::__construct($message, $code, $mode, $options, $userinfo);
    }

    /**
     * Get the error mode from an error object.
     *
     * @return int error mode
     * @access public
     */
    function getMode()
    {
        return $this->mode;
    }

    /**
     * Get the callback function/method from an error object.
     *
     * @return mixed callback function or object/method array
     * @access public
     */
    function getCallback()
    {
        return $this->callback;
    }

    /**
     * Get the error message from an error object.
     *
     * @return  string  full error message
     * @access public
     */
    function getMessage()
    {
        return ($this->error_message_prefix . $this->message);
    }

    /**
     * Get error code from an error object
     *
     * @return int error code
     * @access public
     */
     function getCode()
     {
        return $this->code;
     }

    /**
     * Get the name of this error/exception.
     *
     * @return string error/exception name (type)
     * @access public
     */
    function getType()
    {
        return get_class($this);
    }

    /**
     * Get additional user-supplied information.
     *
     * @return string user-supplied information
     * @access public
     */
    function getUserInfo()
    {
        return $this->userinfo;
    }

    /**
     * Get additional debug information supplied by the application.
     *
     * @return string debug information
     * @access public
     */
    function getDebugInfo()
    {
        return $this->getUserInfo();
    }

    /**
     * Get the call backtrace from where the error was generated.
     * Supported with PHP 4.3.0 or newer.
     *
     * @param int $frame (optional) what frame to fetch
     * @return array Backtrace, or NULL if not available.
     * @access public
     */
    function getBacktrace($frame = null)
    {
        if (defined('PEAR_IGNORE_BACKTRACE')) {
            return null;
        }
        if ($frame === null) {
            return $this->backtrace;
        }
        return $this->backtrace[$frame];
    }

    function addUserInfo($info)
    {
        if (empty($this->userinfo)) {
            $this->userinfo = $info;
        } else {
            $this->userinfo .= " ** $info";
        }
    }

    function __toString()
    {
        return $this->getMessage();
    }

    /**
     * Make a string representation of this object.
     *
     * @return string a string with an object summary
     * @access public
     */
    function toString()
    {
        $modes = array();
        $levels = array(E_USER_NOTICE  => 'notice',
                        E_USER_WARNING => 'warning',
                        E_USER_ERROR   => 'error');
        if ($this->mode & PEAR_ERROR_CALLBACK) {
            if (is_array($this->callback)) {
                $callback = (is_object($this->callback[0]) ?
                    strtolower(get_class($this->callback[0])) :
                    $this->callback[0]) . '::' .
                    $this->callback[1];
            } else {
                $callback = $this->callback;
            }
            return sprintf('[%s: message="%s" code=%d mode=callback '.
                           'callback=%s prefix="%s" info="%s"]',
                           strtolower(get_class($this)), $this->message, $this->code,
                           $callback, $this->error_message_prefix,
                           $this->userinfo);
        }
        if ($this->mode & PEAR_ERROR_PRINT) {
            $modes[] = 'print';
        }
        if ($this->mode & PEAR_ERROR_TRIGGER) {
            $modes[] = 'trigger';
        }
        if ($this->mode & PEAR_ERROR_DIE) {
            $modes[] = 'die';
        }
        if ($this->mode & PEAR_ERROR_RETURN) {
            $modes[] = 'return';
        }
        return sprintf('[%s: message="%s" code=%d mode=%s level=%s '.
                       'prefix="%s" info="%s"]',
                       strtolower(get_class($this)), $this->message, $this->code,
                       implode("|", $modes), $levels[$this->level],
                       $this->error_message_prefix,
                       $this->userinfo);
    }
}

/*
 * Local Variables:
 * mode: php
 * tab-width: 4
 * c-basic-offset: 4
 * End:
 */

/**
 * Crypt_Blowfish allows for encryption and decryption on the fly using
 * the Blowfish algorithm. Crypt_Blowfish does not require the MCrypt
 * PHP extension, but uses it if available, otherwise it uses only PHP.
 * Crypt_Blowfish supports encryption/decryption with or without a secret key.
 *
 *
 * PHP versions 4 and 5
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_0.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   Encryption
 * @package    Crypt_Blowfish
 * @author     Matthew Fonda <mfonda@php.net>
 * @copyright  2005 Matthew Fonda
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    CVS: $Id: encr-lib.php,v 1.2 2010/02/16 06:51:02 Prasanth Exp $
 * @link       http://pear.php.net/package/Crypt_Blowfish
 */



/**
 * Engine choice constants
 */
/**
 * To let the Crypt_Blowfish package decide which engine to use
 * @since 1.1.0
 */
define('CRYPT_BLOWFISH_AUTO',   1);
/**
 * To use the MCrypt PHP extension.
 * @since 1.1.0
 */
define('CRYPT_BLOWFISH_MCRYPT', 2);
/**
 * To use the PHP-only engine.
 * @since 1.1.0
 */
define('CRYPT_BLOWFISH_PHP',    3);


/**
 * Example using the factory method in CBC mode
 * <code>
 * $bf =& Crypt_Blowfish::factory('cbc');
 * if (PEAR::isError($bf)) {
 *     echo $bf->getMessage();
 *     exit;
 * }
 * $iv = 'abc123+=';
 * $key = 'My secret key';
 * $bf->setKey($key, $iv);
 * $encrypted = $bf->encrypt('this is some example plain text');
 * $bf->setKey($key, $iv);
 * $plaintext = $bf->decrypt($encrypted);
 * if (PEAR::isError($plaintext)) {
 *     echo $plaintext->getMessage();
 *     exit;
 * }
 * // Encrypted text is padded prior to encryption
 * // so you may need to trim the decrypted result.
 * echo 'plain text: ' . trim($plaintext);
 * </code>
 *
 * To disable using the mcrypt library, define the CRYPT_BLOWFISH_NOMCRYPT
 * constant. This is useful for instance on Windows platform with a buggy
 * mdecrypt_generic() function.
 * <code>
 * define('CRYPT_BLOWFISH_NOMCRYPT', true);
 * </code>
 *
 * @category   Encryption
 * @package    Crypt_Blowfish
 * @author     Matthew Fonda <mfonda@php.net>
 * @author     Philippe Jausions <jausions@php.net>
 * @copyright  2005-2006 Matthew Fonda
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link       http://pear.php.net/package/Crypt_Blowfish
 * @version    @package_version@
 * @access     public
 */
 
 define('CRYPT_BLOWFISH_NOMCRYPT', true);

class Crypt_Blowfish
{
    /**
     * Implementation-specific Crypt_Blowfish object
     *
     * @var object
     * @access private
     */
    var $_crypt = null;

    /**
     * Initialization vector
     *
     * @var string
     * @access protected
     */
    var $_iv = null;

    /**
     * Holds block size
     *
     * @var integer
     * @access protected
     */
    var $_block_size = 8;

    /**
     * Holds IV size
     *
     * @var integer
     * @access protected
     */
    var $_iv_size = 8;

    /**
     * Holds max key size
     *
     * @var integer
     * @access protected
     */
    var $_key_size = 56;

    /**
     * Crypt_Blowfish Constructor
     * Initializes the Crypt_Blowfish object (in EBC mode), and sets
     * the secret key
     *
     * @param string $key
     * @access public
     * @deprecated Since 1.1.0
     * @see Crypt_Blowfish::factory()
     */
    function __construct($key)
    {
        $this->_crypt =& Crypt_Blowfish::factory('ecb', $key);
        if (!PEAR::isError($this->_crypt)) {
            $this->_crypt->setKey($key);
        }
    }

    /**
     * Crypt_Blowfish object factory
     *
     * This is the recommended method to create a Crypt_Blowfish instance.
     *
     * When using CRYPT_BLOWFISH_AUTO, you can force the package to ignore
     * the MCrypt extension, by defining CRYPT_BLOWFISH_NOMCRYPT.
     *
     * @param string $mode operating mode 'ecb' or 'cbc' (case insensitive)
     * @param string $key
     * @param string $iv initialization vector (must be provided for CBC mode)
     * @param integer $engine one of CRYPT_BLOWFISH_AUTO, CRYPT_BLOWFISH_PHP
     *                or CRYPT_BLOWFISH_MCRYPT
     * @return object Crypt_Blowfish object or PEAR_Error object on error
     * @access public
     * @static
     * @since 1.1.0
     */
    function &factory($mode = 'ecb', $key = null, $iv = null, $engine = CRYPT_BLOWFISH_AUTO)
    {
        switch ($engine) {
            case CRYPT_BLOWFISH_AUTO:
                if (!defined('CRYPT_BLOWFISH_NOMCRYPT')
                    && extension_loaded('mcrypt')) {
                    $engine = CRYPT_BLOWFISH_MCRYPT;
                } else {
                    $engine = CRYPT_BLOWFISH_PHP;
                }
                break;
            case CRYPT_BLOWFISH_MCRYPT:
                if (!PEAR::loadExtension('mcrypt')) {
                    return PEAR::raiseError('MCrypt extension is not available.');
                }
                break;
        }

        switch ($engine) {
            case CRYPT_BLOWFISH_PHP:
                $mode = strtoupper($mode);
                $class = 'Crypt_Blowfish_' . $mode;
                
                $crypt = new $class(null);
                break;

            case CRYPT_BLOWFISH_MCRYPT:
                
                $crypt = new Crypt_Blowfish_MCrypt(null, $mode);
                break;
        }

        if (!is_null($key) || !is_null($iv)) {
            $result = $crypt->setKey($key, $iv);
            if (PEAR::isError($result)) {
                return $result;
            }
        }

        return $crypt;
    }

    /**
     * Returns the algorithm's block size
     *
     * @return integer
     * @access public
     * @since 1.1.0
     */
    function getBlockSize()
    {
        return $this->_block_size;
    }

    /**
     * Returns the algorithm's IV size
     *
     * @return integer
     * @access public
     * @since 1.1.0
     */
    function getIVSize()
    {
        return $this->_iv_size;
    }

    /**
     * Returns the algorithm's maximum key size
     *
     * @return integer
     * @access public
     * @since 1.1.0
     */
    function getMaxKeySize()
    {
        return $this->_key_size;
    }

    /**
     * Deprecated isReady method
     *
     * @return bool
     * @access public
     * @deprecated
     */
    function isReady()
    {
        return true;
    }

    /**
     * Deprecated init method - init is now a private
     * method and has been replaced with _init
     *
     * @return bool
     * @access public
     * @deprecated
     */
    function init()
    {
        return $this->_crypt->init();
    }

    /**
     * Encrypts a string
     *
     * Value is padded with NUL characters prior to encryption. You may
     * need to trim or cast the type when you decrypt.
     *
     * @param string $plainText the string of characters/bytes to encrypt
     * @return string|PEAR_Error Returns cipher text on success, PEAR_Error on failure
     * @access public
     */
    function encrypt($plainText)
    {
        return $this->_crypt->encrypt($plainText);
    }


    /**
     * Decrypts an encrypted string
     *
     * The value was padded with NUL characters when encrypted. You may
     * need to trim the result or cast its type.
     *
     * @param string $cipherText the binary string to decrypt
     * @return string|PEAR_Error Returns plain text on success, PEAR_Error on failure
     * @access public
     */
    function decrypt($cipherText)
    {
        return $this->_crypt->decrypt($cipherText);
    }

    /**
     * Sets the secret key
     * The key must be non-zero, and less than or equal to
     * 56 characters (bytes) in length.
     *
     * If you are making use of the PHP MCrypt extension, you must call this
     * method before each encrypt() and decrypt() call.
     *
     * @param string $key
     * @return boolean|PEAR_Error  Returns TRUE on success, PEAR_Error on failure
     * @access public
     */
    function setKey($key)
    {
        return $this->_crypt->setKey($key);
    }
}


/**
 * Crypt_Blowfish allows for encryption and decryption on the fly using
 * the Blowfish algorithm. Crypt_Blowfish does not require the mcrypt
 * PHP extension, but uses it if available, otherwise it uses only PHP.
 * Crypt_Blowfish support encryption/decryption with or without a secret key.
 *
 * PHP versions 4 and 5
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_0.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   Encryption
 * @package    Crypt_Blowfish
 * @author     Matthew Fonda <mfonda@php.net>
 * @author     Philippe Jausions <jausions@php.net>
 * @copyright  2005-2006 Matthew Fonda
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    CVS: $Id: encr-lib.php,v 1.2 2010/02/16 06:51:02 Prasanth Exp $
 * @link       http://pear.php.net/package/Crypt_Blowfish
 * @since      1.1.0
 */


/**
 * Common class for PHP-only implementations
 *
 * @category   Encryption
 * @package    Crypt_Blowfish
 * @author     Matthew Fonda <mfonda@php.net>
 * @author     Philippe Jausions <jausions@php.net>
 * @copyright  2005-2006 Matthew Fonda
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link       http://pear.php.net/package/Crypt_Blowfish
 * @version    @package_version@
 * @access     public
 * @since      1.1.0
 */
class Crypt_Blowfish_PHP extends Crypt_Blowfish
{
    /**
     * P-Array contains 18 32-bit subkeys
     *
     * @var array
     * @access protected
     */
    var $_P = array();

    /**
     * Array of four S-Blocks each containing 256 32-bit entries
     *
     * @var array
     * @access protected
     */
    var $_S = array();

    /**
     * Whether the IV is required
     *
     * @var boolean
     * @access protected
     */
    var $_iv_required = false;
    
    /**
     * Hash value of last used key
     * 
     * @var     string
     * @access  protected
     */
    var $_keyHash = null;

    /**
     * Crypt_Blowfish_PHP Constructor
     * Initializes the Crypt_Blowfish object, and sets
     * the secret key
     *
     * @param string $key
     * @param string $mode operating mode 'ecb' or 'cbc'
     * @param string $iv initialization vector
     * @access protected
     */
    function x_construct($key = null, $iv = null)
    {
        $this->_iv = $iv . ((strlen($iv) < $this->_iv_size)
                            ? str_repeat(chr(0), $this->_iv_size - strlen($iv))
                            : '');
        if (!is_null($key)) {
            $this->setKey($key, $this->_iv);
        }
    }

    /**
     * Initializes the Crypt_Blowfish object
     *
     * @access private
     */
    function _init()
    {
        $defaults = new Crypt_Blowfish_DefaultKey();
        $this->_P = $defaults->P;
        $this->_S = $defaults->S;
    }

    /**
     * Workaround for XOR on certain systems
     *
     * @param integer|float $l
     * @param integer|float $r
     * @return float
     * @access protected
     */
    function _binxor($l, $r)
    {
        $x = (($l < 0) ? (float)($l + 4294967296) : (float)$l)
             ^ (($r < 0) ? (float)($r + 4294967296) : (float)$r);

        return (float)(($x < 0) ? $x + 4294967296 : $x);
    }

    /**
     * Enciphers a single 64-bit block
     *
     * @param int &$Xl
     * @param int &$Xr
     * @access protected
     */
    function _encipher(&$Xl, &$Xr)
    {
        if ($Xl < 0) {
            $Xl += 4294967296;
        }
        if ($Xr < 0) {
            $Xr += 4294967296;
        }

        for ($i = 0; $i < 16; $i++) {
            $temp = $Xl ^ $this->_P[$i];
            if ($temp < 0) {
                $temp += 4294967296;
            }

            $Xl = fmod((fmod($this->_S[0][($temp >> 24) & 255]
                             + $this->_S[1][($temp >> 16) & 255], 4294967296) 
                        ^ $this->_S[2][($temp >> 8) & 255]) 
                       + $this->_S[3][$temp & 255], 4294967296) ^ $Xr;
            $Xr = $temp;
        }
        $Xr = $this->_binxor($Xl, $this->_P[16]);
        $Xl = $this->_binxor($temp, $this->_P[17]);
    }

    /**
     * Deciphers a single 64-bit block
     *
     * @param int &$Xl
     * @param int &$Xr
     * @access protected
     */
    function _decipher(&$Xl, &$Xr)
    {
        if ($Xl < 0) {
            $Xl += 4294967296;
        }
        if ($Xr < 0) {
            $Xr += 4294967296;
        }

        for ($i = 17; $i > 1; $i--) {
            $temp = $Xl ^ $this->_P[$i];
            if ($temp < 0) {
                $temp += 4294967296;
            }

            $Xl = fmod((fmod($this->_S[0][($temp >> 24) & 255]
                             + $this->_S[1][($temp >> 16) & 255], 4294967296) 
                        ^ $this->_S[2][($temp >> 8) & 255]) 
                       + $this->_S[3][$temp & 255], 4294967296) ^ $Xr;
            $Xr = $temp;
        }
        $Xr = $this->_binxor($Xl, $this->_P[1]);
        $Xl = $this->_binxor($temp, $this->_P[0]);
    }

    /**
     * Sets the secret key
     * The key must be non-zero, and less than or equal to
     * 56 characters (bytes) in length.
     *
     * If you are making use of the PHP mcrypt extension, you must call this
     * method before each encrypt() and decrypt() call.
     *
     * @param string $key
     * @param string $iv 8-char initialization vector (required for CBC mode)
     * @return boolean|PEAR_Error  Returns TRUE on success, PEAR_Error on failure
     * @access public
     * @todo Fix the caching of the key
     */
    function setKey($key, $iv = null)
    {
        if (!is_string($key)) {
            return PEAR::raiseError('Key must be a string', 2);
        }

        $len = strlen($key);

        if ($len > $this->_key_size || $len == 0) {
            return PEAR::raiseError('Key must be less than ' . $this->_key_size . ' characters (bytes) and non-zero. Supplied key length: ' . $len, 3);
        }

        if ($this->_iv_required) {
            if (strlen($iv) != $this->_iv_size) {
                return PEAR::raiseError('IV must be ' . $this->_iv_size . '-character (byte) long. Supplied IV length: ' . strlen($iv), 7);
            }
            $this->_iv = $iv;
        }

        if ($this->_keyHash == md5($key)) {
            return true;
        }

        $this->_init();

        $k = 0;
        $data = 0;
        $datal = 0;
        $datar = 0;

        for ($i = 0; $i < 18; $i++) {
            $data = 0;
            for ($j = 4; $j > 0; $j--) {
                    $data = $data << 8 | ord($key{$k});
                    $k = ($k+1) % $len;
            }
            $this->_P[$i] ^= $data;
        }

        for ($i = 0; $i <= 16; $i += 2) {
            $this->_encipher($datal, $datar);
            $this->_P[$i] = $datal;
            $this->_P[$i+1] = $datar;
        }
        for ($i = 0; $i < 256; $i += 2) {
            $this->_encipher($datal, $datar);
            $this->_S[0][$i] = $datal;
            $this->_S[0][$i+1] = $datar;
        }
        for ($i = 0; $i < 256; $i += 2) {
            $this->_encipher($datal, $datar);
            $this->_S[1][$i] = $datal;
            $this->_S[1][$i+1] = $datar;
        }
        for ($i = 0; $i < 256; $i += 2) {
            $this->_encipher($datal, $datar);
            $this->_S[2][$i] = $datal;
            $this->_S[2][$i+1] = $datar;
        }
        for ($i = 0; $i < 256; $i += 2) {
            $this->_encipher($datal, $datar);
            $this->_S[3][$i] = $datal;
            $this->_S[3][$i+1] = $datar;
        }

        $this->_keyHash = md5($key);
        return true;
    }
}

/**
 * PHP implementation of the Blowfish algorithm in ECB mode
 *
 * PHP versions 4 and 5
 *
 * LICENSE: This source file is subject to version 3.0 of the PHP license
 * that is available through the world-wide-web at the following URI:
 * http://www.php.net/license/3_0.txt.  If you did not receive a copy of
 * the PHP License and are unable to obtain it through the web, please
 * send a note to license@php.net so we can mail you a copy immediately.
 *
 * @category   Encryption
 * @package    Crypt_Blowfish
 * @author     Matthew Fonda <mfonda@php.net>
 * @author     Philippe Jausions <jausions@php.net>
 * @copyright  2005-2006 Matthew Fonda
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @version    CVS: $Id: encr-lib.php,v 1.2 2010/02/16 06:51:02 Prasanth Exp $
 * @link       http://pear.php.net/package/Crypt_Blowfish
 * @since      1.1.0
 */


/**
 * Example
 * <code>
 * $bf =& Crypt_Blowfish::factory('ecb');
 * if (PEAR::isError($bf)) {
 *     echo $bf->getMessage();
 *     exit;
 * }
 * $bf->setKey('My secret key');
 * $encrypted = $bf->encrypt('this is some example plain text');
 * $plaintext = $bf->decrypt($encrypted);
 * echo "plain text: $plaintext";
 * </code>
 *
 * @category   Encryption
 * @package    Crypt_Blowfish
 * @author     Matthew Fonda <mfonda@php.net>
 * @author     Philippe Jausions <jausions@php.net>
 * @copyright  2005-2006 Matthew Fonda
 * @license    http://www.php.net/license/3_0.txt  PHP License 3.0
 * @link       http://pear.php.net/package/Crypt_Blowfish
 * @version    @package_version@
 * @access     public
 * @since      1.1.0
 */
class Crypt_Blowfish_ECB extends Crypt_Blowfish_PHP
{
    /**
     * Crypt_Blowfish Constructor
     * Initializes the Crypt_Blowfish object, and sets
     * the secret key
     *
     * @param string $key
     * @param string $iv initialization vector
     * @access public
     */
    function __construct($key = null, $iv = null)
    {
        $this->x_construct($key, $iv);
    }

    /**
     * Class constructor
     *
     * @param string $key
     * @param string $iv initialization vector
     * @access public
     */
    function x_construct($key = null, $iv = null)
    {
        $this->_iv_required = false;
        parent::x_construct($key, $iv);
    }

    /**
     * Encrypts a string
     *
     * Value is padded with NUL characters prior to encryption. You may
     * need to trim or cast the type when you decrypt.
     *
     * @param string $plainText string of characters/bytes to encrypt
     * @return string|PEAR_Error Returns cipher text on success, PEAR_Error on failure
     * @access public
     */
    function encrypt($plainText)
    {
        if (!is_string($plainText)) {
            return PEAR::raiseError('Input must be a string', 0);
        } elseif (empty($this->_P)) {
            return PEAR::raiseError('The key is not initialized.', 8);
        }

        $cipherText = '';
        $len = strlen($plainText);
        $plainText .= str_repeat(chr(0), (8 - ($len % 8)) % 8);

        for ($i = 0; $i < $len; $i += 8) {
            list(, $Xl, $Xr) = unpack('N2', substr($plainText, $i, 8));
            $this->_encipher($Xl, $Xr);
            $cipherText .= pack('N2', $Xl, $Xr);
        }

        return $cipherText;
    }

    /**
     * Decrypts an encrypted string
     *
     * The value was padded with NUL characters when encrypted. You may
     * need to trim the result or cast its type.
     *
     * @param string $cipherText
     * @return string|PEAR_Error Returns plain text on success, PEAR_Error on failure
     * @access public
     */
    function decrypt($cipherText)
    {
        if (!is_string($cipherText)) {
            return PEAR::raiseError('Cipher text must be a string', 1);
        }
        if (empty($this->_P)) {
            return PEAR::raiseError('The key is not initialized.', 8);
        }

        $plainText = '';
        $len = strlen($cipherText);
        $cipherText .= str_repeat(chr(0), (8 - ($len % 8)) % 8);

        for ($i = 0; $i < $len; $i += 8) {
            list(, $Xl, $Xr) = unpack('N2', substr($cipherText, $i, 8));
            $this->_decipher($Xl, $Xr);
            $plainText .= pack('N2', $Xl, $Xr);
        }

        return $plainText;
    }
}

class Crypt_Blowfish_DefaultKey
{
    var $P = array();
    
    var $S = array();
    
    function __construct()
    {
        $this->P = array(
            0x243f6a88, 0x85a308d3, 0x13198a2e, 0x03707344,
	        0xa4093822, 0x299f31d0, 0x082efa98, 0xec4e6c89,
	        0x452821e6, 0x38d01377, 0xbe5466cf, 0x34e90c6c,
	        0xc0ac29b7, 0xc97c50dd, 0x3f84d5b5, 0xb5470917,
	        0x9216d5d9, 0x8979fb1b
        );
        
        $this->S = array(
            array(
         0xd1310ba6, 0x98dfb5ac, 0x2ffd72db, 0xd01adfb7,
	     0xb8e1afed, 0x6a267e96, 0xba7c9045, 0xf12c7f99,
	     0x24a19947, 0xb3916cf7, 0x0801f2e2, 0x858efc16,
	     0x636920d8, 0x71574e69, 0xa458fea3, 0xf4933d7e,
	     0x0d95748f, 0x728eb658, 0x718bcd58, 0x82154aee,
	     0x7b54a41d, 0xc25a59b5, 0x9c30d539, 0x2af26013,
	     0xc5d1b023, 0x286085f0, 0xca417918, 0xb8db38ef,
	     0x8e79dcb0, 0x603a180e, 0x6c9e0e8b, 0xb01e8a3e,
	     0xd71577c1, 0xbd314b27, 0x78af2fda, 0x55605c60,
	     0xe65525f3, 0xaa55ab94, 0x57489862, 0x63e81440,
	     0x55ca396a, 0x2aab10b6, 0xb4cc5c34, 0x1141e8ce,
	     0xa15486af, 0x7c72e993, 0xb3ee1411, 0x636fbc2a,
	     0x2ba9c55d, 0x741831f6, 0xce5c3e16, 0x9b87931e,
	     0xafd6ba33, 0x6c24cf5c, 0x7a325381, 0x28958677,
	     0x3b8f4898, 0x6b4bb9af, 0xc4bfe81b, 0x66282193,
	     0x61d809cc, 0xfb21a991, 0x487cac60, 0x5dec8032,
	     0xef845d5d, 0xe98575b1, 0xdc262302, 0xeb651b88,
	     0x23893e81, 0xd396acc5, 0x0f6d6ff3, 0x83f44239,
	     0x2e0b4482, 0xa4842004, 0x69c8f04a, 0x9e1f9b5e,
	     0x21c66842, 0xf6e96c9a, 0x670c9c61, 0xabd388f0,
	     0x6a51a0d2, 0xd8542f68, 0x960fa728, 0xab5133a3,
	     0x6eef0b6c, 0x137a3be4, 0xba3bf050, 0x7efb2a98,
	     0xa1f1651d, 0x39af0176, 0x66ca593e, 0x82430e88,
	     0x8cee8619, 0x456f9fb4, 0x7d84a5c3, 0x3b8b5ebe,
	     0xe06f75d8, 0x85c12073, 0x401a449f, 0x56c16aa6,
	     0x4ed3aa62, 0x363f7706, 0x1bfedf72, 0x429b023d,
	     0x37d0d724, 0xd00a1248, 0xdb0fead3, 0x49f1c09b,
	     0x075372c9, 0x80991b7b, 0x25d479d8, 0xf6e8def7,
	     0xe3fe501a, 0xb6794c3b, 0x976ce0bd, 0x04c006ba,
	     0xc1a94fb6, 0x409f60c4, 0x5e5c9ec2, 0x196a2463,
	     0x68fb6faf, 0x3e6c53b5, 0x1339b2eb, 0x3b52ec6f,
	     0x6dfc511f, 0x9b30952c, 0xcc814544, 0xaf5ebd09,
	     0xbee3d004, 0xde334afd, 0x660f2807, 0x192e4bb3,
	     0xc0cba857, 0x45c8740f, 0xd20b5f39, 0xb9d3fbdb,
	     0x5579c0bd, 0x1a60320a, 0xd6a100c6, 0x402c7279,
	     0x679f25fe, 0xfb1fa3cc, 0x8ea5e9f8, 0xdb3222f8,
	     0x3c7516df, 0xfd616b15, 0x2f501ec8, 0xad0552ab,
	     0x323db5fa, 0xfd238760, 0x53317b48, 0x3e00df82,
	     0x9e5c57bb, 0xca6f8ca0, 0x1a87562e, 0xdf1769db,
	     0xd542a8f6, 0x287effc3, 0xac6732c6, 0x8c4f5573,
	     0x695b27b0, 0xbbca58c8, 0xe1ffa35d, 0xb8f011a0,
	     0x10fa3d98, 0xfd2183b8, 0x4afcb56c, 0x2dd1d35b,
	     0x9a53e479, 0xb6f84565, 0xd28e49bc, 0x4bfb9790,
	     0xe1ddf2da, 0xa4cb7e33, 0x62fb1341, 0xcee4c6e8,
	     0xef20cada, 0x36774c01, 0xd07e9efe, 0x2bf11fb4,
	     0x95dbda4d, 0xae909198, 0xeaad8e71, 0x6b93d5a0,
	     0xd08ed1d0, 0xafc725e0, 0x8e3c5b2f, 0x8e7594b7,
	     0x8ff6e2fb, 0xf2122b64, 0x8888b812, 0x900df01c,
	     0x4fad5ea0, 0x688fc31c, 0xd1cff191, 0xb3a8c1ad,
	     0x2f2f2218, 0xbe0e1777, 0xea752dfe, 0x8b021fa1,
	     0xe5a0cc0f, 0xb56f74e8, 0x18acf3d6, 0xce89e299,
	     0xb4a84fe0, 0xfd13e0b7, 0x7cc43b81, 0xd2ada8d9,
	     0x165fa266, 0x80957705, 0x93cc7314, 0x211a1477,
	     0xe6ad2065, 0x77b5fa86, 0xc75442f5, 0xfb9d35cf,
	     0xebcdaf0c, 0x7b3e89a0, 0xd6411bd3, 0xae1e7e49,
	     0x00250e2d, 0x2071b35e, 0x226800bb, 0x57b8e0af,
	     0x2464369b, 0xf009b91e, 0x5563911d, 0x59dfa6aa,
	     0x78c14389, 0xd95a537f, 0x207d5ba2, 0x02e5b9c5,
	     0x83260376, 0x6295cfa9, 0x11c81968, 0x4e734a41,
	     0xb3472dca, 0x7b14a94a, 0x1b510052, 0x9a532915,
	     0xd60f573f, 0xbc9bc6e4, 0x2b60a476, 0x81e67400,
	     0x08ba6fb5, 0x571be91f, 0xf296ec6b, 0x2a0dd915,
	     0xb6636521, 0xe7b9f9b6, 0xff34052e, 0xc5855664,
	     0x53b02d5d, 0xa99f8fa1, 0x08ba4799, 0x6e85076a
            ),
            array(
        0x4b7a70e9, 0xb5b32944, 0xdb75092e, 0xc4192623,
	     0xad6ea6b0, 0x49a7df7d, 0x9cee60b8, 0x8fedb266,
	     0xecaa8c71, 0x699a17ff, 0x5664526c, 0xc2b19ee1,
	     0x193602a5, 0x75094c29, 0xa0591340, 0xe4183a3e,
	     0x3f54989a, 0x5b429d65, 0x6b8fe4d6, 0x99f73fd6,
	     0xa1d29c07, 0xefe830f5, 0x4d2d38e6, 0xf0255dc1,
	     0x4cdd2086, 0x8470eb26, 0x6382e9c6, 0x021ecc5e,
	     0x09686b3f, 0x3ebaefc9, 0x3c971814, 0x6b6a70a1,
	     0x687f3584, 0x52a0e286, 0xb79c5305, 0xaa500737,
	     0x3e07841c, 0x7fdeae5c, 0x8e7d44ec, 0x5716f2b8,
	     0xb03ada37, 0xf0500c0d, 0xf01c1f04, 0x0200b3ff,
	     0xae0cf51a, 0x3cb574b2, 0x25837a58, 0xdc0921bd,
	     0xd19113f9, 0x7ca92ff6, 0x94324773, 0x22f54701,
	     0x3ae5e581, 0x37c2dadc, 0xc8b57634, 0x9af3dda7,
	     0xa9446146, 0x0fd0030e, 0xecc8c73e, 0xa4751e41,
	     0xe238cd99, 0x3bea0e2f, 0x3280bba1, 0x183eb331,
	     0x4e548b38, 0x4f6db908, 0x6f420d03, 0xf60a04bf,
	     0x2cb81290, 0x24977c79, 0x5679b072, 0xbcaf89af,
	     0xde9a771f, 0xd9930810, 0xb38bae12, 0xdccf3f2e,
	     0x5512721f, 0x2e6b7124, 0x501adde6, 0x9f84cd87,
	     0x7a584718, 0x7408da17, 0xbc9f9abc, 0xe94b7d8c,
	     0xec7aec3a, 0xdb851dfa, 0x63094366, 0xc464c3d2,
	     0xef1c1847, 0x3215d908, 0xdd433b37, 0x24c2ba16,
	     0x12a14d43, 0x2a65c451, 0x50940002, 0x133ae4dd,
	     0x71dff89e, 0x10314e55, 0x81ac77d6, 0x5f11199b,
	     0x043556f1, 0xd7a3c76b, 0x3c11183b, 0x5924a509,
	     0xf28fe6ed, 0x97f1fbfa, 0x9ebabf2c, 0x1e153c6e,
	     0x86e34570, 0xeae96fb1, 0x860e5e0a, 0x5a3e2ab3,
	     0x771fe71c, 0x4e3d06fa, 0x2965dcb9, 0x99e71d0f,
	     0x803e89d6, 0x5266c825, 0x2e4cc978, 0x9c10b36a,
	     0xc6150eba, 0x94e2ea78, 0xa5fc3c53, 0x1e0a2df4,
	     0xf2f74ea7, 0x361d2b3d, 0x1939260f, 0x19c27960,
	     0x5223a708, 0xf71312b6, 0xebadfe6e, 0xeac31f66,
	     0xe3bc4595, 0xa67bc883, 0xb17f37d1, 0x018cff28,
	     0xc332ddef, 0xbe6c5aa5, 0x65582185, 0x68ab9802,
	     0xeecea50f, 0xdb2f953b, 0x2aef7dad, 0x5b6e2f84,
	     0x1521b628, 0x29076170, 0xecdd4775, 0x619f1510,
	     0x13cca830, 0xeb61bd96, 0x0334fe1e, 0xaa0363cf,
	     0xb5735c90, 0x4c70a239, 0xd59e9e0b, 0xcbaade14,
	     0xeecc86bc, 0x60622ca7, 0x9cab5cab, 0xb2f3846e,
	     0x648b1eaf, 0x19bdf0ca, 0xa02369b9, 0x655abb50,
	     0x40685a32, 0x3c2ab4b3, 0x319ee9d5, 0xc021b8f7,
	     0x9b540b19, 0x875fa099, 0x95f7997e, 0x623d7da8,
	     0xf837889a, 0x97e32d77, 0x11ed935f, 0x16681281,
	     0x0e358829, 0xc7e61fd6, 0x96dedfa1, 0x7858ba99,
	     0x57f584a5, 0x1b227263, 0x9b83c3ff, 0x1ac24696,
	     0xcdb30aeb, 0x532e3054, 0x8fd948e4, 0x6dbc3128,
	     0x58ebf2ef, 0x34c6ffea, 0xfe28ed61, 0xee7c3c73,
	     0x5d4a14d9, 0xe864b7e3, 0x42105d14, 0x203e13e0,
	     0x45eee2b6, 0xa3aaabea, 0xdb6c4f15, 0xfacb4fd0,
	     0xc742f442, 0xef6abbb5, 0x654f3b1d, 0x41cd2105,
	     0xd81e799e, 0x86854dc7, 0xe44b476a, 0x3d816250,
	     0xcf62a1f2, 0x5b8d2646, 0xfc8883a0, 0xc1c7b6a3,
	     0x7f1524c3, 0x69cb7492, 0x47848a0b, 0x5692b285,
	     0x095bbf00, 0xad19489d, 0x1462b174, 0x23820e00,
	     0x58428d2a, 0x0c55f5ea, 0x1dadf43e, 0x233f7061,
	     0x3372f092, 0x8d937e41, 0xd65fecf1, 0x6c223bdb,
	     0x7cde3759, 0xcbee7460, 0x4085f2a7, 0xce77326e,
	     0xa6078084, 0x19f8509e, 0xe8efd855, 0x61d99735,
	     0xa969a7aa, 0xc50c06c2, 0x5a04abfc, 0x800bcadc,
	     0x9e447a2e, 0xc3453484, 0xfdd56705, 0x0e1e9ec9,
	     0xdb73dbd3, 0x105588cd, 0x675fda79, 0xe3674340,
	     0xc5c43465, 0x713e38d8, 0x3d28f89e, 0xf16dff20,
	     0x153e21e7, 0x8fb03d4a, 0xe6e39f2b, 0xdb83adf7
            ),
            array(
      0xe93d5a68, 0x948140f7, 0xf64c261c, 0x94692934,
	 0x411520f7, 0x7602d4f7, 0xbcf46b2e, 0xd4a20068,
	 0xd4082471, 0x3320f46a, 0x43b7d4b7, 0x500061af,
	 0x1e39f62e, 0x97244546, 0x14214f74, 0xbf8b8840,
	 0x4d95fc1d, 0x96b591af, 0x70f4ddd3, 0x66a02f45,
	 0xbfbc09ec, 0x03bd9785, 0x7fac6dd0, 0x31cb8504,
	 0x96eb27b3, 0x55fd3941, 0xda2547e6, 0xabca0a9a,
	 0x28507825, 0x530429f4, 0x0a2c86da, 0xe9b66dfb,
	 0x68dc1462, 0xd7486900, 0x680ec0a4, 0x27a18dee,
	 0x4f3ffea2, 0xe887ad8c, 0xb58ce006, 0x7af4d6b6,
	 0xaace1e7c, 0xd3375fec, 0xce78a399, 0x406b2a42,
	 0x20fe9e35, 0xd9f385b9, 0xee39d7ab, 0x3b124e8b,
	 0x1dc9faf7, 0x4b6d1856, 0x26a36631, 0xeae397b2,
	 0x3a6efa74, 0xdd5b4332, 0x6841e7f7, 0xca7820fb,
	 0xfb0af54e, 0xd8feb397, 0x454056ac, 0xba489527,
	 0x55533a3a, 0x20838d87, 0xfe6ba9b7, 0xd096954b,
	 0x55a867bc, 0xa1159a58, 0xcca92963, 0x99e1db33,
	 0xa62a4a56, 0x3f3125f9, 0x5ef47e1c, 0x9029317c,
	 0xfdf8e802, 0x04272f70, 0x80bb155c, 0x05282ce3,
	 0x95c11548, 0xe4c66d22, 0x48c1133f, 0xc70f86dc,
	 0x07f9c9ee, 0x41041f0f, 0x404779a4, 0x5d886e17,
	 0x325f51eb, 0xd59bc0d1, 0xf2bcc18f, 0x41113564,
	 0x257b7834, 0x602a9c60, 0xdff8e8a3, 0x1f636c1b,
	 0x0e12b4c2, 0x02e1329e, 0xaf664fd1, 0xcad18115,
	 0x6b2395e0, 0x333e92e1, 0x3b240b62, 0xeebeb922,
	 0x85b2a20e, 0xe6ba0d99, 0xde720c8c, 0x2da2f728,
	 0xd0127845, 0x95b794fd, 0x647d0862, 0xe7ccf5f0,
	 0x5449a36f, 0x877d48fa, 0xc39dfd27, 0xf33e8d1e,
	 0x0a476341, 0x992eff74, 0x3a6f6eab, 0xf4f8fd37,
	 0xa812dc60, 0xa1ebddf8, 0x991be14c, 0xdb6e6b0d,
	 0xc67b5510, 0x6d672c37, 0x2765d43b, 0xdcd0e804,
	 0xf1290dc7, 0xcc00ffa3, 0xb5390f92, 0x690fed0b,
	 0x667b9ffb, 0xcedb7d9c, 0xa091cf0b, 0xd9155ea3,
	 0xbb132f88, 0x515bad24, 0x7b9479bf, 0x763bd6eb,
	 0x37392eb3, 0xcc115979, 0x8026e297, 0xf42e312d,
	 0x6842ada7, 0xc66a2b3b, 0x12754ccc, 0x782ef11c,
	 0x6a124237, 0xb79251e7, 0x06a1bbe6, 0x4bfb6350,
	 0x1a6b1018, 0x11caedfa, 0x3d25bdd8, 0xe2e1c3c9,
	 0x44421659, 0x0a121386, 0xd90cec6e, 0xd5abea2a,
	 0x64af674e, 0xda86a85f, 0xbebfe988, 0x64e4c3fe,
	 0x9dbc8057, 0xf0f7c086, 0x60787bf8, 0x6003604d,
	 0xd1fd8346, 0xf6381fb0, 0x7745ae04, 0xd736fccc,
	 0x83426b33, 0xf01eab71, 0xb0804187, 0x3c005e5f,
	 0x77a057be, 0xbde8ae24, 0x55464299, 0xbf582e61,
	 0x4e58f48f, 0xf2ddfda2, 0xf474ef38, 0x8789bdc2,
	 0x5366f9c3, 0xc8b38e74, 0xb475f255, 0x46fcd9b9,
	 0x7aeb2661, 0x8b1ddf84, 0x846a0e79, 0x915f95e2,
	 0x466e598e, 0x20b45770, 0x8cd55591, 0xc902de4c,
	 0xb90bace1, 0xbb8205d0, 0x11a86248, 0x7574a99e,
	 0xb77f19b6, 0xe0a9dc09, 0x662d09a1, 0xc4324633,
	 0xe85a1f02, 0x09f0be8c, 0x4a99a025, 0x1d6efe10,
	 0x1ab93d1d, 0x0ba5a4df, 0xa186f20f, 0x2868f169,
	 0xdcb7da83, 0x573906fe, 0xa1e2ce9b, 0x4fcd7f52,
	 0x50115e01, 0xa70683fa, 0xa002b5c4, 0x0de6d027,
	 0x9af88c27, 0x773f8641, 0xc3604c06, 0x61a806b5,
	 0xf0177a28, 0xc0f586e0, 0x006058aa, 0x30dc7d62,
	 0x11e69ed7, 0x2338ea63, 0x53c2dd94, 0xc2c21634,
	 0xbbcbee56, 0x90bcb6de, 0xebfc7da1, 0xce591d76,
	 0x6f05e409, 0x4b7c0188, 0x39720a3d, 0x7c927c24,
	 0x86e3725f, 0x724d9db9, 0x1ac15bb4, 0xd39eb8fc,
	 0xed545578, 0x08fca5b5, 0xd83d7cd3, 0x4dad0fc4,
	 0x1e50ef5e, 0xb161e6f8, 0xa28514d9, 0x6c51133c,
	 0x6fd5c7e7, 0x56e14ec4, 0x362abfce, 0xddc6c837,
	 0xd79a3234, 0x92638212, 0x670efa8e, 0x406000e0
            ),
            array(
0x3a39ce37, 0xd3faf5cf, 0xabc27737, 0x5ac52d1b,
	 0x5cb0679e, 0x4fa33742, 0xd3822740, 0x99bc9bbe,
	 0xd5118e9d, 0xbf0f7315, 0xd62d1c7e, 0xc700c47b,
	 0xb78c1b6b, 0x21a19045, 0xb26eb1be, 0x6a366eb4,
	 0x5748ab2f, 0xbc946e79, 0xc6a376d2, 0x6549c2c8,
	 0x530ff8ee, 0x468dde7d, 0xd5730a1d, 0x4cd04dc6,
	 0x2939bbdb, 0xa9ba4650, 0xac9526e8, 0xbe5ee304,
	 0xa1fad5f0, 0x6a2d519a, 0x63ef8ce2, 0x9a86ee22,
	 0xc089c2b8, 0x43242ef6, 0xa51e03aa, 0x9cf2d0a4,
	 0x83c061ba, 0x9be96a4d, 0x8fe51550, 0xba645bd6,
	 0x2826a2f9, 0xa73a3ae1, 0x4ba99586, 0xef5562e9,
	 0xc72fefd3, 0xf752f7da, 0x3f046f69, 0x77fa0a59,
	 0x80e4a915, 0x87b08601, 0x9b09e6ad, 0x3b3ee593,
	 0xe990fd5a, 0x9e34d797, 0x2cf0b7d9, 0x022b8b51,
	 0x96d5ac3a, 0x017da67d, 0xd1cf3ed6, 0x7c7d2d28,
	 0x1f9f25cf, 0xadf2b89b, 0x5ad6b472, 0x5a88f54c,
	 0xe029ac71, 0xe019a5e6, 0x47b0acfd, 0xed93fa9b,
	 0xe8d3c48d, 0x283b57cc, 0xf8d56629, 0x79132e28,
	 0x785f0191, 0xed756055, 0xf7960e44, 0xe3d35e8c,
	 0x15056dd4, 0x88f46dba, 0x03a16125, 0x0564f0bd,
	 0xc3eb9e15, 0x3c9057a2, 0x97271aec, 0xa93a072a,
	 0x1b3f6d9b, 0x1e6321f5, 0xf59c66fb, 0x26dcf319,
	 0x7533d928, 0xb155fdf5, 0x03563482, 0x8aba3cbb,
	 0x28517711, 0xc20ad9f8, 0xabcc5167, 0xccad925f,
	 0x4de81751, 0x3830dc8e, 0x379d5862, 0x9320f991,
	 0xea7a90c2, 0xfb3e7bce, 0x5121ce64, 0x774fbe32,
	 0xa8b6e37e, 0xc3293d46, 0x48de5369, 0x6413e680,
	 0xa2ae0810, 0xdd6db224, 0x69852dfd, 0x09072166,
	 0xb39a460a, 0x6445c0dd, 0x586cdecf, 0x1c20c8ae,
	 0x5bbef7dd, 0x1b588d40, 0xccd2017f, 0x6bb4e3bb,
	 0xdda26a7e, 0x3a59ff45, 0x3e350a44, 0xbcb4cdd5,
	 0x72eacea8, 0xfa6484bb, 0x8d6612ae, 0xbf3c6f47,
	 0xd29be463, 0x542f5d9e, 0xaec2771b, 0xf64e6370,
	 0x740e0d8d, 0xe75b1357, 0xf8721671, 0xaf537d5d,
	 0x4040cb08, 0x4eb4e2cc, 0x34d2466a, 0x0115af84,
	 0xe1b00428, 0x95983a1d, 0x06b89fb4, 0xce6ea048,
	 0x6f3f3b82, 0x3520ab82, 0x011a1d4b, 0x277227f8,
	 0x611560b1, 0xe7933fdc, 0xbb3a792b, 0x344525bd,
	 0xa08839e1, 0x51ce794b, 0x2f32c9b7, 0xa01fbac9,
	 0xe01cc87e, 0xbcc7d1f6, 0xcf0111c3, 0xa1e8aac7,
	 0x1a908749, 0xd44fbd9a, 0xd0dadecb, 0xd50ada38,
	 0x0339c32a, 0xc6913667, 0x8df9317c, 0xe0b12b4f,
	 0xf79e59b7, 0x43f5bb3a, 0xf2d519ff, 0x27d9459c,
	 0xbf97222c, 0x15e6fc2a, 0x0f91fc71, 0x9b941525,
	 0xfae59361, 0xceb69ceb, 0xc2a86459, 0x12baa8d1,
	 0xb6c1075e, 0xe3056a0c, 0x10d25065, 0xcb03a442,
	 0xe0ec6e0e, 0x1698db3b, 0x4c98a0be, 0x3278e964,
	 0x9f1f9532, 0xe0d392df, 0xd3a0342b, 0x8971f21e,
	 0x1b0a7441, 0x4ba3348c, 0xc5be7120, 0xc37632d8,
	 0xdf359f8d, 0x9b992f2e, 0xe60b6f47, 0x0fe3f11d,
	 0xe54cda54, 0x1edad891, 0xce6279cf, 0xcd3e7e6f,
	 0x1618b166, 0xfd2c1d05, 0x848fd2c5, 0xf6fb2299,
	 0xf523f357, 0xa6327623, 0x93a83531, 0x56cccd02,
	 0xacf08162, 0x5a75ebb5, 0x6e163697, 0x88d273cc,
	 0xde966292, 0x81b949d0, 0x4c50901b, 0x71c65614,
	 0xe6c6c7bd, 0x327a140a, 0x45e1d006, 0xc3f27b9a,
	 0xc9aa53fd, 0x62a80f00, 0xbb25bfe2, 0x35bdd2f6,
	 0x71126905, 0xb2040222, 0xb6cbcf7c, 0xcd769c2b,
	 0x53113ec0, 0x1640e3d3, 0x38abbd60, 0x2547adf0,
	 0xba38209c, 0xf746ce76, 0x77afa1c5, 0x20756060,
	 0x85cbfe4e, 0x8ae88dd8, 0x7aaaf9b0, 0x4cf9aa7e,
	 0x1948c25c, 0x02fb8a8c, 0x01c36ae4, 0xd6ebe1f9,
	 0x90d4f869, 0xa65cdea0, 0x3f09252d, 0xc208e69f,
	 0xb74e6132, 0xce77e25b, 0x578fdfe3, 0x3ac372e6
            )
        );
    }
    
}


class FM_FormPageDisplayModule extends FM_Module
{
   private $validator_obj;
   private $uploader_obj;
   
   private $formdata_cookiname;
   private $formpage_cookiname;
   
   public function __construct()
   {
      parent::__construct();
      $this->formdata_cookiname = 'sfm_saved_form_data';
      $this->formpage_cookiname = 'sfm_saved_form_page_num';
   }

   function SetFormValidator(&$validator)
   {
      $this->validator_obj = &$validator;
   }
   
   function SetFileUploader(&$uploader)
   {
      $this->uploader_obj = &$uploader;
   }
   
   function getSerializer()
   {
        $tablename = 'sfm_'.substr($this->formname,0,32).'_saved';
        return new FM_SubmissionSerializer($this->config,$this->logger,$this->error_handler,$tablename);
   }
   
   function Process(&$continue)
   {
      $display_thankyou = true;

      $this->SaveCurrentPageToSession();
      
      if($this->NeedSaveAndClose())
      {
         $serializer = $this->getSerializer();
         
         $id = $serializer->SerializeToTable($this->SaveAllDataToArray());
         $this->AddToSerializedIDs($id);
         $id_encr = $this->ConvertID($id,/*encrypt*/true);
         
         $this->SaveFormDataToCookie($id_encr);
         
         $continue=false;
         $display_thankyou = false;
         $url = sfm_selfURL_abs().'?'.$this->config->reload_formvars_var.'='.$id_encr;
         $url ='<code>'.$url.'</code>';
         $msg = str_replace('{link}',$url,$this->config->saved_message_templ);
         echo $msg;
      }
      else
      if($this->NeedDisplayFormPage())
      {
         $this->DisplayFormPage($display_thankyou);
         $continue=false;
      }
      
      if($display_thankyou)
      {
         $this->LoadAllPageValuesFromSession($this->formvars,/*load files*/true,
                                          /*overwrite_existing*/false);
         $continue=true;
      }
   }
   
   function ConvertID($id,$encrypt)
   {
       $ret='';
       if($encrypt)
       {
            $ret = sfm_crypt_encrypt('x'.$id,$this->config->encr_key);
       }
       else
       {
            $ret = sfm_crypt_decrypt($id,$this->config->encr_key);
            $ret = str_replace('x','',$ret);
       }
       return $ret;
   }
   
   function Destroy()
   {
      if($this->globaldata->IsFormProcessingComplete())
      {
         $this->RemoveUnSerializedRows();
         
         $this->RemoveCookies();
      }
   }
    
   function NeedDisplayFormPage()
   {

      if($this->globaldata->IsButtonClicked('sfm_prev_page'))
      {
         return true;
      }
      elseif(false == isset($this->formvars[$this->config->form_submit_variable]))
      {
         return true;   
      }
      return false;
   }
      
   function NeedSaveAndClose()
   {
     if($this->globaldata->IsButtonClicked('sfm_save_n_close'))
     {
       return true;
     }
     return false;
   }


   
      
   function DisplayFormPage(&$display_thankyou)
   {
      $display_thankyou = false;
      
      $var_map = array();
      
      $var_map = array_merge($var_map,$this->config->element_info->default_values);
      
      $var_map[$this->config->error_display_variable]="";
      
      $this->LoadAllPageValuesFromSession($var_map,/*load files*/false,/*overwrite_existing*/true);
      

      
      $id_reload = $this->GetReloadFormID();
      if(false !== $id_reload)
      {
        $id = $id_reload;
        
        $this->AddToSerializedIDs($id);
        
        $serializer = $this->getSerializer();
        $all_formdata = array();
        $serializer->RecreateFromTable($id,$all_formdata,/*reset*/false);
        $this->LoadAllDataFromArray($all_formdata,$var_map,$page_num);
        
        $this->common_objs->formpage_renderer->DisplayFormPage($page_num,$var_map);
      }
      elseif($this->globaldata->IsButtonClicked('sfm_prev_page'))
      {
         $this->common_objs->formpage_renderer->DisplayPrevPage($var_map);
      }
      elseif($this->common_objs->formpage_renderer->IsPageNumSet())
      {
        if(isset($this->validator_obj) && 
        !$this->validator_obj->ValidateCurrentPage($var_map))
        {
            return false;
        }
         $this->logger->LogInfo("FormPageDisplayModule:  DisplayNextPage");
         $this->common_objs->formpage_renderer->DisplayNextPage($var_map,$display_thankyou);
      }
      else
      {//display the very first page
        $this->globaldata->RecordVariables();
        
        if($this->config->load_values_from_url)
        {
            $this->LoadValuesFromURL($var_map);
        }
        
        $this->logger->LogInfo("FormPageDisplayModule:  DisplayFirstPage");
        $this->common_objs->formpage_renderer->DisplayFirstPage($var_map);
      }
      return true;
   }
   
   function LoadValuesFromURL(&$varmap)
   {
        foreach($this->globaldata->get_vars as $gk => $gv)
        {
            
            if(!$this->config->element_info->IsElementPresent($gk))
            { continue; }
            
            $pagenum = $this->config->element_info->GetPageNum($gk);
            
            if($pagenum == 0)
            {
                $varmap[$gk] = $gv;
            }
            else
            {
                 $varname = $this->GetPageDataVarName($pagenum);
                 
                 if(empty($this->globaldata->session[$varname]))
                 {
                    $this->globaldata->session[$varname] = array();
                 }
                 $this->globaldata->session[$varname][$gk] = $gv;
            }
        }
   }
   function AddToSerializedIDs($id)
   {
        if(!isset($this->globaldata->session['sfm_serialized_ids']))
        {
            $this->globaldata->session['sfm_serialized_ids'] = array();
        }
        $this->globaldata->session['sfm_serialized_ids'][$id] = 'k';
   }
   
   function RemoveUnSerializedRows()
   {
        if(empty($this->globaldata->session['sfm_serialized_ids']))
        {
            return;
        }
        $serializer = $this->getSerializer();
        $serializer->Login();
        foreach($this->globaldata->session['sfm_serialized_ids'] as $id => $val)
        {
            $serializer->DeleteRow($id);
        }
        $serializer->Close();
   }
   
   function GetReloadFormID()
   {
        $id_encr='';
        if(!empty($_GET[$this->config->reload_formvars_var]))
        {
            $id_encr = $_GET[$this->config->reload_formvars_var];
        }
        elseif($this->IsFormReloadCookieSet())
        {
            $id_encr = $_COOKIE[$this->formdata_cookiname];
        }
        if(!empty($id_encr))
        {
            $id = $this->ConvertID($id_encr,false/*encrypt*/);
            return $id;
        }
        return false;
   }
         
   function IsFormReloadCookieSet()
   {
      if(!$this->common_objs->formpage_renderer->IsPageNumSet() &&
      isset($_COOKIE[$this->formdata_cookiname]) )
      {
         return true;
      }
      return false;
   }
   
   function RemoveControlVariables($session_varname)
   {
        $this->RemoveButtonVariableFromSession($session_varname,'sfm_prev_page');
        $this->RemoveButtonVariableFromSession($session_varname,'sfm_save_n_close');
        $this->RemoveButtonVariableFromSession($session_varname,'sfm_prev_page');
        $this->RemoveButtonVariableFromSession($session_varname,'sfm_confirm_edit');
        $this->RemoveButtonVariableFromSession($session_varname,'sfm_confirm');
   }
   
   function RemoveButtonVariableFromSession($sess_var,$varname)
   {
        unset($this->globaldata->session[$sess_var][$varname]);
        unset($this->globaldata->session[$sess_var][$varname."_x"]);
        unset($this->globaldata->session[$sess_var][$varname."_y"]);
   }
   
   function RemoveCookies()
   {
      if(isset($_COOKIE[$this->formdata_cookiname]))
      {
         sfm_clearcookie($this->formdata_cookiname);
         sfm_clearcookie($this->formpage_cookiname);
      }
   }

    function SaveAllDataToArray()
    {
        $all_formdata = $this->globaldata->session;
        $all_formdata['sfm_latest_page_num'] = $this->common_objs->formpage_renderer->GetCurrentPageNum();
        
        return $all_formdata;
    }
   function SaveFormDataToCookie($id_encr)
   {
      setcookie($this->formdata_cookiname,$id_encr,mktime()+(86400*30));
   }
   
   function LoadAllDataFromArray($all_formdata,&$var_map,&$page_num)
   {
      if(isset($all_formdata['sfm_latest_page_num']))
      {
         $page_num = intval($all_formdata['sfm_latest_page_num']);
      }
      else
      {
         $page_num =0;
      }
      unset($all_formdata['sfm_latest_page_num']);
      
      $this->globaldata->RecreateSessionValues($all_formdata);
      
      $this->LoadFormPageFromSession($var_map,$page_num);   
   }
   
   function LoadAllPageValuesFromSession(&$varmap,$load_files,$overwrite_existing=true)
   {
      if(!$this->common_objs->formpage_renderer->IsPageNumSet())
      {
         return;
      }

      $npages = $this->common_objs->formpage_renderer->GetNumPages();

      $this->logger->LogInfo("LoadAllPageValuesFromSession npages $npages");

      for($p=0; $p < $npages; $p++)
      {
         $varname = $this->GetPageDataVarName($p);
         if(isset($this->globaldata->session[$varname]))
         {
            if($overwrite_existing)
            {
               $varmap = array_merge($varmap,$this->globaldata->session[$varname]);
            }
            else
            {
               //Array union: donot overwrite values
               $varmap = $varmap + $this->globaldata->session[$varname]; 
            }
            
            if($load_files && isset($this->uploader_obj))
            {
               $this->uploader_obj->LoadFileListFromSession($varname);
            }
         }
      }//for
     
   }//function
   
   function LoadFormPageFromSession(&$var_map, $page_num)
   {
      $varname = $this->GetPageDataVarName($page_num);
      if(isset($this->globaldata->session[$varname]))
      {
         $var_map = array_merge($var_map,$this->globaldata->session[$varname]);
         $this->logger->LogInfo(" LoadFormPageFromSession  var_map ".var_export($var_map,TRUE));
      }
   }
   
 
   function SaveCurrentPageToSession()
   {
      if($this->common_objs->formpage_renderer->IsPageNumSet())
      {
         $page_num = $this->common_objs->formpage_renderer->GetCurrentPageNum();
         
         $varname = $this->GetPageDataVarName($page_num);
         
         $this->globaldata->session[$varname] = $this->formvars;
         
         $this->RemoveControlVariables($varname);
         
         if(isset($this->uploader_obj))
         {
            $this->uploader_obj->HandleNativeFileUpload();
         }
         
         $this->logger->LogInfo(" SaveCurrentPageToSession _SESSION(varname) "
         .var_export($this->globaldata->session[$varname],TRUE));
      }
   }
   
   function GetPageDataVarName($page_num)
   {
      return "sfm_form_page_".$page_num."_data";
   }

   function DisplayUsingTemplate(&$var_map)
   {
      $merge = new FM_PageMerger();
      if(false == $merge->Merge($this->config->form_page_code,$var_map))
      {
         $this->error_handler->HandleConfigError(_("Failed merging form page"));
         return false;
      }
      $strdisp = $merge->getMessageBody();
      echo $strdisp;
   }
}

function sfm_clearcookie( $inKey ) 
{
    setcookie( $inKey , '' , time()-3600 );
    unset( $_COOKIE[$inKey] );
} 


?>