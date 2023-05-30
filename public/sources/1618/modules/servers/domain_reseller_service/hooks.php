<?php
use \Illuminate\Database\Capsule\Manager as Capsule;
function hook_domain_reseller_service_charge($params)
{
   
    $invoice = Capsule::table('domain_reseller_management_invoice')->where('invoiceid', '=', $params['invoiceid'])->first();
    
    if(!$invoice){
        return '';
    }
    try {
        require_once dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'addons' . DIRECTORY_SEPARATOR . 'domain_reseller_management' . DIRECTORY_SEPARATOR . 'unirest-php' . DIRECTORY_SEPARATOR . 'Unirest.php';
        require_once dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'addons' . DIRECTORY_SEPARATOR . 'domain_reseller_management' . DIRECTORY_SEPARATOR . 'utils.php';

        $command = "getinvoice";
        $adminuser = domainResellerGetAdminUserName();
        $values["invoiceid"] = $params['invoiceid'];
        $results = localAPI($command,$values,$adminuser);

        $charge = (int)$results['subtotal'];
        
        $units = domainResellerGetUnits(true);
        if($units == 'toman') {
            //$results = domain_reseller_service_xml_parser($results);
            $charge = (int)$results['subtotal'] * 10;
        }
//        $invoice = Capsule::table('domain_reseller_management_invoice')->where('invoiceid', '=', $params['invoiceid'])->first();
//
//        if(!$invoice){
//            throw new Exception('invoice not found');
//        }
        
        $reseller_id = Capsule::table('domain_reseller_management_setting')->where('item', '=', 'rid')->where('key','=',$invoice->serviceid)->first();
        if (!$reseller_id) {
            throw new Exception('reseller id not found');
        }
  
        $apiBaseUrl = domainResellerGetApiUrl();

        $token = 'Bearer ' . domainResellerGetToken();
      
        $headers = array("Accept" => "application/json", "Content-Type" => "application/json; charset=UTF-8");
        $headers['Authorization'] = $token;
        $regParams['id'] = $reseller_id->value;
        $regParams['charge'] = $charge;
        $regParams['message'] = $params['invoiceid'];
        $regParams['type'] = 'charge';
        $CrequestParams = $regParams;
        $CrequestParams['requestTime'] = time();
        $Cstatus = 'success';

        $regParams = json_encode($regParams);

        $response = domainResellerUnirest\Request::post($apiBaseUrl . 'reseller/charge', $headers, $regParams);
        if ($response->code == 200) {

            $CrequestParams['responseTime'] = time();
            $respMsg = 1;
            #bonus charge

//            $domain_reseller_management_setting = Capsule::table('domain_reseller_management_setting')->where('item', '=', 'formula')->get();
//            $domain_reseller_management_formula = [];

//            if(!empty($domain_reseller_management_setting)){
//                foreach ($domain_reseller_management_setting as $d){
//                    $domain_reseller_management_formula[$d->value] = $d->key;
//                }
//                asort($domain_reseller_management_formula,SORT_NUMERIC);
//                $bonus = 0;
//                $lastBonus = 0;
//                foreach ($domain_reseller_management_formula as $p=>$f){
//                    if($f >= $charge){
//                        $bonus = $p;
//                        break;
//                    }
//                    $lastBonus = $p;
//                }
//                if($charge != 0 && $bonus == 0){
//                    $bonus = $lastBonus;
//                }
//                $extraCharge = ($charge * $bonus) / 100;
//                $regParams = [];
//                $regParams['id'] = $reseller_id->value;
//                $regParams['charge'] = $extraCharge;
//                $regParams['message'] = $params['invoiceid'];
//                $regParams['type'] = 'bonus';
//                $BrequestParams = $regParams;
//                $BrequestParams['requestTime'] = time();
//                $Bstatus = 'success';
//                $regParams = json_encode($regParams);
//                $response = domainResellerUnirest\Request::post($apiBaseUrl . 'reseller/charge', $headers, $regParams);
//                if ($response->code == 200) {
//                    $BrequestParams['responseTime'] = time();
//                } else {
//                    $BrequestParams['responseTime'] = time();
//                    $Bstatus = 'failed';
//                }
//                domainResellerLogger('module_reseller_bonus', $_SESSION['adminid'], $params['userid'], '', '', $BrequestParams, $response->body, $reseller_id->value, $Bstatus);
//            }

        } else {
            $CrequestParams['responseTime'] = time();
            $Cstatus = 'failed';
            $respMsg = isset($response->body->errorDetails) ? $response->body->errorDetails : 'server error';
        }
        domainResellerLogger('module_reseller_charge', $_SESSION['adminid'], $params['userid'], '', '', $CrequestParams, $response->body, $reseller_id->value, $Cstatus);
    } catch (Exception $ex) {
        domainResellerLogger('module_reseller_charge_ex', $_SESSION['adminid'], $params['userid'], '', '', $params, domainResellerResponseTemplate($ex->getMessage(),'06220001','failed'),'', 'failed');
        $respMsg = $ex->getMessage();
    }

}

add_hook('InvoicePaid', 1, 'hook_domain_reseller_service_charge');


function domain_reseller_service_xml_parser($rawxml) {
    $xml_parser = xml_parser_create();
    xml_parse_into_struct($xml_parser, $rawxml, $vals, $index);
    xml_parser_free($xml_parser);
    $params = array();
    $level = array();
    $alreadyused = array();
    $x=0;
    foreach ($vals as $xml_elem) {
        if ($xml_elem['type'] == 'open') {
            if (in_array($xml_elem['tag'],$alreadyused)) {
                $x++;
                $xml_elem['tag'] = $xml_elem['tag'].$x;
            }
            $level[$xml_elem['level']] = $xml_elem['tag'];
            $alreadyused[] = $xml_elem['tag'];
        }
        if ($xml_elem['type'] == 'complete') {
            $start_level = 1;
            $php_stmt = '$params';
            while($start_level < $xml_elem['level']) {
                $php_stmt .= '[$level['.$start_level.']]';
                $start_level++;
            }
            $php_stmt .= '[$xml_elem[\'tag\']] = $xml_elem[\'value\'];';
            @eval($php_stmt);
        }
    }
    return($params);
}
