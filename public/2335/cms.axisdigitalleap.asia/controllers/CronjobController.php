<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use app\models\Product;
use app\models\StockLog;
use app\models\Countrycode;
use app\models\Shippingrate;
use app\models\Courierfsc;
use app\models\Courierpsc;
use app\models\Orders;
use app\models\OrdersOwn;
use app\models\PackageProductList;

class CronjobController extends Controller {

    public function actionUpdatestatus() {

        ini_set('max_execution_time', 3000);

        //get all order in pending
        $model = Orders::find()->where(['OR', ['=', 'order_status', 'pending'], ['=', 'order_status', 'under process'], ['=', 'order_status', 'on hold']])->groupBy(['invoice_id'])->all();
        if (!empty($model)) {
            foreach ($model as $row) {
                //check status at memberv2 using curl
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, Yii::$app->params['apiGetOrderStatus']);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, "id=$row->invoice_id");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $data = curl_exec($ch);
                curl_close($ch);
                $dataArr = json_decode($data, true);

                //update the order_status & order_date
                if (!empty($dataArr)) {
                    $sql = "UPDATE orders SET 
                    order_status='" . $dataArr['order_status'] . "', 
                    order_date='" . $dataArr['order_date'] . "',
                    shipping_fee='" . $dataArr['shipping_fee'] . "',
                    shipping_cost='" . $dataArr['shipping_cost'] . "',
                    tracking_code='" . $dataArr['tracking_code'] . "',
                    material='" . $dataArr['material_id'] . "',
                    material_fee='" . $dataArr['material_fee'] . "',
                    weight='" . $dataArr['weight'] . "',
                    courier='" . $dataArr['courier'] . "',
                    shipping_declare='" . $dataArr['shipping_declare'] . "'
                    WHERE invoice_id='" . $row->invoice_id . "'";
                    Yii::$app->db->createCommand($sql)->execute();
                }
            }
        }
    }

    // public function actionUpdateshippingfee() {
    //     ini_set('max_execution_time', 3000);
    //     //get data from memberv2
    //     $ch = curl_init();
    //     curl_setopt($ch, CURLOPT_URL, Yii::$app->params['apiGetShippingFeeMonthly']);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //     $data = curl_exec($ch);
    //     curl_close($ch);
    //     $dataArr = json_decode($data, true);
    //     //print_r($dataArr);
    //     if (!empty($dataArr)) {
    //         foreach ($dataArr as $data) {
    //             //update at cms
    //             $sql = "UPDATE orders SET shipping_fee='" . $data['shipping_fee'] . "',shipping_cost='" . $data['shipping_cost'] . "' WHERE invoice_id='" . $data['id'] . "'";
    //             Yii::$app->db->createCommand($sql)->execute();
    //         }
    //     }
    //     exit();
    // }

    public function actionGetlatestorders() {
        ini_set('max_execution_time', 3000);

        //get max id
        $lastID = Orders::find()->max('id');

        //get data from memberv2 using curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, Yii::$app->params['apiGetOrderDetails']);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "id=$lastID");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);
        $dataArr = json_decode($data);
        //print_r($dataArr);
        //insert into table orders
        if (!empty($dataArr)) {
            foreach ($dataArr as $info) {
                $model = new Orders();
                $Product = new Product();

                $model->id = $info->id;
                $model->invoice_id = $info->invoice_id;
                $model->order_date = $info->order_date;
                $model->order_status = $info->order_status;
                $model->marketplace = $info->marketplace;
                $model->courier = $info->courier;
                $model->weight = $info->weight;
                $model->shipping_fee = $info->shipping_fee;
                $model->shipping_cost = $info->shipping_cost;
                $model->tracking_code = $info->tracking_code;
                $model->material = $info->material_id;
                $model->material_fee = $info->material_fee;
                $model->shipping_declare = $info->shipping_declare;
                $model->package_id = $info->package_id;
                $model->quantity = $info->quantity;
                $model->product_id = $info->product_id;
                $model->brand_id = $info->brand_id;
                $model->cat_id = $info->cat_id;
                $model->product_price = $info->product_price;
                $model->selling_price = $info->selling_price;
                $model->selling_currency = $info->selling_currency;
                $model->member_id = $info->member_id;
                $model->member_name = $info->member_name;
                $model->member_username = $info->member_username;
                $model->member_store_id = $info->member_store_id;
                $model->customer_name = $info->customer_name;
                $model->customer_address1 = $info->customer_address1;
                $model->customer_address2 = $info->customer_address2;
                $model->customer_city = $info->customer_city;
                $model->customer_state = $info->customer_state;
                $model->customer_postcode = $info->customer_postcode;
                $model->customer_country_code = $info->customer_country_code;
                $model->customer_country_name = $info->customer_country_name;
                $model->customer_contact = $info->customer_contact;
                $model->customer_email = $info->customer_email;
                $model->member_remark = $info->member_remark;
                $model->declare_value = $info->declare_value;
                $model->order_id = $info->order_id;
                $model->listing_title = $info->listing_title;
                $model->listing_url = $info->listing_url;
                $model->selling_mode = $info->selling_mode;
                
                // $model->owner_type = 'vendor';
                // $model->owner_id = $Product->getvendor($model->product_id);
                
                if ($model->package_id !== '')
                {
                    $PackageProductList = new PackageProductList();
                    $model->owner_type = $PackageProductList->getproductowner($model->package_id,$model->product_id);
                    if ($model->owner_type == 'company')
                        $model->owner_id = $Product->getcompany($model->product_id);
                    else
                        $model->owner_id = $Product->getvendor($model->product_id);
                }
                else
                {
                    $model->owner_type = 'vendor';
                    $model->owner_id = $Product->getvendor($model->product_id);
                }

                if ($model->owner_type == '')
                {
                    $model->owner_type = 'vendor';
                    $model->owner_id = $Product->getvendor($model->product_id);
                }
                
                $model->save(false);
            }
        }

        exit();
    }

    public function actionUpdateownstatus() {
        ini_set('max_execution_time', 30000);
        $model = OrdersOwn::find()->where(['OR', ['=', 'order_status', 'pending'], ['=', 'order_status', 'under process'], ['=', 'order_status', 'on hold']])->groupBy(['invoice_id'])->orderBy(['id' => SORT_DESC])->all();
        if (!empty($model)) {
            foreach ($model as $row) {
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, Yii::$app->params['apiGetOwnOrderStatus']);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, "id=$row->invoice_id");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $data = curl_exec($ch);
                curl_close($ch);
                $dataArr = json_decode($data, true);
                if (!empty($dataArr)) {
                    $sql = "UPDATE orders_own SET 
                    order_status='" . $dataArr['order_status'] . "', 
                    order_date='" . $dataArr['order_date'] . "',
                    shipping_fee='" . $dataArr['shipping_fee'] . "',
                    shipping_cost='" . $dataArr['shipping_cost'] . "',
                    tracking_code='" . $dataArr['tracking_code'] . "',
                    material='" . $dataArr['material_id'] . "',
                    material_fee='" . $dataArr['material_fee'] . "',
                    weight='" . $dataArr['weight'] . "',
                    courier='" . $dataArr['courier'] . "'
                    WHERE invoice_id='" . $row->invoice_id . "'";
                    Yii::$app->db->createCommand($sql)->execute();
                }
            }
        }
    }
    
    // public function actionUpdateownshippingfee() {
    //     ini_set('max_execution_time', 3000);
    //     //get data from memberv2
    //     $ch = curl_init();
    //     curl_setopt($ch, CURLOPT_URL, Yii::$app->params['apiGetOwnShippingFeeMonthly']);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    //     $data = curl_exec($ch);
    //     curl_close($ch);
    //     $dataArr = json_decode($data, true);
    //     if (!empty($dataArr)) {
    //         foreach ($dataArr as $data) {
    //             //update at cms
    //             $sql = "UPDATE orders_own SET shipping_fee='" . $data['shipping_fee'] . "',shipping_cost='" . $data['shipping_cost'] . "' WHERE invoice_id='" . $data['id'] . "'";
    //             Yii::$app->db->createCommand($sql)->execute();
    //         }
    //     }
    //     exit();
    // }
    
    public function actionGetownlatestorders() {
        ini_set('max_execution_time', 3000);

        //get max id
        $lastID = OrdersOwn::find()->max('id');

        //get data from memberv2 using curl
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, Yii::$app->params['apiGetOwnOrderDetails']);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "id=$lastID");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);
        $dataArr = json_decode($data);
        //print_r($dataArr);
        //insert into table orders
        if (!empty($dataArr)) {
            foreach ($dataArr as $info) {
                $model = new OrdersOwn();
                $model->id = $info->id;
                $model->invoice_id = $info->invoice_id;
                $model->order_date = $info->order_date;
                $model->order_status = $info->order_status;
                $model->marketplace = $info->marketplace;
                $model->courier = $info->courier;
                $model->weight = $info->weight;
                $model->shipping_fee = $info->shipping_fee;
                $model->shipping_cost = $info->shipping_cost;
                $model->tracking_code = $info->tracking_code;
                $model->material = $info->material_id;
                $model->material_fee = $info->material_fee;
                $model->product_quantity = $info->product_quantity;
                $model->product_title = $info->product_title;
                $model->product_price = $info->product_price;
                $model->stock_mode = $info->stock_mode;
                $model->member_id = $info->member_id;
                $model->member_name = $info->member_name;
                $model->member_username = $info->member_username;
                $model->customer_name = $info->customer_name;
                $model->customer_address1 = $info->customer_address1;
                $model->customer_address2 = $info->customer_address2;
                $model->customer_city = $info->customer_city;
                $model->customer_state = $info->customer_state;
                $model->customer_postcode = $info->customer_postcode;
                $model->customer_country_code = $info->customer_country_code;
                $model->customer_country_name = $info->customer_country_name;
                $model->customer_contact = $info->customer_contact;
                $model->customer_email = $info->customer_email;
                $model->member_remark = $info->member_remark;
                $model->order_id = $info->order_id;
                $model->merchant_id = $info->merchant_id;
                $model->merchant_name = $info->merchant_name;
                $model->save(false);
            }
        }

        exit();
    }
    
    public function actionUpdatenewshippingfee() {

        date_default_timezone_set("Asia/Kuala_Lumpur");
        ini_set('max_execution_time', 3000);
        $today = date('Y-m-d');
        // $yesterday = date('Y-m-d');
        $yesterday = date('Y-m-d',strtotime("-1 days"));
        //$yesterday = date('Y-m-d');
        // $today = '2021-06-28';
        // $later = '2021-05-06';
        
        
        //get all order in completed
        // $model = Orders::find()->where(['BETWEEN', 'order_date', $today, $later])->andWhere(['=', 'order_status', 'completed'])->andWhere(['=', 'courier', 'Aramex'])->groupBy(['invoice_id'])->all();
        $model = Orders::find()->where(['BETWEEN', 'order_date', $yesterday, $today])->andWhere(['=', 'order_status', 'completed'])->groupBy(['invoice_id'])->all();
        if (!empty($model)) {
            foreach ($model as $row) {
                $base = 0;
                $service = 0;
                $allrate = 0;
                $zone = 0;
                $courierarea = 0;
                $fuelcharge = 0;
                $peak = 0;
                $totalrate = 0;
                $fuelrate = 0;
                $fsc = 0;
                $basefsc =0;
                $servicefsc =0;
                $formula = '';
                $formulavalue = 0;
                $fullformula = '';
                $psc = 0;
                $newshippingfee = 0;
                $newshippingcost = 0;
                $msg = 'initial';

                // GET ORDERS DATA (date,courier,country,weight)
                $invoice_id = $row->invoice_id;
                $order_date = $row->order_date;
                $courier = $row->courier;
                $country = $row->customer_country_code;
                $weight = $row->weight;
                $currentshippingfee = $row->shipping_fee;

                
                // GET SHIPPING RATE
                if ($courier == 'Aramex' || $courier == 'DHL Express' || $courier == 'DHL Global Mail' || $courier == 'Fedex' || $courier == 'UPS')
                {
                    if ($weight <= 20)
                    {
                        if ($weight > 0.54 && $courier == 'DHL Global Mail')
                        {
                            echo "Skip process ".$invoice_id . " (DHL eCommerce > 0.54kg)<br>";
                        }
                        else
                        {
                            $courierarea = Countrycode::find()->andWhere(['=', 'code',$country])->one();

                            if ($country != 'US')
                            {
                                    // GET COURIER ZONE
                                if ($courier == 'Aramex')
                                    $zone = $courierarea->Aramex_zone;
                                else if ($courier == 'DHL Express')
                                    $zone = $courierarea->DHL_zone;
                                else if ($courier == 'DHL Global Mail')
                                    $zone = $courierarea->Global_Mail;
                                else if ($courier == 'Fedex')
                                    $zone = $courierarea->Fedex_zone;
                                else if ($courier == 'UPS')
                                    $zone = $courierarea->UPS_zone;
                                else
                                    $zone = 0;
                                
                                //->orderBy('created_date DESC')->limit(1)
                                //$null = '';
                                $baserate = Shippingrate::find()->andWhere(['courier' => $courier, 'type' => 'Base', 'weight'=> $weight, 'zone' => $zone,'member'=>''])->andWhere(['<=', 'start_date',$order_date])->andWhere(['>=', 'end_date',$order_date])->one();
                                if ($baserate->rate)
                                    $base = $baserate->rate;
                                
                                $servicerate = Shippingrate::find()->andWhere(['courier' => $courier, 'type' => 'Service', 'weight'=> $weight, 'zone' => $zone, 'member'=> ''])->andWhere(['<=', 'start_date',$order_date])->andWhere(['>=', 'end_date',$order_date])->one();
                                if ($servicerate->rate)
                                    $service = $servicerate->rate;
                                
                                $totalrate = $base + $service;
                            }
                            else
                            {
                                if ($courier == 'DHL Global Mail')
                                {
                                    $zone = 10;
                                    
                                    $baserate = Shippingrate::find()->andWhere(['courier' => $courier, 'type' => 'Base', 'weight'=> $weight, 'zone' => $zone])->andWhere(['<=', 'start_date',$order_date])->andWhere(['>=', 'end_date',$order_date])->one();
                                    if ($baserate->rate)
                                        $base = $baserate->rate;
                                    
                                    $servicerate = Shippingrate::find()->andWhere(['courier' => $courier, 'type' => 'Service', 'weight'=> $weight, 'zone' => $zone])->andWhere(['<=', 'start_date',$order_date])->andWhere(['>=', 'end_date',$order_date])->one();
                                    if ($servicerate->rate)
                                        $service = $servicerate->rate;
                                }
                                else
                                {
                                    $zone = 0;
                                    
                                    $baserate = Shippingrate::find()->andWhere(['courier' => 'US', 'type' => 'Base', 'weight'=> $weight, 'zone' => $zone])->andWhere(['<=', 'start_date',$order_date])->andWhere(['>=', 'end_date',$order_date])->one();
                                    if ($baserate->rate)
                                        $base = $baserate->rate;
                                    
                                    $servicerate = Shippingrate::find()->andWhere(['courier' => 'US', 'type' => 'Service', 'weight'=> $weight, 'zone' => $zone])->andWhere(['<=', 'start_date',$order_date])->andWhere(['>=', 'end_date',$order_date])->one();
                                    if ($servicerate->rate)
                                        $service = $servicerate->rate;
                                }
                                
                                $totalrate = $base + $service;
                            }
                            
                            // GET FUEL SURCHARGE
                            $fuelcharge = Courierfsc::find()->andWhere(['<=', 'start_date',$order_date])->andWhere(['>=', 'end_date',$order_date])->andWhere(['=', 'courier', $courier])->one();
                            $fuelrate = $fuelcharge->fuelcharge_rate;
                            $fsc = $fuelrate * $totalrate;
                            $totalwithfsc = $totalrate + $fsc;
                            $basefsc = $base + ($fuelrate * $base);
                            $servicefsc = $service + ($fuelrate * $service);
                            
                            // GET PEAK SURCHARGE
                            $peak = Courierpsc::find()->andWhere(['<=', 'start_date',$order_date])->andWhere(['>=', 'end_date',$order_date])->andWhere(['=', 'courier', $courier])->andWhere(['LIKE', 'country_code', $country])->one();
                            $formula = $peak->formula;
                            $formulavalue = $peak->value;
                            
                            if ($courier == 'UPS' || $courier == 'Fedex')
                            {
                                if ($weight < 1)
                                    $new_weight = 1;
                                else
                                    $new_weight = $weight;
                            }
                            else
                                $new_weight = $weight;
                            
                            $formula2 = str_replace("value", $formulavalue, $formula);
                            $formula3 = str_replace("weight", $new_weight, $formula2);
                            $formula4 = str_replace("fsc", $fuelrate, $formula3);
                            $fullformula = str_replace("total", $totalwithfsc, $formula4);
                            $psc = eval('return '.$fullformula.';');
                            
                            if ($courier == 'DHL Express' && $new_weight < 2.5 && $country == 'US')
                            {
                                $psc = 0;
                            }
                            
                            // CALCULATE TOTAL COST
                            $total_cost = $basefsc + $psc;
                            $newshippingcost = round($total_cost * 2, 1) / 2;
                            
                            // CALCULATE TOTAL SHIPPING
                            $totalshippingfee = $totalwithfsc + $psc;
                            $newshippingfee = round($totalshippingfee * 2, 1) / 2;
                            
                            // UPDATE NEW SHIPPING FEES & ALL SURCHARGES
                            $sql = "UPDATE orders SET 
                            shipping_base='" . $base . "',
                            shipping_services ='" . $service . "',
                            shipping_total ='" . $totalrate . "', 
                            shipping_fuelsurcharge ='" . $fsc . "',
                            shipping_basefsc = '" . $basefsc . "',
                            shipping_servicefsc = '" . $servicefsc . "',
                            shipping_peaksurcharge ='" . $psc . "',
                            shipping_finalfees ='" . $newshippingfee . "',
                            shipping_fee = '" . $newshippingfee . "',
                            shipping_cost = '" . $newshippingcost . "'
                            WHERE invoice_id ='" . $invoice_id . "'";
                            Yii::$app->db->createCommand($sql)->execute();
                            
                            // UPDATE MEMBERV2 NEW FIELDS 
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, Yii::$app->params['apiUpdateNewShippingValues']);
                            curl_setopt($ch, CURLOPT_POST, 1);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, "id=" . $invoice_id . "&newfee=" . $newshippingfee. "&newcost=" . $newshippingcost);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                            $data = curl_exec($ch);
                            curl_close($ch);
                            
                            $dataArr = json_decode($data);
                            if (!empty($dataArr)) {
                                if ($dataArr->status == 'success')
                                    $msg = 'success';
                                else
                                    $msg = 'failed';
                            }
                            echo $invoice_id." - ".$msg."<br>";
                        }
                    }
                }
            }
        }
        exit();
    }
    
    public function actionUpdatenewshippingfeeown() {

        date_default_timezone_set("Asia/Kuala_Lumpur");
        ini_set('max_execution_time', 3000);
        $today = date('Y-m-d');
        $yesterday = date('Y-m-d',strtotime("-1 days"));
        // $yesterday = date('Y-m-d');
        // $yesterday = '2021-06-18';
        // $today = '2021-06-18';
        // $later = '2021-05-06';
        
        //get all order in completed
        // $model = OrdersOwn::find()->where(['BETWEEN', 'order_date', $today, $later])->andWhere(['=', 'order_status', 'completed'])->andWhere(['=', 'courier', 'Aramex'])->groupBy(['invoice_id'])->all();
        $model = OrdersOwn::find()->where(['BETWEEN', 'order_date', $yesterday, $today])->andWhere(['=', 'order_status', 'completed'])->groupBy(['invoice_id'])->all();
        // $model = OrdersOwn::find()->where(['=', 'courier', 'Fedex'])->andWhere(['=', 'order_status', 'completed'])->andWhere(['=', 'member_id', '765'])->groupBy(['invoice_id'])->all();
        if (!empty($model)) {
            foreach ($model as $row) {
                $base = 0;
                $service = 0;
                $allrate = 0;
                $zone = 0;
                $courierarea = 0;
                $fuelcharge = 0;
                $peak = 0;
                $totalrate = 0;
                $fuelrate = 0;
                $fsc = 0;
                $basefsc =0;
                $servicefsc =0;
                $formula = '';
                $formulavalue = 0;
                $fullformula = '';
                $psc = 0;
                $newshippingfee = 0;
                $newshippingcost = 0;
                $msg = 'initial';

                // GET ORDERS DATA (date,courier,country,weight)
                $invoice_id = $row->invoice_id;
                $order_date = $row->order_date;
                $courier = $row->courier;
                $country = $row->customer_country_code;
                $weight = $row->weight;
                $currentshippingfee = $row->shipping_fee;
                $uid = $row->member_id;
                // echo $uid."<br>";

                // GET SHIPPING RATE
                if ($courier == 'Aramex' || $courier == 'DHL Express' || $courier == 'DHL Global Mail' || $courier == 'Fedex' || $courier == 'UPS')
                {
                    if ($weight <= 20)
                    {
                        if ($weight > 0.54 && $courier == 'DHL Global Mail')
                        {
                            echo "Skip process ".$invoice_id . " (DHL eCommerce > 0.54kg)<br>";
                        }
                        else
                        {
                            $courierarea = Countrycode::find()->andWhere(['=', 'code',$country])->one();

                            // NON-US SHIPMENTS
                            if ($country != 'US')
                            {
                                    // GET COURIER ZONE
                                if ($courier == 'Aramex')
                                    $zone = $courierarea->Aramex_zone;
                                else if ($courier == 'DHL Express')
                                    $zone = $courierarea->DHL_zone;
                                else if ($courier == 'DHL Global Mail')
                                    $zone = $courierarea->Global_Mail;
                                else if ($courier == 'Fedex')
                                    $zone = $courierarea->Fedex_zone;
                                else if ($courier == 'UPS')
                                    $zone = $courierarea->UPS_zone;
                                else
                                    $zone = 0;
                                
                                if ($uid == '368')
                                {
                                    // ONECARE & OTHERS SPECIAL SHIPMENTS
                                    $baserate = Shippingrate::find()->andWhere(['courier' => $courier, 'type' => 'Base', 'weight'=> $weight, 'zone' => $zone, 'member' => $uid])->andWhere(['<=', 'start_date',$order_date])->andWhere(['>=', 'end_date',$order_date])->one();
                                    if ($baserate->rate)
                                        $base = $baserate->rate;
                                    
                                    $servicerate = Shippingrate::find()->andWhere(['courier' => $courier, 'type' => 'Service', 'weight'=> $weight, 'zone' => $zone, 'member' => $uid])->andWhere(['<=', 'start_date',$order_date])->andWhere(['>=', 'end_date',$order_date])->one();
                                    if ($servicerate->rate)
                                        $service = $servicerate->rate;
                                }
                                else
                                {
                                    $baserate = Shippingrate::find()->andWhere(['courier' => $courier, 'type' => 'Base', 'weight'=> $weight, 'zone' => $zone, 'member'=> ''])->andWhere(['<=', 'start_date',$order_date])->andWhere(['>=', 'end_date',$order_date])->one();
                                    if (gettype($baserate)=='object')
                                        $base = $baserate->rate;
                                    
                                    $servicerate = Shippingrate::find()->andWhere(['courier' => $courier, 'type' => 'Service', 'weight'=> $weight, 'zone' => $zone,'member'=> ''])->andWhere(['<=', 'start_date',$order_date])->andWhere(['>=', 'end_date',$order_date])->one();
                                    if (gettype($servicerate)=='object')
                                        $service = $servicerate->rate;
                                }
                                
                                $totalrate = $base + $service;
                            }
                            // US SHIPMENTS
                            else
                            {
                                if ($courier == 'DHL Global Mail')
                                {
                                    $zone = 10;
                                    
                                    $baserate = Shippingrate::find()->andWhere(['courier' => $courier, 'type' => 'Base', 'weight'=> $weight, 'zone' => $zone,'member'=>''])->andWhere(['<=', 'start_date',$order_date])->andWhere(['>=', 'end_date',$order_date])->one();
                                    if (gettype($baserate)=='object')
                                        $base = $baserate->rate;
                                    
                                    $servicerate = Shippingrate::find()->andWhere(['courier' => $courier, 'type' => 'Service', 'weight'=> $weight, 'zone' => $zone,'member'=>''])->andWhere(['<=', 'start_date',$order_date])->andWhere(['>=', 'end_date',$order_date])->one();
                                    if (gettype($servicerate)=='object')
                                        $service = $servicerate->rate;
                                }
                                else if ($courier == 'DHL Express')
                                {
                                    if ($uid == '368')
                                    {
                                        $zone = 5;
                                        
                                        // ONECARE & OTHERS SPECIAL SHIPMENTS
                                        $baserate = Shippingrate::find()->andWhere(['courier' => $courier, 'type' => 'Base', 'weight'=> $weight, 'zone' => $zone, 'member' => $uid])->andWhere(['<=', 'start_date',$order_date])->andWhere(['>=', 'end_date',$order_date])->one();
                                        if ($baserate->rate)
                                            $base = $baserate->rate;
                                        
                                        $servicerate = Shippingrate::find()->andWhere(['courier' => $courier, 'type' => 'Service', 'weight'=> $weight, 'zone' => $zone,'member' => $uid])->andWhere(['<=', 'start_date',$order_date])->andWhere(['>=', 'end_date',$order_date])->one();
                                        if ($servicerate->rate)
                                            $service = $servicerate->rate;
                                    }
                                    else
                                    {
                                        $zone = 0;
                                        
                                        $baserate = Shippingrate::find()->andWhere(['courier' => 'US', 'type' => 'Base', 'weight'=> $weight, 'zone' => $zone,'member'=>''])->andWhere(['<=', 'start_date',$order_date])->andWhere(['>=', 'end_date',$order_date])->one();
                                        if (gettype($baserate)=='object')
                                            $base = $baserate->rate;
                                        
                                        $servicerate = Shippingrate::find()->andWhere(['courier' => 'US', 'type' => 'Service', 'weight'=> $weight, 'zone' => $zone,'member'=>''])->andWhere(['<=', 'start_date',$order_date])->andWhere(['>=', 'end_date',$order_date])->one();
                                        if (gettype($servicerate)=='object')
                                            $service = $servicerate->rate;   
                                    }
                                }
                                else if ($courier == 'Fedex')
                                {
                                    $zone = 4;
                                    
                                    $baserate = Shippingrate::find()->andWhere(['courier' => $courier, 'type' => 'Base', 'weight'=> $weight, 'zone' => $zone,'member'=>''])->andWhere(['<=', 'start_date',$order_date])->andWhere(['>=', 'end_date',$order_date])->one();
                                    if (gettype($baserate)=='object')
                                        $base = $baserate->rate;
                                    
                                    $servicerate = Shippingrate::find()->andWhere(['courier' => $courier, 'type' => 'Service', 'weight'=> $weight, 'zone' => $zone,'member'=>''])->andWhere(['<=', 'start_date',$order_date])->andWhere(['>=', 'end_date',$order_date])->one();
                                    if (gettype($servicerate)=='object')
                                        $service = $servicerate->rate;
                                }
                                else
                                {
                                    $zone = 0;
                                    
                                    $baserate = Shippingrate::find()->andWhere(['courier' => 'US', 'type' => 'Base', 'weight'=> $weight, 'zone' => $zone,'member'=>''])->andWhere(['<=', 'start_date',$order_date])->andWhere(['>=', 'end_date',$order_date])->one();
                                    if (gettype($baserate)=='object')
                                        $base = $baserate->rate;
                                    
                                    $servicerate = Shippingrate::find()->andWhere(['courier' => 'US', 'type' => 'Service', 'weight'=> $weight, 'zone' => $zone,'member'=>''])->andWhere(['<=', 'start_date',$order_date])->andWhere(['>=', 'end_date',$order_date])->one();
                                    if (gettype($servicerate)=='object')
                                        $service = $servicerate->rate;   
                                }
                                
                                $totalrate = $base + $service;
                            }
                            
                            // GET FUEL SURCHARGE
                            $fuelcharge = Courierfsc::find()->andWhere(['<=', 'start_date',$order_date])->andWhere(['>=', 'end_date',$order_date])->andWhere(['=', 'courier', $courier])->one();
                            if (gettype($fuelcharge)=='object')
                                $fuelrate = $fuelcharge->fuelcharge_rate;
                            $fsc = $fuelrate * $totalrate;
                            $totalwithfsc = $totalrate + $fsc;
                            $basefsc = $base + ($fuelrate * $base);
                            $servicefsc = $service + ($fuelrate * $service);
                            
                            // GET PEAK SURCHARGE
                            $peak = Courierpsc::find()->andWhere(['<=', 'start_date',$order_date])->andWhere(['>=', 'end_date',$order_date])->andWhere(['=', 'courier', $courier])->andWhere(['LIKE', 'country_code', $country])->one();
                            // var_dump(gettype($peak));
                            if (gettype($peak)=='object')
                            {
                                $formula = $peak->formula;
                                $formulavalue = $peak->value;
                            }
                            
                            
                            if ($courier == 'UPS' || $courier == 'Fedex')
                            {
                                if ($weight < 1)
                                    $new_weight = 1;
                                else
                                    $new_weight = $weight;
                            }
                            else
                                $new_weight = $weight;
                            
                            $formula2 = str_replace("value", $formulavalue, $formula);
                            $formula3 = str_replace("weight", $new_weight, $formula2);
                            $formula4 = str_replace("fsc", $fuelrate, $formula3);
                            $fullformula = str_replace("total", $totalwithfsc, $formula4);
                            $psc = eval('return '.$fullformula.';');
                            
                            if ($courier == 'DHL Express' && $country == 'US')
                            {
                                if ($uid == '368')
                                {
                                    $psc = (1 * $new_weight) * (1 + $fuelrate);
                                }
                                else
                                {
                                    if ($new_weight < 2.5)
                                        $psc = 0;
                                    else
                                        $psc = eval('return '.$fullformula.';');
                                }
                            }
                            
                            if ($courier == 'Fedex')
                            {
                                if ($country == 'AU' || $country == 'NZ')
                                {
                                    if ($weight < 1)
                                        $psc = 5.35;
                                }
                                else
                                {
                                    if ($psc < 4.30)
                                    {
                                        if($weight < 4.5)
                                        {
                                            $psc = (4.3) * (1 + $fuelrate);
                                        }
                                    }
                                }
                            }
                            
                            // CALCULATE TOTAL COST
                            $total_cost = $basefsc + $psc;
                            $newshippingcost = round($total_cost * 2, 1) / 2;
                            
                            // CALCULATE TOTAL SHIPPING
                            $totalshippingfee = $totalwithfsc + $psc;
                            $newshippingfee = round($totalshippingfee * 2, 1) / 2;
                            
                            
                            // UPDATE NEW SHIPPING FEES & ALL SURCHARGES
                            $sql = "UPDATE orders_own SET 
                            shipping_base='" . $base . "',
                            shipping_services ='" . $service . "',
                            shipping_total ='" . $totalrate . "', 
                            shipping_fuelsurcharge ='" . $fsc . "',
                            shipping_basefsc = '" . $basefsc . "',
                            shipping_servicefsc = '" . $servicefsc . "',
                            shipping_peaksurcharge ='" . $psc . "',
                            shipping_finalfees ='" . $newshippingfee . "',
                            shipping_fee ='" . $newshippingfee . "',
                            shipping_cost = '" . $newshippingcost . "'
                            WHERE invoice_id ='" . $invoice_id . "'";
                            Yii::$app->db->createCommand($sql)->execute();
                            
                            // UPDATE TO MEMBERV2 NEW FIELDS
                            $ch = curl_init();
                            curl_setopt($ch, CURLOPT_URL, Yii::$app->params['apiUpdateNewShippingValuesOwn']);
                            curl_setopt($ch, CURLOPT_POST, 1);
                            curl_setopt($ch, CURLOPT_POSTFIELDS, "id=" . $invoice_id . "&newfee=" . $newshippingfee. "&newcost=" . $newshippingcost);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                            $data = curl_exec($ch);
                            curl_close($ch);
                            
                            $dataArr = json_decode($data);
                            if (!empty($dataArr)) {
                                if ($dataArr->status == 'success')
                                    $msg = 'success';
                                else
                                    $msg = 'failed';
                            }
                            echo $invoice_id." - ".$msg."<br>";
                        }
                    }
                }
            }
        }
        exit();
    }
    
    public function actionUpdateshippingfeebernard() {
        date_default_timezone_set("Asia/Kuala_Lumpur");
        ini_set('max_execution_time', 3000);
        $today = date('Y-m-d');
        $yesterday = date('Y-m-d',strtotime("-1 days"));
        // $yesterday = date('Y-m-d');
        // $yesterday = '2021-05-03';
        // $today = '2021-01-12';
        
        //get all OWN order in completed
        echo "CY Global OWN INVOICE<br>";
        $model = OrdersOwn::find()->where(['BETWEEN', 'order_date', $yesterday, $today])->andWhere(['=', 'order_status', 'completed'])->andWhere(['=', 'courier', 'DHL Express'])->andWhere(['=', 'member_id', 190])->andWhere(['=', 'customer_country_code', 'US'])->andWhere(['>=', 'weight', 15])->groupBy(['invoice_id'])->all();
        if (!empty($model)) {
            foreach ($model as $row) {
                $base = 0;
                $service = 0;
                $allrate = 0;
                $zone = 0;
                $courierarea = 0;
                $fuelcharge = 0;
                $peak = 0;
                $totalrate = 0;
                $fuelrate = 0;
                $fsc = 0;
                $basefsc =0;
                $servicefsc =0;
                $formula = '';
                $formulavalue = 0;
                $fullformula = '';
                $psc = 0;
                $newshippingfee = 0;
                $newshippingcost = 0;
                $msg = 'initial';

                // GET ORDERS DATA (date,courier,country,weight)
                $invoice_id = $row->invoice_id;
                $order_date = $row->order_date;
                $courier = $row->courier;
                $country = $row->customer_country_code;
                $weight = $row->weight;
                $currentshippingfee = $row->shipping_fee;
                $uid = $row->member_id;
                // echo $uid."<br>";
                
                $zone = 5;
                // SPECIAL SHIPMENTS BASE RATE (bernard 190)
                // $baserate = Shippingrate::find()->andWhere(['courier' => $courier, 'type' => 'Base', 'weight'=> $weight, 'zone' => $zone])->andWhere(['<=', 'start_date',$order_date])->andWhere(['>=', 'end_date',$order_date])->one();
                $baserate = Shippingrate::find()->andWhere(['courier' => $courier, 'type' => 'Base', 'weight'=> $weight, 'zone' => $zone, 'member'=> ''])->andWhere(['<=', 'start_date',$order_date])->andWhere(['>=', 'end_date',$order_date])->one();
                if ($baserate->rate)
                    $base = $baserate->rate;
                                        
                $zoneS = 0;
                // SPECIAL SHIPMENTS SERVICE RATE (bernard 190)   
                $servicerate = Shippingrate::find()->andWhere(['courier' => 'US', 'type' => 'Service', 'weight'=> $weight, 'zone' => $zoneS])->andWhere(['<=', 'start_date',$order_date])->andWhere(['>=', 'end_date',$order_date])->one();
                //  $servicerate = Shippingrate::find()->andWhere(['courier' => $courier, 'type' => 'Service', 'weight'=> $weight, 'zone' => $zoneS,'member'=> ''])->andWhere(['<=', 'start_date',$order_date])->andWhere(['>=', 'end_date',$order_date])->one();
                //  echo "<pre>";
                // var_dump($servicerate);
                // exit;
                if (gettype($servicerate)=='object')
                    $service = $servicerate->rate;   
                
                $totalrate = $base + $service;
                
                // FUEL SURCHARGE
                $fuelcharge = Courierfsc::find()->andWhere(['<=', 'start_date',$order_date])->andWhere(['>=', 'end_date',$order_date])->andWhere(['=', 'courier', $courier])->one();
                if (gettype($fuelcharge)=='object')
                    $fuelrate = $fuelcharge->fuelcharge_rate;
                $fsc = $fuelrate * $totalrate;
                $totalwithfsc = $totalrate + $fsc;
                $basefsc = $base + ($fuelrate * $base);
                $servicefsc = $service + ($fuelrate * $service);
                
                // PEAK SURCHARGE
                $psc = (1 * $weight) * (1 + $fuelrate);
                
                // CALCULATE TOTAL COST
                $total_cost = $basefsc + $psc;
                $newshippingcost = round($total_cost * 2, 1) / 2;
                            
                // CALCULATE TOTAL SHIPPING
                $totalshippingfee = $totalwithfsc + $psc;
                $newshippingfee = round($totalshippingfee * 2, 1) / 2;
                
                // UPDATE NEW SHIPPING FEES & ALL SURCHARGES
                $sql = "UPDATE orders_own SET 
                shipping_base='" . $base . "',
                shipping_services ='" . $service . "',
                shipping_total ='" . $totalrate . "', 
                shipping_fuelsurcharge ='" . $fsc . "',
                shipping_basefsc = '" . $basefsc . "',
                shipping_servicefsc = '" . $servicefsc . "',
                shipping_peaksurcharge ='" . $psc . "',
                shipping_finalfees ='" . $newshippingfee . "',
                shipping_fee ='" . $newshippingfee . "',
                shipping_cost = '" . $newshippingcost . "'
                WHERE invoice_id ='" . $invoice_id . "'";
                Yii::$app->db->createCommand($sql)->execute();
                            
                // UPDATE TO MEMBERV2 NEW FIELDS
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, Yii::$app->params['apiUpdateNewShippingValuesOwn']);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, "id=" . $invoice_id . "&newfee=" . $newshippingfee. "&newcost=" . $newshippingcost);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                $data = curl_exec($ch);
                curl_close($ch);
                            
                $dataArr = json_decode($data);
                if (!empty($dataArr)) {
                    if ($dataArr->status == 'success')
                        $msg = 'success';
                    else
                        $msg = 'failed';
                }
                echo "S".$invoice_id." - ".$msg."<br>";
            }
        }
        
        // //get all AXIS order in completed
        // echo "<br><br>CY Global COMPANY INVOICE<br>";
        // $modelTR= Orders::find()->where(['BETWEEN', 'order_date', $yesterday, $today])->andWhere(['=', 'order_status', 'completed'])->andWhere(['=', 'courier', 'DHL Express'])->andWhere(['=', 'member_id', 190])->andWhere(['=', 'customer_country_code', 'US'])->andWhere(['>=', 'weight', 15])->groupBy(['invoice_id'])->all();
        // if (!empty($modelTR)) {
        //     foreach ($modelTR as $row) {
        //         $base = 0;
        //         $service = 0;
        //         $allrate = 0;
        //         $zone = 0;
        //         $courierarea = 0;
        //         $fuelcharge = 0;
        //         $peak = 0;
        //         $totalrate = 0;
        //         $fuelrate = 0;
        //         $fsc = 0;
        //         $basefsc =0;
        //         $servicefsc =0;
        //         $formula = '';
        //         $formulavalue = 0;
        //         $fullformula = '';
        //         $psc = 0;
        //         $newshippingfee = 0;
        //         $newshippingcost = 0;
        //         $msg = 'initial';

        //         // GET ORDERS DATA (date,courier,country,weight)
        //         $invoice_id = $row->invoice_id;
        //         $order_date = $row->order_date;
        //         $courier = $row->courier;
        //         $country = $row->customer_country_code;
        //         $weight = $row->weight;
        //         $currentshippingfee = $row->shipping_fee;
        //         $uid = $row->member_id;
        //         // echo $uid."<br>";
                
        //         $zone = 5;
        //         // SPECIAL SHIPMENTS BASE RATE (bernard 190)
        //         $baserate = Shippingrate::find()->andWhere(['courier' => $courier, 'type' => 'Base', 'weight'=> $weight, 'zone' => $zone])->andWhere(['<=', 'start_date',$order_date])->andWhere(['>=', 'end_date',$order_date])->one();
        //         if ($baserate->rate)
        //             $base = $baserate->rate;
                                        
        //         $zoneS = 0;
        //         // SPECIAL SHIPMENTS SERVICE RATE (bernard 190)   
        //         $servicerate = Shippingrate::find()->andWhere(['courier' => 'US', 'type' => 'Service', 'weight'=> $weight, 'zone' => $zoneS])->andWhere(['<=', 'start_date',$order_date])->andWhere(['>=', 'end_date',$order_date])->one();
        //         if (gettype($servicerate)=='object')
        //             $service = $servicerate->rate;   
        
        //          $totalrate = $base + $service;
                
        //         // FUEL SURCHARGE
        //         $fuelcharge = Courierfsc::find()->andWhere(['<=', 'start_date',$order_date])->andWhere(['>=', 'end_date',$order_date])->andWhere(['=', 'courier', $courier])->one();
        //         if (gettype($fuelcharge)=='object')
        //             $fuelrate = $fuelcharge->fuelcharge_rate;
        //         $fsc = $fuelrate * $totalrate;
        //         $totalwithfsc = $totalrate + $fsc;
        //         $basefsc = $base + ($fuelrate * $base);
        //         $servicefsc = $service + ($fuelrate * $service);
                    
                
        //         // PEAK SURCHARGE
        //         $psc = (1 * $weight) * (1 + $fuelrate);
                
        //         // CALCULATE TOTAL COST
        //         $total_cost = $basefsc + $psc;
        //         $newshippingcost = round($total_cost * 2, 1) / 2;
                            
        //         // CALCULATE TOTAL SHIPPING
        //         $totalshippingfee = $totalwithfsc + $psc;
        //         $newshippingfee = round($totalshippingfee * 2, 1) / 2;
                
        //         // UPDATE NEW SHIPPING FEES & ALL SURCHARGES
        //         $sql = "UPDATE orders SET 
        //         shipping_base='" . $base . "',
        //         shipping_services ='" . $service . "',
        //         shipping_total ='" . $totalrate . "', 
        //         shipping_fuelsurcharge ='" . $fsc . "',
        //         shipping_basefsc = '" . $basefsc . "',
        //         shipping_servicefsc = '" . $servicefsc . "',
        //         shipping_peaksurcharge ='" . $psc . "',
        //         shipping_finalfees ='" . $newshippingfee . "',
        //         shipping_fee ='" . $newshippingfee . "',
        //         shipping_cost = '" . $newshippingcost . "'
        //         WHERE invoice_id ='" . $invoice_id . "'";
        //         Yii::$app->db->createCommand($sql)->execute();
                            
        //         // UPDATE TO MEMBERV2 NEW FIELDS
        //         $ch = curl_init();
        //         curl_setopt($ch, CURLOPT_URL, Yii::$app->params['apiUpdateNewShippingValues']);
        //         curl_setopt($ch, CURLOPT_POST, 1);
        //         curl_setopt($ch, CURLOPT_POSTFIELDS, "id=" . $invoice_id . "&newfee=" . $newshippingfee. "&newcost=" . $newshippingcost);
        //         curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        //         $data = curl_exec($ch);
        //         curl_close($ch);
                            
        //         $dataArr = json_decode($data);
        //         if (!empty($dataArr)) {
        //             if ($dataArr->status == 'success')
        //                 $msg = 'success';
        //             else
        //                 $msg = 'failed';
        //         }
        //         echo "TR".$invoice_id." - ".$msg."<br>";
        //     }
        // }
        
        exit();
    }
}
