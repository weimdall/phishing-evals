<?php

namespace app\controllers;

use Yii;
use yii\helpers\Url;
use app\models\Orders;
use app\models\OrdersSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii2tech\spreadsheet\Spreadsheet;
use yii\data\ArrayDataProvider;
use app\models\ParamList;
use app\models\Product;
use app\models\ProductCategory;
use app\models\Brand;
use app\models\Company;
use app\models\Vendor;
use app\models\Package;
use app\models\PackageGallery;
use app\models\PackageProductList;
use app\models\EasProductVariant;
use app\models\ProductPricing;
use app\models\User;
use app\models\Member;
use app\models\MemberStore;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\mpdf\Pdf;
use app\models\Notes;
use yii\web\UploadedFile;
use app\models\Countrycode;
use app\models\Shippingrate;
use app\models\Courierfsc;
use app\models\Courierpsc;
use app\models\OrdersOwn;

/**
 * OrdersController implements the CRUD actions for Orders model.
 */
class OrdersController extends Controller {

    public $menu_id = 77;
    public $menu_id2 = 79;

    /**
     * {@inheritdoc}
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index', 'create', 'update', 'view'],
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                // everything else is denied
                ],
            ],
        ];
    }

    /**
     * Lists all Orders models.
     * @return mixed
     */
    public function actionStart() {
        $model = new OrdersSearch();
        $model->search(Yii::$app->request->queryParams, '');
        if (empty(Yii::$app->request->queryParams['OrdersSearch']['data_list'])) {
            $model->data_list = ['order_date', 'invoice_id', 'product_id', 'quantity', 'product_price', 'owner_type'];
        }
        return $this->render('start', ['model' => $model, 'menu_id' => $this->menu_id]);
    }

    public function actionStartsme() {
        $model = new OrdersSearch();
        $model->search(Yii::$app->request->queryParams, '');
        if (empty(Yii::$app->request->queryParams['OrdersSearch']['data_list'])) {
            $model->data_list = ['order_date', 'invoice_id', 'product_id', 'quantity', 'product_price', 'owner_type'];
        }
        return $this->render('startsme', ['model' => $model, 'menu_id' => $this->menu_id]);
    }

    public function actionStart2() {
        $model = new OrdersSearch();
        $model->search2(Yii::$app->request->queryParams, '');
        if (empty(Yii::$app->request->queryParams['OrdersSearch']['data_list'])) {
            $model->data_list = ['order_date', 'invoice_id', 'marketplace', 'courier', 'product_id', 'product_price', 'selling_currency', 'selling_price', 'shipping_fee'];
        }
        return $this->render('start2', ['model' => $model, 'menu_id' => $this->menu_id2]);
    }

    public function actionIndex() {
        $searchModel = new OrdersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, 'index');
        
        $paramGet = Yii::$app->request->queryParams;
        unset($paramGet['r']);
        $urlExt = http_build_query($paramGet);

        return $this->render('index', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'menu_id' => $this->menu_id, 'urlExt' => $urlExt]);
    }

    public function actionIndexsme() {
        $searchModel = new OrdersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams, 'indexsme');

        $paramGet = Yii::$app->request->queryParams;
        unset($paramGet['r']);
        $urlExt = http_build_query($paramGet);

        return $this->render('indexsme', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'menu_id' => $this->menu_id, 'urlExt' => $urlExt]);
    }

    public function actionIndex2() {
        $searchModel = new OrdersSearch();
        $dataProvider = $searchModel->search2(Yii::$app->request->queryParams, 'index2');

        $paramGet = Yii::$app->request->queryParams;
        unset($paramGet['r']);
        $urlExt = http_build_query($paramGet);

        return $this->render('index2', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'menu_id' => $this->menu_id2, 'urlExt' => $urlExt]);
    }

    public function actionDownload() {
        $searchModel = new OrdersSearch();
        $model = $searchModel->search(Yii::$app->request->queryParams, 'download');
        $arr = [];
        $Brand = new Brand();
        $ProductCategory = new ProductCategory();
        $Product = new Product();
        $Vendor = new Vendor();
        $Company = new Company();

        if (!empty($model)) {
            foreach ($model as $row) {
                $arr2 = [];
                if (in_array("invoice_id", $searchModel->data_list))
                    $arr2['Invoice ID'] = $row->invoice_id;
                if (in_array("order_date", $searchModel->data_list))
                    $arr2['Sales Date'] = $row->order_date;
                if (in_array("brand_id", $searchModel->data_list))
                    $arr2['Brand'] = $Brand->getname($row->brand_id);
                if (in_array("cat_id", $searchModel->data_list))
                    $arr2['Product Category'] = $ProductCategory->getname($row->cat_id);
                if (in_array("product_id", $searchModel->data_list))
                    $arr2['Product Name'] = $Product->getname($row->product_id);
                if (in_array("quantity", $searchModel->data_list))
                    $arr2['Quantity'] = $row->quantity;
                if (in_array("product_price", $searchModel->data_list))
                    $arr2['Product Price (MYR)'] = number_format((float) $row->product_price, 2, '.', '');
                if (in_array("selling_currency", $searchModel->data_list))
                    $arr2['Selling Currency'] = $row->selling_currency;
                if (in_array("selling_price", $searchModel->data_list))
                    $arr2['Selling Price'] = number_format((float) $row->selling_price, 2, '.', '');
                if (in_array("marketplace", $searchModel->data_list))
                    $arr2['Marketplace'] = $row->marketplace;
                if (in_array("courier", $searchModel->data_list))
                    $arr2['Courier'] = $row->courier;
                if (in_array("shipping_fee", $searchModel->data_list))
                    $arr2['Shipping Fee (MYR)'] = number_format((float) $row->shipping_fee, 2, '.', '');
                if (in_array("weight", $searchModel->data_list))
                    $arr2['Weight'] = $row->weight;
                if (in_array("member_name", $searchModel->data_list))
                    $arr2['Member Name'] = $row->member_name;
                if (in_array("member_username", $searchModel->data_list))
                    $arr2['Member Username'] = $row->member_username;
                if (in_array("customer_name", $searchModel->data_list))
                    $arr2['Customer Name'] = $row->customer_name;
                if (in_array("customer_address1", $searchModel->data_list))
                    $arr2['Customer Address'] = $row->customer_address1 . ' ' . $row->customer_address2;
                if (in_array("customer_city", $searchModel->data_list))
                    $arr2['City'] = $row->customer_city;
                if (in_array("customer_state", $searchModel->data_list))
                    $arr2['State'] = $row->customer_state;
                if (in_array("customer_postcode", $searchModel->data_list))
                    $arr2['Postcode'] = $row->customer_postcode;
                if (in_array("customer_country_name", $searchModel->data_list))
                    $arr2['Country'] = $row->customer_country_name;
                if (in_array("customer_contact", $searchModel->data_list))
                    $arr2['Customer Contact No.'] = $row->customer_contact;
                if (in_array("customer_email", $searchModel->data_list))
                    $arr2['Customer Email'] = $row->customer_email;
                if (in_array("listing_title", $searchModel->data_list))
                    $arr2['Listing Title'] = $row->listing_title;
                if (in_array("listing_url", $searchModel->data_list))
                    $arr2['Listing URL'] = $row->listing_url;
                if (in_array("owner_type", $searchModel->data_list)) {
                    $arr2['Owner Type'] = ucwords($row->owner_type);
                    if ($row->owner_type == 'vendor')
                        $arr2['Stock Owner'] = $Vendor->getname($row->owner_id);
                    if ($row->owner_type == 'company')
                        $arr2['Stock Owner'] = $Company->getname($row->owner_id);
                }
                $arr[] = $arr2;
            }
        }

        $spreadsheet = new Spreadsheet(['dataProvider' => new ArrayDataProvider(['allModels' => $arr])]);
        $spreadsheet->renderCell('A1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('B1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('C1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('D1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('E1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('F1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('G1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('H1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('I1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('J1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('K1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('L1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('M1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('N1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('O1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('P1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('Q1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('R1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('S1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('T1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('U1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('V1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('W1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('X1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('Y1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('Z1', '', ['font' => ['bold' => true]]);

        return $spreadsheet->send('Online Sales - AXIS & SME Product.xls');
        die();
    }

    public function actionDownload2() {
        $searchModel = new OrdersSearch();
        $model = $searchModel->search2(Yii::$app->request->queryParams, 'download2');
        $arr = [];
        $Product = new Product();
        $Orders = new Orders();
        $MemberStore = new MemberStore();

        if (!empty($model)) {
            foreach ($model as $row) {
                $arr2 = [];
                if (in_array("invoice_id", $searchModel->data_list))
                    $arr2['Invoice ID'] = $row->invoice_id;
                if (in_array("order_date", $searchModel->data_list))
                    $arr2['Sales Date'] = $row->order_date;
                if (in_array("product_id", $searchModel->data_list))
                    $arr2['Item'] = $Orders->getproductlist($row->invoice_id);
                if (in_array("product_price", $searchModel->data_list))
                    $arr2['Product Price (MYR)'] = $Orders->getallproductprice($row->invoice_id);
                if (in_array("selling_currency", $searchModel->data_list))
                    $arr2['Selling Currency'] = $row->selling_currency;
                if (in_array("selling_price", $searchModel->data_list))
                    $arr2['Selling Price'] = number_format((float) $row->selling_price, 2, '.', '');
                if (in_array("marketplace", $searchModel->data_list))
                    $arr2['Marketplace'] = $row->marketplace;
                if (in_array("courier", $searchModel->data_list))
                    $arr2['Courier'] = $row->courier;
                if (in_array("shipping_fee", $searchModel->data_list))
                    $arr2['Shipping Fee (MYR)'] = number_format((float) $row->shipping_fee, 2, '.', '');
                if (in_array("weight", $searchModel->data_list))
                    $arr2['Weight'] = $row->weight;
                if (in_array("order_id", $searchModel->data_list))
                    $arr2['Order ID'] = $row->order_id;
                if (in_array("member_name", $searchModel->data_list))
                    $arr2['Member Name'] = $row->member_name;
                if (in_array("member_username", $searchModel->data_list))
                    $arr2['Member Username'] = $row->member_username;
                if (in_array("member_store_id", $searchModel->data_list))
                    $arr2['Member Storename'] = $MemberStore->getStoredetails($row->member_store_id);
                if (in_array("customer_name", $searchModel->data_list))
                    $arr2['Customer Name'] = $row->customer_name;
                if (in_array("customer_address1", $searchModel->data_list))
                    $arr2['Customer Address'] = $row->customer_address1 . ' ' . $row->customer_address2;
                if (in_array("customer_city", $searchModel->data_list))
                    $arr2['City'] = $row->customer_city;
                if (in_array("customer_state", $searchModel->data_list))
                    $arr2['State'] = $row->customer_state;
                if (in_array("customer_postcode", $searchModel->data_list))
                    $arr2['Postcode'] = $row->customer_postcode;
                if (in_array("customer_country_name", $searchModel->data_list))
                    $arr2['Country'] = $row->customer_country_name;
                if (in_array("customer_contact", $searchModel->data_list))
                    $arr2['Customer Contact No.'] = $row->customer_contact;
                if (in_array("customer_email", $searchModel->data_list))
                    $arr2['Customer Email'] = $row->customer_email;
                if (in_array("listing_title", $searchModel->data_list))
                    $arr2['Listing Title'] = $row->listing_title;
                if (in_array("listing_url", $searchModel->data_list))
                    $arr2['Listing URL'] = $row->listing_url;
                $arr[] = $arr2;
            }
        }
        $spreadsheet = new Spreadsheet(['dataProvider' => new ArrayDataProvider(['allModels' => $arr])]);
        $spreadsheet->renderCell('A1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('B1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('C1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('D1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('E1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('F1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('G1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('H1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('I1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('J1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('K1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('L1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('M1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('N1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('O1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('P1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('Q1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('R1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('S1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('T1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('U1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('V1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('W1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('X1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('Y1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('Z1', '', ['font' => ['bold' => true]]);

        return $spreadsheet->send('Online Sales - Orders.xls');
        die();
    }

    public function actionUpdatestockowner() {
        if (($model = Orders::findOne($_POST['id'])) !== null) {
            $model->owner_type = $_POST['owner_type'];
            $model->owner_id = $_POST['owner_id'];
            if ($model->save(false))
                return 'updated';
        }
    }

    /**
     * Displays a single Orders model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id) {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Deletes an existing Orders model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete() {
        $model = $this->findModel($_POST['id']);
        $model->order_status = 'Deleted';
        $model->save(false);
    }

    /**
     * Finds the Orders model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Orders the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id) {
        if (($model = Orders::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    public function actionStartpackage() {
        $model = new OrdersSearch();
        $model->searchpackage(Yii::$app->request->queryParams, '');
        if (empty(Yii::$app->request->queryParams['OrdersSearch']['data_list'])) {
            $model->data_list = ['order_date', 'invoice_id', 'package_name', 'package_sku', 'package_owner', 'package_price'];
        }
        return $this->render('startpackage', ['model' => $model, 'menu_id' => 85]);
    }

    public function actionIndexpackage() {
        $searchModel = new OrdersSearch();
        $dataProvider = $searchModel->searchpackage(Yii::$app->request->queryParams, 'index');

        $paramGet = Yii::$app->request->queryParams;
        unset($paramGet['r']);
        $urlExt = http_build_query($paramGet);

        return $this->render('indexpackage', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'menu_id' => 85, 'urlExt' => $urlExt]);
    }

    public function actionDownloadpackage() {
        $searchModel = new OrdersSearch();
        $model = $searchModel->searchpackage(Yii::$app->request->queryParams, 'download');
        $arr = [];
        $Package = new Package();

        if (!empty($model)) {
            foreach ($model as $row) {
                $arr2 = [];
                if (in_array("invoice_id", $searchModel->data_list))
                    $arr2['Invoice ID'] = $row->invoice_id;
                if (in_array("order_date", $searchModel->data_list))
                    $arr2['Sales Date'] = $row->order_date;
                if (in_array("package_name", $searchModel->data_list))
                    $arr2['Package Name'] = $Package->getPackageName2($row->package_id);
                if (in_array("package_sku", $searchModel->data_list))
                    $arr2['Package SKU'] = $Package->getPackageSku($row->package_id);
                if (in_array("package_price", $searchModel->data_list))
                    $arr2['Package Price (MYR)'] = $Package->getPackagePrice($row->package_id);
                if (in_array("package_owner", $searchModel->data_list))
                    $arr2['Package Owner'] = $Package->getPackageOwner($row->package_id);
                if (in_array("marketplace", $searchModel->data_list))
                    $arr2['Marketplace'] = $row->marketplace;
                if (in_array("courier", $searchModel->data_list))
                    $arr2['Courier'] = $row->courier;
                if (in_array("selling_currency", $searchModel->data_list))
                    $arr2['Selling Currency'] = $row->selling_currency;
                if (in_array("selling_price", $searchModel->data_list))
                    $arr2['Selling Price'] = number_format((float) $row->selling_price, 2, '.', '');
                if (in_array("shipping_fee", $searchModel->data_list))
                    $arr2['Shipping Fee (MYR)'] = number_format((float) $row->shipping_fee, 2, '.', '');
                if (in_array("weight", $searchModel->data_list))
                    $arr2['Weight'] = $row->weight;
                if (in_array("order_id", $searchModel->data_list))
                    $arr2['Order ID'] = $row->order_id;
                if (in_array("member_name", $searchModel->data_list))
                    $arr2['Member Name'] = $row->member_name;
                if (in_array("member_username", $searchModel->data_list))
                    $arr2['Member Username'] = $row->member_username;
                if (in_array("customer_name", $searchModel->data_list))
                    $arr2['Customer Name'] = $row->customer_name;
                if (in_array("customer_address1", $searchModel->data_list))
                    $arr2['Customer Address'] = $row->customer_address1 . ' ' . $row->customer_address2;
                if (in_array("customer_city", $searchModel->data_list))
                    $arr2['City'] = $row->customer_city;
                if (in_array("customer_state", $searchModel->data_list))
                    $arr2['State'] = $row->customer_state;
                if (in_array("customer_postcode", $searchModel->data_list))
                    $arr2['Postcode'] = $row->customer_postcode;
                if (in_array("customer_country_name", $searchModel->data_list))
                    $arr2['Country'] = $row->customer_country_name;
                if (in_array("customer_contact", $searchModel->data_list))
                    $arr2['Customer Contact No.'] = $row->customer_contact;
                if (in_array("customer_email", $searchModel->data_list))
                    $arr2['Customer Email'] = $row->customer_email;
                if (in_array("listing_title", $searchModel->data_list))
                    $arr2['Listing Title'] = $row->listing_title;
                if (in_array("listing_url", $searchModel->data_list))
                    $arr2['Listing URL'] = $row->listing_url;

                $arr[] = $arr2;
            }
        }
        $spreadsheet = new Spreadsheet(['dataProvider' => new ArrayDataProvider(['allModels' => $arr])]);
        $spreadsheet->renderCell('A1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('B1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('C1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('D1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('E1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('F1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('G1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('H1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('I1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('J1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('K1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('L1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('M1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('N1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('O1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('P1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('Q1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('R1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('S1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('T1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('U1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('V1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('W1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('X1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('Y1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('Z1', '', ['font' => ['bold' => true]]);

        return $spreadsheet->send('Online Sales - Package.xls');
        die();
    }

    // SHIPMENT REQUEST 
    public function actionUpdate($id) {
        $model = $this->findModel($id);
        $ParamList = new ParamList();
        $ProductPricing = new ProductPricing();
        $pricing = 0;
        $counter = 0;
        $User = new User();
        $Member = new Member();
        $user = Yii::$app->user->identity->id;
        $member_id = '';
        $member_name = '';
        $member_username = '';
        if (Yii::$app->request->post()) 
        {
            // GET all inputs
            $model2 = $_POST['Orders'];
            $order_status = $model->order_status;
            $invoice_id = $model->invoice_id;
            $order_date = $model2['order_date'];
            $marketplace = $model2['marketplace'];
            if ($marketplace == 'eBay')
                $marketplace = 'ebay';
            $customer_name = $model2['customer_name'];
            $customer_address1 = $model2['customer_address1'];
            $customer_address2 = $model2['customer_address2'];
            $customer_city = $model2['customer_city'];
            $customer_state = $model2['customer_state'];
            $customer_postcode = $model2['customer_postcode'];
            $customer_country_code = $model2['customer_country_code'];
            $customer_country_name = $ParamList->getlabel(21,$customer_country_code);
            $customer_contact = $model2['customer_contact'];
            $customer_email = $model2['customer_email'];
            $listing_title = $model2['listing_title'];
            $listing_url = $model2['listing_url'];
            $order_id = $model2['order_id'];
            $courier = $model2['courier'];
            $selling_mode = $model2['selling_mode'];
            $selling_price = $model2['selling_price'];
            $selling_currency = $model2['selling_currency'];
            $member_id = $User->getuserresellerid($user);
            if ($member_id != '')
            {
                $member_name = $Member->getmembername($member_id);
                $member_username = $Member->getmemberusername($member_id);
            }
            $member_store_id = $model2['member_store_id'];

            // GET price type selection
            if ($marketplace == 'Amazon' || $marketplace == 'ebay')
                $pricing = 5;
            else if ($marketplace == 'Shopee' || $marketplace == 'Lazada')
                $pricing = 3;
            else if ($marketplace == 'FDW-AU')
                $pricing = 7;
            else if ($marketplace == 'FBA')
                $pricing = 2;
            else
                $pricing = 0; //member_price

            // CURRENT PRODUCT
            if (!empty($_POST['itemlist']))
            {
                $itemlist = explode(",",$_POST['itemlist']);
                foreach($itemlist as $item_id)
                {
                    $modelUpdate = $this->findModel($_POST['id_'.$item_id]);
                    $modelUpdate->order_date = $order_date;
                    $modelUpdate->member_id = $member_id;
                    $modelUpdate->member_name = $member_name;
                    $modelUpdate->member_username = $member_username;
                    $modelUpdate->customer_name = $customer_name;
                    $modelUpdate->customer_address1 = $customer_address1;
                    $modelUpdate->customer_address2 = $customer_address2;
                    $modelUpdate->customer_city = $customer_city;
                    $modelUpdate->customer_state = $customer_state;
                    $modelUpdate->customer_postcode = $customer_postcode;
                    $modelUpdate->customer_country_code = $customer_country_code;
                    $modelUpdate->customer_country_name = $customer_country_name;
                    $modelUpdate->customer_contact = $customer_contact;
                    $modelUpdate->customer_email = $customer_email;
                    $modelUpdate->listing_title = $listing_title;
                    $modelUpdate->listing_url = $listing_url;
                    $modelUpdate->order_id = $order_id;
                    $modelUpdate->courier = $courier;
                    $modelUpdate->selling_mode = $selling_mode;
                    $modelUpdate->selling_price = $selling_price;
                    $modelUpdate->selling_currency = $selling_currency;
                    $modelUpdate->product_id = $item_id;
                    $modelUpdate->quantity = $_POST['qty_'.$item_id];
                    $productModel = EasProductVariant::findOne(['id'=>$item_id]);
                    if (!empty($productModel)) {
                        $Product = Product::find()->where(['id'=>$productModel->id])->one();
                        $product_id = $Product->id;
                        $modelUpdate->brand_id = $Product->brand_id;
                        $modelUpdate->cat_id = $Product->cat_id;

                        // PROCESS price details by marketplace
                        $productprice = $ProductPricing->getpricing($item_id,$pricing);
                        if ($productprice == '')
                            $productprice = $Product->selling_price;
                        $modelUpdate->product_price = $productprice;
                    }
                    // var_dump($modelUpdate);
                    $modelUpdate->save(false);
                    $counter++;
                }
            }

            // NEWLY ADDED PRODUCT
            if (!empty($_POST['item_ids']))
            {
                $item_ids = explode(",",$_POST['item_ids']);
                foreach($item_ids as $item_id)
                {
                    $modelOrderProd = new Orders();
                    $modelOrderProd->order_status = $order_status;
                    $modelOrderProd->invoice_id = $invoice_id;
                    $modelOrderProd->order_date = $order_date;
                    $modelOrderProd->marketplace = $marketplace;
                    $modelOrderProd->member_id = $member_id;
                    $modelOrderProd->member_name = $member_name;
                    $modelOrderProd->member_username = $member_username;
                    $modelOrderProd->member_store_id = $member_store_id;
                    $modelOrderProd->customer_name = $customer_name;
                    $modelOrderProd->customer_address1 = $customer_address1;
                    $modelOrderProd->customer_address2 = $customer_address2;
                    $modelOrderProd->customer_city = $customer_city;
                    $modelOrderProd->customer_state = $customer_state;
                    $modelOrderProd->customer_postcode = $customer_postcode;
                    $modelOrderProd->customer_country_code = $customer_country_code;
                    $modelOrderProd->customer_country_name = $customer_country_name;
                    $modelOrderProd->customer_contact = $customer_contact;
                    $modelOrderProd->customer_email = $customer_email;
                    $modelOrderProd->listing_title = $listing_title;
                    $modelOrderProd->listing_url = $listing_url;
                    $modelOrderProd->order_id = $order_id;
                    $modelOrderProd->courier = $courier;
                    $modelOrderProd->selling_mode = $selling_mode;
                    $modelOrderProd->selling_price = $selling_price;
                    $modelOrderProd->selling_currency = $selling_currency;
                    $modelOrderProd->product_id = $item_id;
                    $modelOrderProd->quantity = $_POST['qty_'.$item_id];
                    $productModel = EasProductVariant::findOne(['id'=>$item_id]);
                    if (!empty($productModel)) {
                        $Product = Product::find()->where(['id'=>$productModel->id])->one();
                        $product_id = $Product->id;
                        $modelOrderProd->brand_id = $Product->brand_id;
                        $modelOrderProd->cat_id = $Product->cat_id;

                        // PROCESS price details by marketplace
                        $productprice = $ProductPricing->getpricing($item_id,$pricing);
                        if ($productprice == '')
                            $productprice = $Product->selling_price;
                        $modelOrderProd->product_price = $productprice;
                    }
                    // var_dump($modelOrderProd);
                    $modelOrderProd->save();
                    $counter++;
                }
            }

            $this->getView()->registerJs("swal.fire({
                position: 'center-center',type: 'success',title: 'Own Shipment has been updated with total ".$counter." products',showConfirmButton: false,timer: 3000,
            }).then(function(result){window.location = '" . Url::toRoute('orders/' . Yii::$app->getRequest()->getQueryParam('returnUrl')) . "';});");
        }

        return $this->render('update', ['model' => $model, 'menu_id' => 124]);
    }

    public function actionCreate($copy_id = '') {
        $model = new Orders();
        $ParamList = new ParamList();
        $ProductPricing = new ProductPricing();
        $pricing = 0;
        $counter = 0;
        $User = new User();
        $Member = new Member();
        $user = Yii::$app->user->identity->id;
        $member_id = '';
        $member_name = '';
        $member_username = '';

        if ($copy_id != '') {
            if (($modelcopy = Orders::findOne(['id' => $copy_id])) !== null) {
                $model->customer_name = $modelcopy->customer_name;
                $model->customer_address1 = $modelcopy->customer_address1;
                $model->customer_address2 = $modelcopy->customer_address2;
                $model->customer_city = $modelcopy->customer_city;
                $model->customer_state = $modelcopy->customer_state;
                $model->customer_postcode = $modelcopy->customer_postcode;
                $model->customer_country_code = $modelcopy->customer_country_code;
                $model->customer_country_name = $modelcopy->customer_country_name;
                $model->customer_contact = $modelcopy->customer_contact;
                $model->customer_email = $modelcopy->customer_email;
            }
        }

        if (Yii::$app->request->post()) 
        {
            // GET max invoice id then + 1 to get new invoice id
            $lastinvoice  = Yii::$app->db->createCommand("SELECT invoice_id FROM orders WHERE id=(SELECT MAX(id) FROM orders)")->queryScalar();
            $invoice_id = $lastinvoice + 1;

            // GET all inputs
            $model2 = $_POST['Orders'];
            $order_date = $model2['order_date'];
            $marketplace = $model2['marketplace'];
            if ($marketplace == 'eBay')
                $marketplace = 'ebay';
            $customer_name = $model2['customer_name'];
            $customer_address1 = $model2['customer_address1'];
            $customer_address2 = $model2['customer_address2'];
            $customer_city = $model2['customer_city'];
            $customer_state = $model2['customer_state'];
            $customer_postcode = $model2['customer_postcode'];
            $customer_country_code = $model2['customer_country_code'];
            $customer_country_name = $ParamList->getlabel(21,$customer_country_code);
            $customer_contact = $model2['customer_contact'];
            $customer_email = $model2['customer_email'];
            $listing_title = $model2['listing_title'];
            $listing_url = $model2['listing_url'];
            $order_id = $model2['order_id'];
            $courier = $model2['courier'];
            $selling_mode = $model2['selling_mode'];
            $selling_price = $model2['selling_price'];
            $selling_currency = $model2['selling_currency'];
            $member_id = $User->getuserresellerid($user);
            if ($member_id != '')
            {
                $member_name = $Member->getmembername($member_id);
                $member_username = $Member->getmemberusername($member_id);
            }
            $member_store_id = $model2['member_store_id'];

            // GET price type selection
            if ($marketplace == 'Amazon' || $marketplace == 'ebay')
                $pricing = 5;
            else if ($marketplace == 'Shopee' || $marketplace == 'Lazada')
                $pricing = 3;
            else if ($marketplace == 'FDW-AU')
                $pricing = 7;
            else if ($marketplace == 'FBA')
                $pricing = 2;
            else
                $pricing = 0; //member_price

            // ADD product details
            if (!empty($_POST['product_ids']))
            {
                $product_ids = explode(",",$_POST['product_ids']);
                foreach($product_ids as $product_id)
                {
                    $modelOrderProd = new Orders();
                    $modelOrderProd->order_status = 'pending';
                    $modelOrderProd->invoice_id = $invoice_id;
                    $modelOrderProd->order_date = $order_date;
                    $modelOrderProd->marketplace = $marketplace;
                    $modelOrderProd->member_id = $member_id;
                    $modelOrderProd->member_name = $member_name;
                    $modelOrderProd->member_username = $member_username;
                    $modelOrderProd->member_store_id = $member_store_id;
                    $modelOrderProd->customer_name = $customer_name;
                    $modelOrderProd->customer_address1 = $customer_address1;
                    $modelOrderProd->customer_address2 = $customer_address2;
                    $modelOrderProd->customer_city = $customer_city;
                    $modelOrderProd->customer_state = $customer_state;
                    $modelOrderProd->customer_postcode = $customer_postcode;
                    $modelOrderProd->customer_country_code = $customer_country_code;
                    $modelOrderProd->customer_country_name = $customer_country_name;
                    $modelOrderProd->customer_contact = $customer_contact;
                    $modelOrderProd->customer_email = $customer_email;
                    $modelOrderProd->listing_title = $listing_title;
                    $modelOrderProd->listing_url = $listing_url;
                    $modelOrderProd->order_id = $order_id;
                    $modelOrderProd->courier = $courier;
                    $modelOrderProd->selling_mode = $selling_mode;
                    $modelOrderProd->selling_price = $selling_price;
                    $modelOrderProd->selling_currency = $selling_currency;
                    $modelOrderProd->product_id = $product_id;
                    $modelOrderProd->quantity = $_POST['qty_'.$product_id];
                    $productModel = EasProductVariant::findOne(['id'=>$product_id]);
                    if (!empty($productModel)) {
                        $Product = Product::find()->where(['id'=>$productModel->id])->one();
                        $product_id = $Product->id;
                        $modelOrderProd->brand_id = $Product->brand_id;
                        $modelOrderProd->cat_id = $Product->cat_id;

                        // PROCESS price details by marketplace
                        $productprice = $ProductPricing->getpricing($product_id,$pricing);
                        if ($productprice == '')
                            $productprice = $Product->selling_price;
                        $modelOrderProd->product_price = $productprice;
                    }
                    $modelOrderProd->save(false);
                    $counter++;
                }
            }

            // ADD package - product details
            $Package = new Package();
            if (!empty($_POST['package_ids']))
            {
                $package_ids = explode(",",$_POST['package_ids']);
                foreach($package_ids as $package_id)
                {
                    $modelProductList = PackageProductList::find()->where(['package_id' => $package_id])->orderBy(['id' => SORT_ASC])->all();
                    if (!empty($modelProductList)) 
                    {
                        foreach ($modelProductList as $rowProductList) 
                        {
                            $modelOrder = new Orders();
                            $modelOrder->order_status = 'pending';
                            $modelOrder->invoice_id = $invoice_id;
                            $modelOrder->order_date = $order_date;
                            $modelOrder->marketplace = $marketplace;
                            $modelOrder->member_id = $member_id;
                            $modelOrder->member_name = $member_name;
                            $modelOrder->member_username = $member_username;
                            $modelOrder->member_store_id = $member_store_id;
                            $modelOrder->customer_name = $customer_name;
                            $modelOrder->customer_address1 = $customer_address1;
                            $modelOrder->customer_address2 = $customer_address2;
                            $modelOrder->customer_city = $customer_city;
                            $modelOrder->customer_state = $customer_state;
                            $modelOrder->customer_postcode = $customer_postcode;
                            $modelOrder->customer_country_code = $customer_country_code;
                            $modelOrder->customer_country_name = $customer_country_name;
                            $modelOrder->customer_contact = $customer_contact;
                            $modelOrder->customer_email = $customer_email;
                            $modelOrder->listing_title = $listing_title;
                            $modelOrder->listing_url = $listing_url;
                            $modelOrder->order_id = $order_id;
                            $modelOrder->courier = $courier;
                            $modelOrder->weight = $Package->getPackageWeight($package_id)*$_POST['qtyPack_'.$package_id];
                            $modelOrder->selling_mode = $selling_mode;
                            $modelOrder->selling_price = $selling_price;
                            $modelOrder->selling_currency = $selling_currency;
                            $modelOrder->package_id = $package_id;
                            $modelOrder->product_id = $rowProductList->variant_id;
                            $modelOrder->quantity = $rowProductList->quantity*$_POST['qtyPack_'.$package_id];
                            $productModel = EasProductVariant::findOne(['id'=>$rowProductList->variant_id]);
                            if (!empty($productModel)) {
                                $Product = Product::find()->where(['id'=>$productModel->id])->one();
                                $product_id = $Product->id;
                                $modelOrder->brand_id = $Product->brand_id;
                                $modelOrder->cat_id = $Product->cat_id; 
                            }

                            /* PROCESS price details by marketplace
                            1) Check if product id inside product_pricing table
                            2) If yes, take price according to $pricing
                            3) If no, take product price directly
                            */
                            $productprice = $ProductPricing->getpricing($product_id,$pricing);
                            if ($productprice == '')
                                $productprice = $rowProductList->price;
                            $modelOrder->product_price = $productprice;
                            $modelOrder->save(false);
                            $counter++;
                        }
                    }
                }
            }

            // DECLARE VALUE CALCULATION - not for MY
            $maxvalue = 0;
            $minArray = [];
            $mainArray = [];
            $countallproduct = 0;
            $countminproduct = 0;
            $countmainproduct = 0;
            $totalminproduct = 0;
            $currentpricing = $selling_price;
            echo $customer_country_code;
            if ($customer_country_code != 'MY')
            {
                $maxvalue = Yii::$app->db->createCommand("SELECT max_declare_value FROM countrycode WHERE code='".$customer_country_code."'")->queryScalar();

                $OrdersItem = Yii::$app->db->createCommand("SELECT * FROM orders WHERE invoice_id='".$invoice_id."'")->queryAll();
                foreach ($OrdersItem as $item)
                {
                    if ($item['product_price'] < 2)
                    {
                        
                        $minArray[$item['id']]['prod_id'] = $item['product_id'];
                        $minArray[$item['id']]['qty'] = $item['quantity'];
                        $countminproduct++;
                    }
                    else
                    {
                        $mainArray[$item['id']]['prod_id'] = $item['product_id'];
                        $mainArray[$item['id']]['qty'] = $item['quantity'];
                        $countmainproduct++;
                    }
                    $countallproduct++;
                }
                
                foreach($minArray as $minprod)
                {
                    $declare_minprod = (float) $minprod['qty'] * 0.2;
                    $totalminproduct += $declare_minprod;
                    // echo $minprod['prod_id']." --- ".$declare_minprod;
                    Yii::$app->db->createCommand("UPDATE orders SET declare_value='0.2' WHERE invoice_id='".$invoice_id."' AND product_id='".$minprod['prod_id']."'")->execute();
                }
                if ($currentpricing > $maxvalue || $currentpricing < 2)
                    $currentpricing = $maxvalue;
                foreach ($mainArray as $mainprod)
                {
                    $declare_value = ($currentpricing-$totalminproduct)/$mainprod['qty']/$countmainproduct;
                    $declare_mainprod = round($declare_value * 2, 1) / 2;
                    Yii::$app->db->createCommand("UPDATE orders SET declare_value='".$declare_mainprod."' WHERE invoice_id='".$invoice_id."' AND product_id='".$mainprod['prod_id']."'")->execute();
                }
            }

            $this->getView()->registerJs("swal.fire({
                position: 'center-center',type: 'success',title: 'Order has been submitted with total ".$counter." products',showConfirmButton: false,timer: 3000,
            }).then(function(result){window.location = '" . Url::to(['orders/ordershistory']) . "';});");

        } // END POST

        return $this->render('create', [
            'model' => $model,
            'menu_id' => 124,
        ]);
    }

    public function actionGetproductlist()
    {
        $brand = $_POST['brand'];
        $type = $_POST['type'];
        $items = explode(",",$_POST['itemlist']);

        $output = '';
        if ($type == 'item')
        {
            $modelProduct = Product::find()->where(['brand_id' => $brand])->andwhere(['status' => 1,'com_id'=>2])->andwhere(['NOT IN','id',$items])->orderBy(['name' => SORT_ASC])->all();
            if (!empty($modelProduct)) {
                $output .= '<select id="selectitem" class="form-control kt-selectpicker">
                <option value="">- Select Product -</option>';
                foreach ($modelProduct as $rowProduct) {
                    $output .= '<option value="'.$rowProduct['id'].'">'.$rowProduct['name'].'</option>';
                }
                $output.= '</select>';
            }
            else
                $output.= 'Sorry...<br>No product found for this brand';

        }
        else
        {   
            $modelProduct = Product::find()->where(['brand_id' => $brand])->andwhere(['status' => 1,'com_id'=>2])->orderBy(['name' => SORT_ASC])->all();
            if (!empty($modelProduct)) {
                $modelProduct = Product::find()->where(['brand_id' => $brand])->andwhere(['status' => 1,'com_id'=>2])->orderBy(['name' => SORT_ASC])->all();

                $output .= '<select id="selectproduct" class="form-control kt-selectpicker">
                <option value="">- Select Product -</option>';
                foreach ($modelProduct as $rowProduct) {
                    $output .= '<option value="'.$rowProduct['id'].'">'.$rowProduct['name'].'</option>';
                }
                $output.= '</select>';
            }
            else
                $output.= 'Sorry...<br>No product found for this brand';
        }
        return $output;
    }

    public function actionGetpackagelist()
    {
        $brand = $_POST['brand'];
        $output = '';
        $user = Yii::$app->user->identity->id;
        $User = new User();
        $reseller_id = $User->getuserresellerid($user);
        $modelBrand = Brand::findOne($brand);
        if (!empty($modelBrand))
        {
            $modelPackage = Package::find()->where(['brand_id' => $brand])->andwhere(['status' => 1])->andWhere(['OR',['LIKE','publish_level',$reseller_id],['=','publish_level','']])->orderBy(['name' => SORT_ASC])->all();
            if (!empty($modelPackage)) {
                $output .= '<select id="selectpackage" class="form-control kt-selectpicker">
                <option value="">- Select Package -</option>';
                foreach ($modelPackage as $rowPackage) {
                    $output .= '<option value="'.$rowPackage['id'].'">'.$rowPackage['name'].'</option>';
                }
                $output.= '</select>';
            }
            else
            {
                $output.= 'Sorry...<br>No package found for this brand';
            }
        }
        else
        {
            $output .= 'Sorry...<br>Brand not found';
        }
        
        return $output;
    }

    public function actionAddproductorder()
    {
        $product = $_POST['product'];
        $type = $_POST['type'];
        $output = '';
        $imglink = Url::base() . '/uploads/product/no-image.png';
        $modelProduct = Product::findOne($product);
        if (!empty($modelProduct)) {
            if (!empty($modelProduct->photo))
                $imglink = Url::base() . '/uploads/product/' . $modelProduct->photo;
            
            if ($type == 'item') {
                $output .= "<tr><td width='1%' class='pl-0' style='border-top:0;'><img src='".$imglink."' style='width:60px;'></td>
                <td class='pl-0' style='border-top:0;'>
                ".$modelProduct->name."<br>
                <span class='text-light-gray pr-3'><small>MYR ".$modelProduct->selling_price."</small></span>
                </td>
                <td class='text-right pl-0 pr-0' style='border-top:0;vertical-align: middle;width:10%'><h3 class='font-weight-light'><input class='form-control' type='text' name='qtyAjax_".$modelProduct->id."' id='qtyAjax_".$modelProduct->id."' value=1 onChange='changeqtyproduct(".$modelProduct->id.")'></h3></td>
                <td style='border-top:0;vertical-align: middle'>".Html::button("<i class='fas fa-trash-alt kt-font-danger'></i>", ["onclick"=>"removerowitem($modelProduct->id)","class" => "btn", "id" => "btn_delete$modelProduct->id"])."</td></tr>";
            }
            else {
                $output .= "<tr><td width='1%' class='pl-0' style='border-top:0;'><img src='".$imglink."' style='width:60px;'></td>
                <td class='pl-0' style='border-top:0;'>
                ".$modelProduct->name."<br>
                <span class='text-light-gray pr-3'><small>MYR ".$modelProduct->selling_price."</small></span>
                </td>
                <td class='text-right pl-0 pr-0' style='border-top:0;vertical-align: middle;width:10%'><h3 class='font-weight-light'><input class='form-control' type='text' name='qtyAjax_".$modelProduct->id."' id='qtyAjax_".$modelProduct->id."' value=1 onChange='changeqtyproduct(".$modelProduct->id.")'></h3></td>
                <td style='border-top:0;vertical-align: middle'>".Html::button("<i class='fas fa-trash-alt kt-font-danger'></i>", ["onclick"=>"removerowproduct($modelProduct->id)","class" => "btn", "id" => "btn_delete$modelProduct->id"])."</td></tr>";
            }
        }
        return $output;
    }

    public function actionAddpackageorder()
    {
        $package = $_POST['package'];
        $PRODUCT = new Product();
        $output = '';
        $imglink = Url::base() . '/uploads/product/no-image.png';
        $productlistdisplay = '';

        $ProductList = PackageProductList::find()->where(['package_id'=>$package])->all();
        if (!empty($ProductList))
        {
            foreach ($ProductList as $Product) {
                $productlistdisplay .="<ul>";
                $productlistdisplay .= "<small><li>".$PRODUCT->getname($Product['variant_id'])." X ".$Product['quantity']."</li></small>";
                $productlistdisplay .="</ul>";
            }
        }

        $modelPackage = Package::findOne($package);
        if (!empty($modelPackage)) {
            $Image = PackageGallery::find()->where(['package_id'=>$modelPackage->id])->one();
            if (!empty($Image))
                $imglink = 'http://dev.axisdigitalleap.asia/web/'. $Image->img_url;

            $output .= "<tr><td width='1%' class='pl-0' style='border-top:0;'><img src='".$imglink."' style='width:60px;'></td>
            <td class='pl-0' style='border-top:0;'>
            ".$modelPackage->name."<br>
            <a data-toggle='collapse' href='#collapse_".$modelPackage->id."' role='button' aria-expanded='false' aria-controls='collapseExample'>
            <small>See product list</small>
            </a><br>
            <div class='collapse' id='collapse_".$modelPackage->id."'>
            ".$productlistdisplay."
            </div>
            <span class='text-light-gray pr-3'><small>MYR ".$modelPackage->price."</small></span>
            </td>
            <td class='text-right pl-0 pr-0' style='border-top:0;vertical-align: middle;width:10%'><h3 class='font-weight-light'><input class='form-control' type='text' name='qtyPackageAjax_".$modelPackage->id."' id='qtyPackageAjax_".$modelPackage->id."' value=1 onchange='changeqtypackage(".$modelPackage->id.")'></h3></td>
            <td style='border-top:0;vertical-align: middle'>".Html::button("<i class='fas fa-trash-alt kt-font-danger'></i>", ["onclick"=>"removerowpackage($modelPackage->id)","class" => "btn", "id" => "btn_deletepackage$modelPackage->id"])."</td></tr>";
        }
        return $output;
    }

    public function actionGetstorebymarketplace()
    {
        $ParamList = new ParamList();
        $mp = $_POST['marketplace'];
        $marketplace = $mp;
        // $marketplace = $ParamList->getlabel(13,$mp);
        $user = Yii::$app->user->identity->id;
        $User = new User();
        $reseller_id = $User->getuserresellerid($user);
        if ($marketplace == 'FBA')
            $marketplace = 'Amazon';
        else if ($marketplace == 'FDW-AU')
            $marketplace='';

        $output = '';
        if ($marketplace != '')
        {
            $model = MemberStore::find()->where(['member_id' => $reseller_id,'marketplace'=>$marketplace])->orderBy(['store_name' => SORT_ASC])->all();
            if (!empty($model)) {
                foreach ($model as $row) {
                    $output .= '<option value="' . $row->id . '">' . $row->store_name . '('.$row->marketplace.')</option>';
                }
            }
        }
        else
        {
            $model = MemberStore::find()->where(['member_id' => $reseller_id])->orderBy(['store_name' => SORT_ASC])->all();
            if (!empty($model)) {
                foreach ($model as $row) {
                    $output .= '<option value="' . $row->id . '">' . $row->store_name . '('.$row->marketplace.')</option>';
                }
            }
        }
        
        return $output;
    }

    // SHIPMENT HISTORY
    public function actionOrdershistory() {
        $searchModel = new OrdersSearch();
        $dataProvider = $searchModel->searchorderhistory(Yii::$app->request->queryParams, 'ordershistory');

        $paramGet = Yii::$app->request->queryParams;
        unset($paramGet['r']);
        $urlExt = http_build_query($paramGet);

        return $this->render('ordershistory', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'menu_id' => 128, 'urlExt' => $urlExt]);
    }

    // SHIPMENT HISTORY - download
    public function actionDownloadhistory() {
        $searchModel = new OrdersSearch();
        $model = $searchModel->searchorderhistory(Yii::$app->request->queryParams, 'downloadhistory');
        $arr = [];
        $ParamList = new ParamList();
        $Orders = new Orders();
        
        if (!empty($model))
        {
            foreach ($model as $row) {

                $totalproductprice = $Orders->gettotalproductprice($row->invoice_id);
                $totalcost = $row->shipping_fee + $totalproductprice;
                if ($row->selling_currency != 'MYR')
                {
                    $dollarValue = Yii::$app->sharedfunction->convertCurrency($row->selling_currency);
                    $selling_price = $row->selling_price * $dollarValue;
                    $earning = $selling_price - $totalcost;
                }
                else
                {
                    $earning = $row->selling_price - $totalcost;
                }

                $arr2 = [];
                $arr2['Order Date'] = date('d/m/Y',strtotime($row->order_date));
                $arr2['Invoice Num'] = "TR".$row->invoice_id;
                $arr2['Customer Details'] = $row->customer_name." - ".$row->customer_country_code." (".$row->customer_contact.")";
                $arr2['Listing Details'] = $row->listing_title;
                $arr2['Product Details'] = $Orders->getproductlist($row->invoice_id);
                $arr2['Courier Details'] = $row->courier;
                $arr2['Tracking Code'] = '';
                $arr2['Profit/Loss Estimation'] = "MYR ".$earning;
                $arr[] = $arr2;
            }

        }

        $spreadsheet = new Spreadsheet(['dataProvider' => new ArrayDataProvider(['allModels' => $arr])]);
        $spreadsheet->renderCell('A1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('B1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('C1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('D1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('E1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('F1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('G1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('H1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('I1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('J1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('K1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('L1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('M1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('N1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('O1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('P1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('Q1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('R1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('S1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('T1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('U1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('V1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('W1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('X1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('Y1', '', ['font' => ['bold' => true]]);
        $spreadsheet->renderCell('Z1', '', ['font' => ['bold' => true]]);

        return $spreadsheet->send('Shipment History.xls');
        exit();  
    }

    // SHIPMENT PROCESSING - all pending
    public function actionPendingorders() {

        $searchModel = new OrdersSearch();
        $dataProvider = $searchModel->searchordershipment(Yii::$app->request->queryParams, 'pendingorders');
        $paramGet = Yii::$app->request->queryParams;
        unset($paramGet['r']);
        $urlExt = http_build_query($paramGet);

        return $this->render('pendingorders', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'menu_id' => 130, 'urlExt' => $urlExt]);
    }

    // SHIPMENT PROCESSING - partial pending
    public function actionPartialpendingorders() {

        $searchModel = new OrdersSearch();
        $dataProvider = $searchModel->searchordershipment(Yii::$app->request->queryParams, 'partialpendingorders');
        $paramGet = Yii::$app->request->queryParams;
        unset($paramGet['r']);
        $urlExt = http_build_query($paramGet);

        return $this->render('partialpendingorders', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'menu_id' => 131, 'urlExt' => $urlExt]);
    }

    // SHIPMENT PROCESSING - downloadpickinglist
    public function actionDownloadpickinglist() {
        $mode = $_GET['mode'];
        if ($mode == 'all')
            $action = 'downloadpending';
        else
            $action = 'downloadpartialpending';

        $searchModel = new OrdersSearch();
        $model = $searchModel->searchordershipment(Yii::$app->request->queryParams, $action);
        $productdetails = [];
        $packagedetails = [];
        $shipmentdetails = [];

        $products = [];
        $packages = [];
        $product_list = [];
        $package_list = [];

        $ParamList = new ParamList();
        $Orders = new Orders();
        $empty = '';
        $countershipment = 1;
        $counterproduct = 1;
        $counterpackage = 1;

        if (!empty($model))
        {
            foreach ($model as $row) {
                // Update order status to "under process" when download picking list
                Yii::$app->db->createCommand("UPDATE orders SET order_status='under process' WHERE invoice_id='".$row->invoice_id."'")->execute();

                // Get all products and packages
                $products[] = $Orders->getproducts($row->invoice_id);
                $packages[] = $Orders->getpackages($row->invoice_id);

                // Get all shipment details
                $shipmentdetails[] = $countershipment.",".$row->invoice_id.",".$row->customer_name.",".$row->customer_country_code.",".$Orders->getproductpickinglist($row->invoice_id).",".$row->courier;
                $countershipment++;
            }
        }

        // PRODUCT LIST DETAILS
        foreach ($products as $key => $value) 
        {
            foreach ($value as $v => $k) 
            {
                foreach ($k as $id => $qty) 
                {
                    if (!isset($product_list[$id]))
                        $product_list[$id] = $qty;
                    else
                        $product_list[$id] += $qty;
                }
            }
        }
        $totalproduct = count($product_list);
        $Product = new Product();
        foreach ($product_list as $key => $value) {
            $productdetails[] = $empty.",".$empty.",".$empty.",".$counterproduct.",".$Product->getname($key). " [".$Product->getuom($key)."],".$value;
            $counterproduct++;
        }

        // PACKAGE LIST DETAILS
        foreach ($packages as $key => $value) 
        {
            foreach ($value as $v => $k) 
            {
                foreach ($k as $id => $qty) 
                {
                    if (!isset($package_list[$id]))
                        $package_list[$id] = $qty;
                    else
                        $package_list[$id] += $qty;
                }
            }
        }
        $totalpackage = count($package_list);
        $PackageItem = new PackageProductList();
        foreach ($package_list as $key => $value) {
            $packagedetails[] = $empty.",".$empty.",".$empty.",".$counterpackage.",".$PackageItem->getpackageitemlist($key).",".$value;
            $counterpackage++;
        }

        header('Content-Type: application/excel');
        header('Content-Disposition: attachment; filename="Shipment Picking List.csv"');
        $fp = fopen('php://output', 'w');

        $content = $empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty;
        $val = explode(",", $content);
        fputcsv($fp, $val);

        //** PRODUCT DETAILS **//
        $content = $empty.",".$empty.",".$empty.",Total ".$totalproduct." Product,".$empty.",".$empty.",".$empty.",".$empty;
        $val = explode(",", $content);
        fputcsv($fp, $val);
        
        foreach ($productdetails as $row)
        {
            $val = explode(",", trim($row));
            fputcsv($fp, $val);
        }
        //** END PRODUCT DETAILS **//

        $content = $empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty;
        $val = explode(",", $content);
        fputcsv($fp, $val);

        //** PACKAGE DETAILS **//
        $content = $empty.",".$empty.",".$empty.",Total ".$totalpackage." Package,".$empty.",".$empty.",".$empty.",".$empty;
        $val = explode(",", $content);
        fputcsv($fp, $val);
        
        foreach ($packagedetails as $row)
        {
            $val = explode(",", trim($row));
            fputcsv($fp, $val);
        }
        //** END PACKAGE DETAILS **//

        $content = $empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty;
        $val = explode(",", $content);
        fputcsv($fp, $val);

        //** SHIPMENT DETAILS **//
        $shipmentheader = array('No,Invoice ID,Customer_Name,Country,Item,Shipping_Mode');
        foreach ($shipmentheader as $line ) 
        {
            $val = explode(",", $line);
            fputcsv($fp, $val);
        }
        
        foreach ($shipmentdetails as $row)
        {
            $val = explode(",", trim($row));
            fputcsv($fp, $val);
        }
        //** END SHIPMENT DETAILS **//

        fclose($fp);
        exit();
    }

    // SHIPMENT PROCESSING - download fdw output file
    public function actionDownloadfdw() {
        $mode = $_GET['mode'];
        if ($mode == 'all')
            $action = 'downloadpending';
        else
            $action = 'downloadpartialpending';

        $searchModel = new OrdersSearch();
        $model = $searchModel->searchordershipment(Yii::$app->request->queryParams, $action);

        $ParamList = new ParamList();
        $Orders = new Orders();
        $countershipment = 1;

        if (!empty($model))
        {
            foreach ($model as $row) {
                // Update order status to "completed" when download fdw list
                Yii::$app->db->createCommand("UPDATE orders SET order_status='completed' WHERE invoice_id='".$row->invoice_id."'")->execute();

                // Get all products list
                $arr[] = $Orders->getproductsfdw($row->invoice_id);
            }
        }

        // ORDER DETAILS
        $ordersarr = [];
        foreach ($arr as $key => $value) 
        {
            foreach ($value as $v => $k) 
            {
                foreach ($k as $keyitem => $item) 
                {
                    $ordersarr[$v]['Date'] = $k['date'];
                    $ordersarr[$v]['Order Reference'] = $k['orderref'];
                    $ordersarr[$v]['SKU No.'] = $k['product'];
                    $ordersarr[$v]['Quantity'] = $k['product_qty'];
                    $ordersarr[$v]['Recipient Name'] = $k['customer_name'];
                    $ordersarr[$v]['Recipient Phone No.'] = $k['customer_contact'];
                    $ordersarr[$v]['Recipient Email'] = $k['customer_email'];
                    $ordersarr[$v]['Address 1'] = $k['customer_address1'];
                    $ordersarr[$v]['Address 2'] = $k['customer_address2'];
                    $ordersarr[$v]['Suburb'] = $k['customer_city'];
                    $ordersarr[$v]['State'] = $k['customer_state'];
                    $ordersarr[$v]['Postcode'] = $k['customer_postcode'];
                    $ordersarr[$v]['Country'] = $k['customer_country_code'];
                    $ordersarr[$v]['Courier'] = $k['courier'];
                    $ordersarr[$v]['Tracking Number'] = $k['tracking_number'];
                }
            }
        }

        header('Content-Type: application/excel');
        header('Content-Disposition: attachment; filename="AXIS FDW-AU '.date("Ymd").'.csv"');
        $fp = fopen('php://output', 'w');

        $headerdata = array('No.,Date,Order Reference,SKU No.,Quantity,Recipient Name,Recipient Phone No.,Recipient Email,Address 1,Address 2,Suburb,State,Postcode,Country,Courier,Tracking Number');
        foreach ($headerdata as $line ) {
            $val = explode(",", $line);
            fputcsv($fp, $val);
        }

        $content = '';
        foreach ($ordersarr as $key => $value) {
            $content .= $countershipment.",";
            foreach ($value as $k => $v) {
                $content .= $v.",";
            }
            $content .= '\n';
            $countershipment++;
        }

        $arraycontent = explode('\n', $content);
        foreach ($arraycontent as $line) {
            $val = explode(",", $line);
            fputcsv($fp, $val);
        }

        fclose($fp);
        exit();
    }

    // SHIPMENT PROCESSING - print individual invoice
    public function actionPrintinvoice($invoice_id) {
        $ParamList = new ParamList();
        $Notes = new Notes();
        $Product = new Product();

        // HEADER - Company Details
        $modelcom = Company::findOne(2);
        if (!empty($modelcom)) {
            $header = '<table id="table-header" border="0" width="100%" cellpadding="0" cellspacing="0">
            <tr>
            <td rowspan="2"><img src="uploads/' . $modelcom->logo . '" height="80"></td>
            <td align="right"><b>' . $modelcom->name . ' ('.$modelcom->reg_no.')</b></td>
            </tr>
            <tr>
            <td align="right" style="font-size:10px;">' . $modelcom->address1 .'<br> '.$modelcom->address2.'<br>'.
            $modelcom->postcode.' '.$modelcom->city.', '.$ParamList->getlabel(3,$modelcom->state).', Malaysia<br>
            (T) '.$modelcom->phone_no.'  (F) '.$modelcom->fax_no.'<br></td>
            </tr>
            </table>';
        }

        $addressline = '';
        $content = '';
        $upcontent = '';
        $tablecontent = '';
        $counter = 1;
        $totalamount = 0;
        $totalqty = 0;
        $price = 0;
        $discount = 0;
        $tax = 0;

        $tablecontent .= '<table width="100%" cellpadding="3" cellspacing="1" border="0" style="background-color:black;" id="table-product">
        <thead>
        <tr>
        <th width=5% style="font-size:10px">BIL.</th>
        <th width=12% style="font-size:10px">ITEM NO.</th>
        <th style="font-size:10px">DESCRIPTIONS</th>
        <th width=10% style="font-size:10px">QTY</th>
        <th width=10% style="font-size:10px">UNIT PRICE</th>
        <th width=10% style="font-size:10px">DISCOUNT</th>
        <th width=10% style="font-size:10px">TAX</th>
        <th width=10% style="font-size:10px">AMOUNT</th>
        </tr>
        </thead>
        <tbody>';
        $invoice = Yii::$app->db->createCommand("SELECT * FROM orders WHERE invoice_id=".$invoice_id."")->queryAll();
        if (!empty($invoice))
        {
            foreach ($invoice as $row) {
                if ($row['customer_address1'] == $row['customer_address2'])
                    $addressline = $row['customer_address1'];
                else
                    $addressline = $row['customer_address1'];
                $cust_address = $addressline."<br>".$row['customer_city'].",".$row['customer_state']."<br><b>".$row['customer_postcode']."<br>".$row['customer_country_name']."</b>";
                $upcontent = '<table border="0" width="100%" cellpadding="0" cellspacing="0">
                <tr>
                <td width="50%" valign="top">
                <br/><b>ATTN</b>
                <table cellpadding="0" cellspacing="0">
                <tr>
                <td valign="top">Name</td>
                <td valign="top">&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                <td valign="top">'.$row['customer_name'].'</td>
                </tr>
                <tr>
                <td valign="top">Address</td>
                <td valign="top">&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                <td valign="top">'.$cust_address.'</td>
                </tr>
                <tr>
                <td valign="top">Phone No</td>
                <td valign="top">&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                <td valign="top">'.$row['customer_contact'].'</td>
                </tr>
                </table>
                </td>
                <td width="50%" valign="top" align="right">
                <table cellpadding="0" cellspacing="0" style="padding-right:1em;">
                <tr><td colspan="3" align="center"><h4><b>INVOICE</b></h4><br/><br/></td></tr>
                <tr>
                <td valign="top"><b>Invoice No</b></td>
                <td valign="top">&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                <td valign="top" align="left">TR'.$invoice_id.'</td>
                </tr>
                <tr>
                <td valign="top"><b>Date</b></td>
                <td valign="top">&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                <td valign="top" align="left">'.date('d-M-Y',strtotime($row['order_date'])).'</td>
                </tr>
                <tr>
                <td valign="top"></td>
                <td valign="top">&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td valign="top" align="left"></td>
                </tr>
                <tr>
                <td valign="top"></td>
                <td valign="top">&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td valign="top" align="left"></td>
                </tr>
                <tr>
                <td valign="top"></td>
                <td valign="top">&nbsp;&nbsp;&nbsp;&nbsp;</td>
                <td valign="top" align="left"></td>
                </tr>
                <tr>
                <td valign="top"><b>Agent</b></td>
                <td valign="top">&nbsp;&nbsp;:&nbsp;&nbsp;</td>
                <td valign="top" align="left">' . $row['member_name'].'</td>
                </tr>
                </table>
                </td>
                </tr>
                </table>';

                // $price = (float) $Product->getprice($row['product_id']);
                if ($row['selling_currency'] != 'MYR')
                {
                    $dollarValue = Yii::$app->sharedfunction->convertCurrency($row['selling_currency']);
                    $price = (float) $row['declare_value'] * $dollarValue;
                }
                else
                {
                    $price = (float) $row['declare_value'];
                }
                $amount = $price * $row['quantity'];

                $tablecontent .= '<tr>
                <td style="font-size:10px">'.$counter.'</td>
                <td style="font-size:10px">'.$Product->getsku($row['product_id']).'</td>
                <td style="font-size:10px">'.$Product->getname($row['product_id']).'</td>
                <td style="font-size:10px;text-align:center;">'.$row['quantity'].'</td>
                <td style="font-size:10px;text-align:right;">'.number_format($price,2,".",",").'</td>
                <td style="font-size:10px"></td>
                <td style="font-size:10px"></td>
                <td style="font-size:10px;text-align:right;">'.number_format($amount,2,".",",").'</td>
                </tr>';

                $counter++;
                $totalamount += $amount;
                $totalqty += $row['quantity'];
                $currency = $row['selling_currency'];
                $courier = $row['courier'];
            }
            $tablecontent.='</tbody></table>';
        }

        $content = $upcontent."<br>".$tablecontent."<br>";

        $content .= '<table width="100%" cellpadding="2" cellspacing="0" border="0">
        <tr>
        <td rowspan=5 style="vertical-align:top;">Total Quantity: '.$totalqty.'<br>
        Shipping Mode: '.$courier.'<br>
        <b>'.str_replace("RINGGIT MALAYSIA",$currency,strtoupper(Yii::$app->sharedfunction->convertMoneyToText($totalamount))).'</b></td>
        <td style="text-align:right">Total</td>
        <td width="2%">&nbsp;:</td>
        <td style="text-align:right;width:10%;">'.number_format($totalamount,2,".",",").'</td>
        </tr>
        <tr>
        <td style="text-align:right">Discount</td>
        <td>&nbsp;:</td>
        <td style="text-align:right">'.number_format($discount,2,".",",").'</td>
        </tr>
        <tr>
        <td style="text-align:right">Net</td>
        <td>&nbsp;:</td>
        <td style="text-align:right">'.number_format($totalamount,2,".",",").'</td>
        </tr>
        <tr>
        <td style="text-align:right">Tax</td>
        <td>&nbsp;:</td>
        <td style="text-align:right">'.number_format($tax,2,".",",").'</td>
        </tr>
        <tr>
        <td style="text-align:right">Gross</td>
        <td>&nbsp;:</td>
        <td style="text-align:right"><b>'.$currency.' '.number_format($totalamount,2,".",",").'</b></td>
        </tr>
        </table>';

        $company_stamp = '<td align="center" height="112"></td>';
        if (!empty($modelcom->stamp))
            $company_stamp = '<td align="center" style="width:150"><img src="uploads/' . $modelcom->stamp . '" height="100"></td>';

        $content .= '<br><br><table width="50%">
        <tr>
        ' . $company_stamp . '
        <td style="width:120"></td>
        <td style="width:120"></td>
        </tr>
        <tr>
        <td align="center" style="border-top:1px solid black;">Authorized Signature</td>
        <td align="center"></td>
        <td align="center" style="border-top:1px solid black;">Received By</td>
        </tr>
        </table>';

        $footer = '<table id="table-footer" border="0" width="100%" cellpadding="0" cellspacing="0">
        <tr><td align="center" style="font-size:10px;"><b>' . $modelcom->tagline . '</b><br>' . $modelcom->website . '
        </td>
        </tr>
        </table>';

        // setup kartikmpdf\Pdf component
        $pdf = new Pdf([
            'mode' => Pdf::MODE_CORE,
            'filename' => 'Invoice TR' . $invoice_id . '.pdf',
            'format' => Pdf::FORMAT_A4,
            'orientation' => Pdf::ORIENT_PORTRAIT,
            'destination' => Pdf::DEST_BROWSER,
            'cssFile' => '@vendor/kartik-v/yii2-mpdf/src/assets/kv-mpdf-bootstrap.min.css',
            'cssInline' => 'body,table td{font-size:11px;Arial, Helvetica, sans-serif;line-height:1.7em;}
                        #table-header{padding-bottom:10px;}
                        #table-footer{padding-top:10px;}
                        #table-product th{background-color:black;color:white;text-align:center;}
                        #table-product tr:nth-child(odd) td{background-color:white;}
                        #table-product tr:nth-child(even) td{background-color:whitesmoke;}',
            'methods' => ['SetHeader' => [$header], 'SetFooter' => $footer],
            'content' => $content,
            'options' => ['setAutoTopMargin' => 'pad','setAutoBottomMargin' => 'pad'],
        ]);

        return $pdf->render();
    }

    // SHIPMENT PROCESSING - on hold shipment
    public function actionOnholdshipment() {
        $invoice_id = $_POST['invoice_id'];

        Yii::$app->db->createCommand("UPDATE orders SET order_status='on hold' WHERE invoice_id='".$invoice_id."'")->execute();
        return "success";
    }

    // SHIPMENT PROCESSING - delete shipment
    public function actionDeleteshipment() {
        $invoice_id = $_POST['invoice_id'];

        Yii::$app->db->createCommand("UPDATE orders SET order_status='Deleted' WHERE invoice_id='".$invoice_id."'")->execute();
        return "success";
    }

    // SHIPMENT PROCESSING - list out on hold orders
    public function actionOnholdorders() {

        $searchModel = new OrdersSearch();
        $dataProvider = $searchModel->searchordershipment(Yii::$app->request->queryParams, 'onholdorders');
        $paramGet = Yii::$app->request->queryParams;
        unset($paramGet['r']);
        $urlExt = http_build_query($paramGet);

        return $this->render('onholdorders', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'menu_id' => 137, 'urlExt' => $urlExt]);
    }

    // SHIPMENT PROCESSING - release on hold orders -> pending
    public function actionReleaseshipment() {
        $invoice_id = $_POST['invoice_id'];

        Yii::$app->db->createCommand("UPDATE orders SET order_status='pending' WHERE invoice_id='".$invoice_id."'")->execute();
        return "success";
    }

    // SHIPMENT PROCESSING - all under process
    public function actionProcessorders() {

        $searchModel = new OrdersSearch();
        $dataProvider = $searchModel->searchordershipment(Yii::$app->request->queryParams, 'processorders');
        $paramGet = Yii::$app->request->queryParams;
        unset($paramGet['r']);
        $urlExt = http_build_query($paramGet);

        return $this->render('processorders', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'menu_id' => 132, 'urlExt' => $urlExt]);
    }

    public function actionUpdateweight() 
    {
        $invoice_id = $_POST['invoice_id'];
        $weight = $_POST['weight'];
        $date = date('Y-m-d');
        $courier = '';
        $country = '';

        $details = Yii::$app->db->createCommand("SELECT * FROM orders WHERE invoice_id ='".$invoice_id."'")->queryOne();

        if (!empty($details))
        {
            $courier = $details['courier'];
            $country = $details['customer_country_code'];
        }
        
        if ($country == 'US' && $courier != 'DHL Global Mail' && $courier != 'Pick Up')
        {
            if((float)$weight <= 2 || (float)$weight >=4)
            {
                Yii::$app->db->createCommand("UPDATE orders SET order_status='under process',weight='".$weight."',courier='UPS' WHERE invoice_id='".$invoice_id."'")->execute();
            }
            else if ((float)$weight >= 2.5 && (float)$weight <= 3.5)
            {
                Yii::$app->db->createCommand("UPDATE orders SET order_status='under process',weight='".$weight."',courier='DHL Express' WHERE invoice_id='".$invoice_id."'")->execute();
            }
        }
        else
        {
            Yii::$app->db->createCommand("UPDATE orders SET order_status='under process',weight='".$weight."' WHERE invoice_id='".$invoice_id."'")->execute();
        }

        // change status to completed for Pick Up order
        if ($courier == 'Pick Up')
        {
            Yii::$app->db->createCommand("UPDATE orders SET order_status='completed',weight='".$weight."' WHERE invoice_id='".$invoice_id."'")->execute();
        }

        return "success";
    }

    // SHIPMENT PROCESSING - download shipping list by courier
    public function actionDownloadcourierfile() {
        $ParamList = new ParamList();
        $Product = new Product();
        $Brand = new Brand();
        $mode = $_GET['couriermode'];
        $action = 'downloadcourierfile';
        $weight = 0;

        $searchModel = new OrdersSearch();
        $model = $searchModel->searchordershipment(Yii::$app->request->queryParams, $action);
        $productdetails = [];
        $shipmentdetails = [];

        $products = [];
        $product_list = [];

        $Orders = new Orders();
        $empty = '';
        $countershipment = 1;

        header('Content-Type: application/excel');
        header('Content-Disposition: attachment; filename="'.$mode.'.csv"');
        $fp = fopen('php://output', 'w');

        if ($mode == 'DHL Express')
        {
            $headerdata = array('sender_account_num,sender_reference,receiver_company,receiver_attention, receiver_address_1,receiver_address_2,receiver_address_3,receiver_city,receiver_state,receiver_zip,receiver_country_cd,receiver_phone_prefix,receiver_phone,Local_product_cd,shipment_weight,contents1,total_declare_value,awb_currency_cd,shipment_pieces,receiver_email1,No_of_special_services,Charge/Service_code,Charge/Service_Category,PLT_shipment_flag,[Proforma Header]Customs_Invoice_Type,[Proforma Header]Invoice_Number,No_of_Proforma_Line_items,[Proforma]Description,[Proforma]Quantity,[Proforma]Unit_Value,[Proforma]Net_Weight,[Proforma]Gross_Weight,Column29=[Proforma]Commodity_Code,Column30=[Proforma]Manufacture_Country_Code,[Proforma]Description,[Proforma]Quantity,[Proforma]Unit_Value,[Proforma]Net_Weight,[Proforma]Gross_Weight,Column29=[Proforma]Commodity_Code,Column30=[Proforma]Manufacture_Country_Code,[Proforma]Description,[Proforma]Quantity,[Proforma]Unit_Value,[Proforma]Net_Weight,[Proforma]Gross_Weight,Column29=[Proforma]Commodity_Code,Column30=[Proforma]Manufacture_Country_Code,[Proforma]Description,[Proforma]Quantity,[Proforma]Unit_Value,[Proforma]Net_Weight,[Proforma]Gross_Weight,Column29=[Proforma]Commodity_Code,Column30=[Proforma]Manufacture_Country_Code,[Proforma]Description,[Proforma]Quantity,[Proforma]Unit_Value,[Proforma]Net_Weight,[Proforma]Gross_Weight,Column29=[Proforma]Commodity_Code,Column30=[Proforma]Manufacture_Country_Code,[Proforma]Description,[Proforma]Quantity,[Proforma]Unit_Value,[Proforma]Net_Weight,[Proforma]Gross_Weight,Column29=[Proforma]Commodity_Code,Column30=[Proforma]Manufacture_Country_Code,[Proforma]Description,[Proforma]Quantity,[Proforma]Unit_Value,[Proforma]Net_Weight,[Proforma]Gross_Weight,Column29=[Proforma]Commodity_Code,Column30=[Proforma]Manufacture_Country_Code,[Proforma]Description,[Proforma]Quantity,[Proforma]Unit_Value,[Proforma]Net_Weight,[Proforma]Gross_Weight,Column29=[Proforma]Commodity_Code,Column30=[Proforma]Manufacture_Country_Code,[Proforma]Description,[Proforma]Quantity,[Proforma]Unit_Value,[Proforma]Net_Weight,[Proforma]Gross_Weight,Column29=[Proforma]Commodity_Code,Column30=[Proforma]Manufacture_Country_Code,[Proforma]Description,[Proforma]Quantity,[Proforma]Unit_Value,[Proforma]Net_Weight,[Proforma]Gross_Weight,Column29=[Proforma]Commodity_Code,Column30=[Proforma]Manufacture_Country_Code');
            foreach ($headerdata as $line ) {
                $val = explode(",", $line);
                fputcsv($fp, $val);
            }

            if (!empty($model))
            {
                foreach ($model as $row) {
                    if($row->customer_email == "")
                        $row->customer_email = "sandy@netbiz.my";
                    if($row->customer_contact == "")
                        $row->customer_contact = "603-90572070";

                    $shipmentdetails[] = "Default,TR".$row->invoice_id."(".$row->member_name."),".$row->customer_name.",".$row->customer_name.",".str_replace(',', ' ', $row->customer_address1).",".str_replace(',', ' ', $row->customer_address2).",".$empty.",".$row->customer_city.",".$row->customer_state.",".$row->customer_postcode.",".$row->customer_country_code.",".$empty.",".$row->customer_contact.",P,".$row->weight.",".$empty.",".$row->selling_price.",".$row->selling_currency.",1,".$row->customer_email.",1,WY,PLT,1,CI,TR".$row->invoice_id.",".$Orders->gettotalproduct($row->invoice_id).$Orders->getproductsdhl($row->invoice_id);
                }
            }

            foreach ($shipmentdetails as $row)
            {
                $val = explode(",", trim($row));
                fputcsv($fp, $val);
            }
        }
        else if ($mode == 'UPS')
        {
            $headerdata = array('Column1=sender_account_num,Column3=sender_reference
                ,Column15=receiver_company
                ,Column16=receiver_attention
                ,Column17=receiver_address_1
                ,Column18=receiver_address_2
                ,Column19=receiver_address_3
                ,Column20=receiver_city
                ,Column21=receiver_state
                ,Column22=receiver_zip
                ,Column23=receiver_country_cd
                ,Column24=receiver_phone
                ,Column25=Local_product_cd
                ,Column26=shipment_weight
                ,Column27=contents1
                ,Column28=total_declared_value
                ,Column29=awb_currency_cd
                ,Column30=shipment_pieces
                ,Countyoforigin
                ,unitofmeasure');

            foreach ($headerdata as $line ) {
                $val = explode(",", $line);
                fputcsv($fp, $val);
            } 


            if (!empty($model))
            {
                foreach ($model as $row) {

                    if($row->customer_email == "")
                        $row->customer_email = "sandy@netbiz.my";
                    if($row->customer_contact == "")
                        $row->customer_contact = "603-90572070";

                    $shipmentdetails[] = $empty.",TR".$row->invoice_id."(".$row->member_name."),".$row->customer_name.",".$row->customer_name.",".str_replace(',', ' ', $row->customer_address1).",".str_replace(',', ' ', $row->customer_address2).",".$empty.",".$row->customer_city.",".$row->customer_state.",".$row->customer_postcode.",".$row->customer_country_code.",".$row->customer_contact.",P,".$row->weight.",".$Brand->getname($Product->getbrand($row->product_id)).",".$Orders->gettotaldeclarevalue($row->invoice_id).",".$row->selling_currency.",1,MY,Each";
                }
            }

            foreach ($shipmentdetails as $row)
            {
                $val = explode(",", trim($row));
                fputcsv($fp, $val);
            }
        }
        else if ($mode == 'Aramex')
        {
            $headerdata = array('Wgt_Actual,Wgt_Unit,Commodity_Desc,Commodity_Org,Customs_Value,Shpr_Ref,Shpr_Ref2,Remarks,Cons_Name,Cons_AttnOf,Cons_Ref,Cons_Ref2,Cons_Address1,Cons_City,Cons_State,Cons_Country,Cons_PostalCode,Cons_Telephone,Cons_Email');

            foreach ($headerdata as $line ) {
                $val = explode(",", $line);
                fputcsv($fp, $val);
            } 

            if (!empty($model))
            {
                foreach ($model as $row) {

                    if($row->customer_email == "")
                        $row->customer_email = "sandy@netbiz.my";
                    if($row->customer_contact == "")
                        $row->customer_contact = "603-90572070";

                    $shipmentdetails[] = $row->weight.",1,".$Brand->getname($Product->getbrand($row->product_id)).",".$empty.",".$Orders->gettotaldeclarevalue($row->invoice_id).",TR".$row->invoice_id."(".$row->member_name."),TR".$row->invoice_id."(".$row->member_name."),".$empty.",".$row->customer_name.",".$row->customer_name.",TR".$row->invoice_id."(".$row->member_name."),TR".$row->invoice_id."(".$row->member_name."),".str_replace(',', ' ', $row->customer_address1).",".$row->customer_city.",".$row->customer_state.",".$row->customer_country_code.",".$row->customer_postcode.",".$row->customer_contact.",".$row->customer_email;
                }
            }

            foreach ($shipmentdetails as $row)
            {
                $val = explode(",", trim($row));
                fputcsv($fp, $val);
            }
        }
        else if ($mode == 'DHL Global Mail')
        {
            $headerdata = array('Pick-up Account Number,Sales Channel,Shipment Order ID,Tracking Number,Shipping Service Code,Company,Consignee Name,Address Line 1,Address Line 2,Address Line 3,City,State,Postal Code,Destination Country Code,Phone Number,Email Address,Shipment Weight (g),Length (cm),Width (cm),Height (cm),Currency Code,Total Declared Value,Incoterm,Freight,Is Insured,Insurance,Is COD,Cash on Delivery Value,Recipient ID,Recipient ID Type,Duties,Taxes,Workshare Indicator,Shipment Description,Shipment Import Description,Shipment Export Description,Shipment Content Indicator,Content Description,Content Import Description,Content Export Description,Content Unit Price,Content Origin Country,Content Quantity,Content Weight (g),Content Code,HS Code,Content Indicator,Remarks,Shipper Company,Shipper Name,Shipper Address1,Shipper Address2,Shipper Address3,Shipper City,Shipper State,Shipper Postal Code,Shipper CountryCode,Shipper Phone Number,Shipper Email address,Return Shipping Service Code,Return Company,Return Name,Return Address Line 1,Return Address Line 2,Return Address Line 3,Return City,Return State,Return Postal Code,Return Destination Country Code,Return Phone Number,Return Email Address,Service1,Service2,Service3,Service4,Service5,Grouping Reference1,Grouping Reference2,Customer Reference 1,Customer Reference 2,Handover Method,Return Mode,Billing Reference 1,Billing Reference 2,IsMult,Delivery Option,PieceID,Piece Description,Piece Weight,Piece COD,Piece Insurance,Piece Billing Reference 1,Piece Billing Reference 2,Invoice Number,Invoice Date,CGST Amount,SGST Amount,IGST Amount,CESS Amount,IGST Rate %,MEIS,Commodity Under 3C,Discount,Reverse Charge,IGST Payment Status,Terms of Invoice');
            foreach ($headerdata as $line ) {
                $val = explode(",", $line);
                fputcsv($fp, $val);
            }

            if (!empty($model))
            {
                $counter = 1;
                foreach ($model as $row) {
                    if($row->customer_email == "")
                        $row->customer_email = "sandy@netbiz.my";
                    if($row->customer_contact == "")
                        $row->customer_contact = "603-90572070";

                    $weight = ((float)$row->weight * 1000) - 30;

                    $shipmentdetails[] = "5261874634,".$empty.",TR".$row->invoice_id.",".$empty.",PPS,".$empty.",".$row->customer_name.",".str_replace(',', ' ', $row->customer_address1).",".str_replace(',', ' ', $row->customer_address2).",".$empty.",".$row->customer_city.",".$row->customer_state.",".$row->customer_postcode.",".$row->customer_country_code.",".$row->customer_contact.",".$row->customer_email.",".$weight.",".$empty.",".$empty.",".$empty.",".$row->selling_currency.",".$Orders->gettotaldeclarevalue($row->invoice_id).",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$Product->getname($row->product_id).",".$empty.",".$empty.",".$empty.",".$Product->getname($row->product_id).",".$empty.",".$empty.",".$row->product_price.",MY,".$row->quantity.",".$empty.",".$counter.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty.",".$empty;
                    $counter++;
                }
            }

            foreach ($shipmentdetails as $row)
            {
                $val = explode(",", trim($row));
                fputcsv($fp, $val);
            }
        }

        fclose($fp);
        exit();
    }

    // SHIPMENT PROCESSING - bulk update tracking code
    public function actionUpdatetracking() {

        $model = new Orders();
        $Member = new Member();
        $MemberStore = new MemberStore();
        $paramGet = Yii::$app->request->queryParams;
        unset($paramGet['r']);
        $urlExt = http_build_query($paramGet);

        if (Yii::$app->request->post()) 
        {
            $date = date('Y-m-d');
            $model->file = UploadedFile::getInstance($model, 'file');
            $table = [];
            $arrayTR = [];
            $arrayS = [];
            $countline = 1;
            if ($model->file->extension == 'csv')
            {
                $fp = fopen($model->file->tempName,'r');
                if($fp){
                    while($line = fgetcsv($fp)){
                        $table[$countline]['tracking'] = $line[0];
                        $table[$countline]['invoice'] = preg_replace('/[^0-9]/', '', $line[1]);
                        if (substr($line[1], 0, 2) == 'TR')
                            $table[$countline]['type'] = substr($line[1], 0, 2);
                        else
                            $table[$countline]['type'] = 'S';
                        $countline++;
                    }
                }
                // UPDATE tracking number, status = completed, date = today
                foreach ($table as $key) {
                    if (!empty($key['invoice']))
                    {
                        if ($key['type'] == 'TR')
                        {
                            Yii::$app->db->createCommand("UPDATE orders SET order_status='completed',order_date='".$date."',tracking_code='".$key['tracking']."' WHERE invoice_id='".$key['invoice']."' AND order_status!='completed'")->execute();
                            $arrayTR[] = $key['invoice'];
                        }
                        else
                        {
                            if ($key['invoice'] != 1)
                            {
                                Yii::$app->db->createCommand("UPDATE orders_own SET order_status='completed',order_date='".$date."',tracking_code='".$key['tracking']."' WHERE invoice_id='".$key['invoice']."' AND order_status!='completed'")->execute();
                                $arrayS[] = $key['invoice'];
                            }
                            
                        }
                    }
                }

                // UPDATE COMPANY SHIPPING FEES
                $modelorder = Orders::find()->where(['IN', 'invoice_id', $arrayTR])->andWhere(['=', 'order_status', 'completed'])->groupBy(['invoice_id'])->all();
                if (!empty($modelorder)) {
                    foreach ($modelorder as $row) {
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
                        $trackingURL = '';

                        // GET ORDERS DATA (date,courier,country,weight)
                        $invoice_id = $row->invoice_id;
                        $order_date = $row->order_date;
                        $courier = $row->courier;
                        $country = $row->customer_country_code;
                        $customer_country_name = $row->customer_country_name;
                        $state = $row->customer_state;
                        $weight = $row->weight;
                        $currentshippingfee = $row->shipping_fee;
                        $uid = $row->member_id;

                         // for email
                        $member_name = $row->member_name;
                        $member_email = $Member->getmemberemail($uid);
                        $member_storename = $MemberStore->getStoreName($row->member_store_id);
                        $customer_name = $row->customer_name;
                        $customer_email = $row->customer_email;
                        $tracking_code = $row->tracking_code;
                        $listing_title = $row->listing_title;
                        $listing_url = $row->listing_url;

                        // GET SHIPPING RATE
                        if ($courier == 'Aramex' || $courier == 'DHL Express' || $courier == 'DHL Global Mail' || $courier == 'Fedex' || $courier == 'UPS')
                        {
                            if ($weight <= 20)
                            {
                                if ($weight > 0.54 && $courier == 'DHL Global Mail')
                                {
                                    // echo "Skip process ".$invoice_id . " (DHL eCommerce > 0.54kg)<br>";
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
                                }
                            }
                        }
                        else if ($courier == 'Pos Laju')
                        {
                            if ($country == 'MY') 
                            {
                                if ($state == 'Sabah') {
                                    if((float)$weight <= 0.5)
                                        $newshippingfee = 10.89;
                                    else if((float)$weight > 0.5 && (float)$weight <= 0.75 )
                                        $newshippingfee = 13.70;
                                    else if((float)$weight > 0.75 && (float)$weight <= 1.00 )
                                        $newshippingfee = 16.51;
                                    else if((float)$weight > 1.00 && (float)$weight <= 1.25 )
                                        $newshippingfee = 19.85;
                                    else if((float)$weight > 1.25 && (float)$weight <= 1.50 )
                                        $newshippingfee = 22.66;
                                    else if((float)$weight > 1.55 && (float)$weight <= 1.75 )
                                        $newshippingfee = 25.47;
                                    else if((float)$weight > 1.75 && (float)$weight <= 2.00 )
                                        $newshippingfee = 28.28;
                                }
                                else if($state == 'Sarawak') {
                                    if((float)$weight <= 0.5)
                                        $newshippingfee = 10.19;

                                    else if((float)$weight > 0.5 && (float)$weight <= 0.75 )
                                        $newshippingfee = 12.30;

                                    else if((float)$weight > 0.75 && (float)$weight <= 1.00 )
                                        $newshippingfee = 14.40;

                                    else if((float)$weight > 1.00 && (float)$weight <= 1.25 )
                                        $newshippingfee = 17.04;

                                    else if((float)$weight > 1.25 && (float)$weight <= 1.50 )
                                        $newshippingfee = 19.15;

                                    else if((float)$weight > 1.55 && (float)$weight <= 1.75 )
                                        $newshippingfee = 21.25;

                                    else if((float)$weight > 1.75 && (float)$weight <= 2.00 )
                                        $newshippingfee = 23.36;
                                }
                                else {
                                    if((float)$weight <= 0.5)
                                        $newshippingfee = 7.38;
                                    else if((float)$weight > 0.5 && (float)$weight <= 0.75 )
                                        $newshippingfee = 8.78;
                                    else if((float)$weight > 0.75 && (float)$weight <= 1.00 )
                                        $newshippingfee = 10.19;
                                    else if((float)$weight > 1.00 && (float)$weight <= 1.25 )
                                        $newshippingfee = 12.12;
                                    else if((float)$weight > 1.25 && (float)$weight <= 1.50 )
                                        $newshippingfee = 13.53;
                                    else if((float)$weight > 1.55 && (float)$weight <= 1.75 )
                                        $newshippingfee = 14.93;
                                    else if((float)$weight > 1.75 && (float)$weight <= 2.00 )
                                        $newshippingfee = 16.34;
                                }
                            }
                        }
                        else if ($courier == 'KTMD')
                        {
                            if ($country == 'MY') 
                            {
                                if ($weight >= 0.5 && $weight <= 1)
                                    $newshippingfee = 7.50;
                                else if ($weight >= 1.5 && $weight <= 2)
                                    $newshippingfee = 13.55;
                                else if ($weight >= 2.5 && $weight <= 3)
                                    $newshippingfee = 27.95;
                                else if ($weight >= 3.5 && $weight <= 4)
                                    $newshippingfee = 33.55;
                                else if ($weight >= 4.5 && $weight < 11)
                                    $newshippingfee = 50.00; //dummy data
                                else if ($weight == 11)
                                    $newshippingfee = 75.75;
                                else
                                    $newshippingfee = 90.00; //dummy data
                            }
                        }

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
                        // $ch = curl_init();
                        // curl_setopt($ch, CURLOPT_URL, Yii::$app->params['apiUpdateNewShippingValues']);
                        // curl_setopt($ch, CURLOPT_POST, 1);
                        // curl_setopt($ch, CURLOPT_POSTFIELDS, "id=" . $invoice_id . "&newfee=" . $newshippingfee. "&newcost=" . $newshippingcost);
                        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        // $data = curl_exec($ch);
                        // curl_close($ch);
                        // $dataArr = json_decode($data);
                        // if (!empty($dataArr)) {
                        //     if ($dataArr->status == 'success')
                        //         $msg = 'success';
                        //     else
                        //         $msg = 'failed';
                        // }
                        // echo $invoice_id." - ".$newshippingfee." / ".$newshippingcost." -->done<br>";
                        // ***********************

                        //TRACKING URL by courier
                        if ($courier == 'DHL Express')
                            $trackingURL = '<br>Track your order <a href="https://www.dhl.com/en.html" target="_blank">HERE</a>';
                        else if ($courier == 'UPS')
                            $trackingURL = '<br>Track your order <a href="https://www.ups.com/tracking/tracking.html" target="_blank">HERE</a>';
                        else if ($courier == 'DHL Global Mail')
                            $trackingURL = '<br>Track your order <a href="https://dhlecommerce.asia/Portal/Track" target="_blank">HERE</a>';
                        else if ($courier == 'Aramex')
                            $trackingURL = '<br>Track your order <a href="https://www.aramex.com/us/en/track/shipments" target="_blank">HERE</a>';
                        else if ($courier == 'Pos Laju')
                            $trackingURL = '<br>Track your order <a href="https://www.poslaju.com.my/track-trace" target="_blank">HERE</a>';

                        //SEND EMAIL TO MEMBER 
                        $email_content = '<html>
                        <body>
                        <div style="font-family: \'Century Gothic\', CenturyGothic, AppleGothic, sans-serif; font-size: 14px;background-color:rgb(234,234,234);padding:2em 0;">
                        <div style="width:70%;background-color:white;margin:0 auto;padding:0em 5em 5em 5em;border:1px solid #ccc;">
                        <p><center><img src="' . Url::base(true) . '/images/netbizlogo.png"></center>Hi ' . $member_name . ',</p>
                        <p>We have finished processing your order.</p>
                        <p>Your order was shipped via <b>'.$courier.'</b><br>
                        Tracking number is <b>'.$tracking_code.'</b>
                        '.$trackingURL.'</p>

                        <p><b><u>Order Details:-</u></b><br>
                        Store: '.$member_storename.'<br>
                        Invoice Number: TR'.$invoice_id.'<br>
                        Shipment Date: '.date('d M Y',strtotime($order_date)).'<br>
                        Listing Title: <a href="'.$listing_url.'">'.$listing_title.'</a></p>
                        
                        <p><b><u>Recipient Details:-</u></b><br>
                        '.$customer_name.'<br>
                        '.$customer_country_name.'<br>
                        '.$customer_email.'</p>
                        <br>
                        <p>Thank you for using our service.</p>
                        <p>Regards,<br>
                        Netbiz Internet Platform Sdn. Bhd.</p>
                        </div>
                        </div>
                        </body>
                        </html>';

                        $email_content2 = '<html>
                        <body>
                        <div style="font-family: \'Century Gothic\', CenturyGothic, AppleGothic, sans-serif; font-size: 14px;background-color:rgb(234,234,234);padding:2em 0;">
                        <div style="width:70%;background-color:white;margin:0 auto;padding:0em 5em 5em 5em;border:1px solid #ccc;">
                        <p><center><img src="' . Url::base(true) . '/images/netbizlogo.png"></center>Hi ' . $customer_name . ',</p>
                        <p>We have finished processing your order.</p>
                        <p>Your order was shipped via <b>'.$courier.'</b><br>
                        Tracking number is <b>'.$tracking_code.'</b>
                        '.$trackingURL.'</p>

                        <p><b><u>Order Details:-</u></b><br>
                        Invoice Number: TR'.$invoice_id.'<br>
                        Shipment Date: '.date('d M Y',strtotime($order_date)).'<br>
                        Order Item: <a href="'.$listing_url.'">'.$listing_title.'</a>
                        </p>
                        
                        <p><b><u>Recipient Details:-</u></b><br>
                        '.$customer_name.'<br>
                        '.$customer_country_name.'<br>
                        '.$customer_email.'</p>
                        <br><p>Thank you for using our service.</p>
                        <p>Regards,<br>
                        Netbiz Internet Platform Sdn. Bhd.</p>
                        </div>
                        </div>
                        </body>
                        </html>';

                        // email to reseller/member
                        if (Yii::$app->mailer->compose()
                            ->setFrom(Yii::$app->params['senderEmail'])
                            ->setTo($member_email)
                            ->setBcc(Yii::$app->params['bccEmail'])
                            ->setSubject('Airway Bill ('.$tracking_code.')')
                            ->setHtmlBody($email_content)
                            ->send())
                        {
                            // email to customer
                            if ($customer_email != '')
                            {
                                Yii::$app->mailer->compose()
                                ->setFrom(Yii::$app->params['senderEmail'])
                                ->setTo($customer_email)
                                ->setBcc(Yii::$app->params['bccEmail'])
                                ->setSubject('Airway Bill ('.$tracking_code.')')
                                ->setHtmlBody($email_content2)
                                ->send();
                            }    
                        }
                    }
                }
                
                // UPDATE OWN SHIPPING FEES
                $modelown = OrdersOwn::find()->where(['IN', 'invoice_id', $arrayS])->andWhere(['=', 'order_status', 'completed'])->groupBy(['invoice_id'])->all();
                if (!empty($modelown)) {
                    foreach ($modelown as $row) {
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
                        $trackingURL = '';

                        // GET ORDERS DATA (date,courier,country,weight)
                        $invoice_id = $row->invoice_id;
                        $order_date = $row->order_date;
                        $courier = $row->courier;
                        $country = $row->customer_country_code;
                        $country_name = $row->customer_country_name;
                        $weight = $row->weight;
                        $currentshippingfee = $row->shipping_fee;
                        $uid = $row->member_id;

                        // for email
                        $member_name = $row->member_name;
                        $member_email = $Member->getmemberemail($uid);
                        $customer_name = $row->customer_name;
                        $customer_email = $row->customer_email;
                        $tracking_code = $row->tracking_code;

                        // GET SHIPPING RATE
                        if ($courier == 'Aramex' || $courier == 'DHL Express' || $courier == 'DHL Global Mail' || $courier == 'Fedex' || $courier == 'UPS')
                        {
                            if ($weight <= 20)
                            {
                                if ($weight > 0.54 && $courier == 'DHL Global Mail')
                                {
                                    // echo "Skip process ".$invoice_id . " (DHL eCommerce > 0.54kg)<br>";
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

                                        if ($uid == '368' || $uid == '415')
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
                                            if ($uid == '368' || $uid == '415')
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

                                    // CALCULATE TOTAL COST
                                    $total_cost = $basefsc + $psc;
                                    $newshippingcost = round($total_cost * 2, 1) / 2;

                                    // CALCULATE TOTAL SHIPPING
                                    $totalshippingfee = $totalwithfsc + $psc;
                                    $newshippingfee = round($totalshippingfee * 2, 1) / 2;
                                }
                            }
                        }
                        else if ($courier == 'Pos Laju')
                        {
                            if ($country == 'MY') 
                            {
                                if ($state == 'Sabah') {
                                    if((float)$weight <= 0.5)
                                        $newshippingfee = 10.89;
                                    else if((float)$weight > 0.5 && (float)$weight <= 0.75 )
                                        $newshippingfee = 13.70;
                                    else if((float)$weight > 0.75 && (float)$weight <= 1.00 )
                                        $newshippingfee = 16.51;
                                    else if((float)$weight > 1.00 && (float)$weight <= 1.25 )
                                        $newshippingfee = 19.85;
                                    else if((float)$weight > 1.25 && (float)$weight <= 1.50 )
                                        $newshippingfee = 22.66;
                                    else if((float)$weight > 1.55 && (float)$weight <= 1.75 )
                                        $newshippingfee = 25.47;
                                    else if((float)$weight > 1.75 && (float)$weight <= 2.00 )
                                        $newshippingfee = 28.28;
                                }
                                else if($state == 'Sarawak') {
                                    if((float)$weight <= 0.5)
                                        $newshippingfee = 10.19;

                                    else if((float)$weight > 0.5 && (float)$weight <= 0.75 )
                                        $newshippingfee = 12.30;

                                    else if((float)$weight > 0.75 && (float)$weight <= 1.00 )
                                        $newshippingfee = 14.40;

                                    else if((float)$weight > 1.00 && (float)$weight <= 1.25 )
                                        $newshippingfee = 17.04;

                                    else if((float)$weight > 1.25 && (float)$weight <= 1.50 )
                                        $newshippingfee = 19.15;

                                    else if((float)$weight > 1.55 && (float)$weight <= 1.75 )
                                        $newshippingfee = 21.25;

                                    else if((float)$weight > 1.75 && (float)$weight <= 2.00 )
                                        $newshippingfee = 23.36;
                                }
                                else {
                                    if((float)$weight <= 0.5)
                                        $newshippingfee = 7.38;
                                    else if((float)$weight > 0.5 && (float)$weight <= 0.75 )
                                        $newshippingfee = 8.78;
                                    else if((float)$weight > 0.75 && (float)$weight <= 1.00 )
                                        $newshippingfee = 10.19;
                                    else if((float)$weight > 1.00 && (float)$weight <= 1.25 )
                                        $newshippingfee = 12.12;
                                    else if((float)$weight > 1.25 && (float)$weight <= 1.50 )
                                        $newshippingfee = 13.53;
                                    else if((float)$weight > 1.55 && (float)$weight <= 1.75 )
                                        $newshippingfee = 14.93;
                                    else if((float)$weight > 1.75 && (float)$weight <= 2.00 )
                                        $newshippingfee = 16.34;
                                }
                            }
                        }
                        else if ($courier == 'KTMD')
                        {
                            if ($country == 'MY') 
                            {
                                if ($weight >= 0.5 && $weight <= 1)
                                    $newshippingfee = 7.50;
                                else if ($weight >= 1.5 && $weight <= 2)
                                    $newshippingfee = 13.55;
                                else if ($weight >= 2.5 && $weight <= 3)
                                    $newshippingfee = 27.95;
                                else if ($weight >= 3.5 && $weight <= 4)
                                    $newshippingfee = 33.55;
                                else if ($weight >= 4.5 && $weight < 11)
                                    $newshippingfee = 50.00; //dummy data
                                else if ($weight == 11)
                                    $newshippingfee = 75.75;
                                else
                                    $newshippingfee = 90.00; //dummy data
                            }
                        }

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

                        // // UPDATE TO MEMBERV2 NEW FIELDS
                        // $ch = curl_init();
                        // curl_setopt($ch, CURLOPT_URL, Yii::$app->params['apiUpdateNewShippingValuesOwn']);
                        // curl_setopt($ch, CURLOPT_POST, 1);
                        // curl_setopt($ch, CURLOPT_POSTFIELDS, "id=" . $invoice_id . "&newfee=" . $newshippingfee. "&newcost=" . $newshippingcost);
                        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        // $data = curl_exec($ch);
                        // curl_close($ch);

                        // $dataArr = json_decode($data);
                        // if (!empty($dataArr)) {
                        //     if ($dataArr->status == 'success')
                        //         $msg = 'success';
                        //     else
                        //         $msg = 'failed';
                        // }

                        // echo $invoice_id." - ".$newshippingfee." / ".$newshippingcost." -->done<br>";

                        // ***********************

                        //SEND EMAIL TO MEMBER 

                        //TRACKING URL by courier
                        if ($courier == 'DHL Express')
                            $trackingURL = '<br>Track your order <a href="https://www.dhl.com/en.html" target="_blank">HERE</a>';
                        else if ($courier == 'UPS')
                            $trackingURL = '<br>Track your order <a href="https://www.ups.com/tracking/tracking.html" target="_blank">HERE</a>';
                        else if ($courier == 'DHL Global Mail')
                            $trackingURL = '<br>Track your order <a href="https://dhlecommerce.asia/Portal/Track" target="_blank">HERE</a>';
                        else if ($courier == 'Aramex')
                            $trackingURL = '<br>Track your order <a href="https://www.aramex.com/us/en/track/shipments" target="_blank">HERE</a>';
                        else if ($courier == 'Pos Laju')
                            $trackingURL = '<br>Track your order <a href="https://www.poslaju.com.my/track-trace" target="_blank">HERE</a>';

                        $email_content = '<html>
                        <body>
                        <div style="font-family: \'Century Gothic\', CenturyGothic, AppleGothic, sans-serif; font-size: 14px;background-color:rgb(234,234,234);padding:2em 0;">
                        <div style="width:70%;background-color:white;margin:0 auto;padding:0em 5em 5em 5em;border:1px solid #ccc;">
                        <p><center><img src="' . Url::base(true) . '/images/netbizlogo.png"></center>Hi ' . $member_name . ',</p>
                        <p>We have finished processing your order.</p>
                        <p>Your order was shipped via <b>'.$courier.'</b><br>
                        Tracking number is <b>'.$tracking_code.'</b>
                        '.$trackingURL.'</p>

                        <p><b><u>Order Details:-</u></b><br>
                        Invoice Number: S'.$invoice_id.'<br>
                        Shipment Date: '.date('d M Y',strtotime($order_date)).'</p>
                        
                        <p><b><u>Recipient Details:-</u></b><br>
                        '.$customer_name.'<br>
                        '.$customer_country_name.'<br>
                        '.$customer_email.'</p>
                        <br><p>Thank you for using our service.</p>
                        <p>Regards,<br>
                        Netbiz Internet Platform Sdn. Bhd.</p>
                        </div>
                        </div>
                        </body>
                        </html>';

                        $email_content2 = '<html>
                        <body>
                        <div style="font-family: \'Century Gothic\', CenturyGothic, AppleGothic, sans-serif; font-size: 14px;background-color:rgb(234,234,234);padding:2em 0;">
                        <div style="width:70%;background-color:white;margin:0 auto;padding:0em 5em 5em 5em;border:1px solid #ccc;">
                        <p><center><img src="' . Url::base(true) . '/images/netbizlogo.png"></center>Hi ' . $customer_name . ',</p>
                        <p>We have finished processing your order.</p>
                        <p>Your order was shipped via <b>'.$courier.'</b><br>
                        Tracking number is <b>'.$tracking_code.'</b>
                        '.$trackingURL.'</p>
                        
                        <p><b><u>Order Details:-</u></b><br>
                        Invoice Number: S'.$invoice_id.'<br>
                        Shipment Date: '.date('d M Y',strtotime($order_date)).'</p>
                        
                        <p><b><u>Recipient Details:-</u></b><br>
                        '.$customer_name.'<br>
                        '.$customer_country_name.'<br>
                        '.$customer_email.'</p>
                        <br><p>Thank you for using our service.</p>
                        <p>Regards,<br>
                        Netbiz Internet Platform Sdn. Bhd.</p>
                        </div>
                        </div>
                        </body>
                        </html>';

                        // email to reseller/member
                        if (Yii::$app->mailer->compose()
                            ->setFrom(Yii::$app->params['senderEmail'])
                            ->setTo($member_email)
                            ->setBcc(Yii::$app->params['bccEmail'])
                            ->setSubject('Airway Bill ('.$tracking_code.')')
                            ->setHtmlBody($email_content)
                            ->send())
                        {
                            // email to customer
                            if ($customer_email != '')
                            {
                                Yii::$app->mailer->compose()
                                ->setFrom(Yii::$app->params['senderEmail'])
                                ->setTo($customer_email)
                                ->setBcc(Yii::$app->params['bccEmail'])
                                ->setSubject('Airway Bill ('.$tracking_code.')')
                                ->setHtmlBody($email_content2)
                                ->send();
                            }    
                        }
                    }
                }
                
                $type = 'success';
                $title = "Tracking code updated and email has been sent to member";
                $url = Url::toRoute('orders/completeorders');
            }
            else
            {
                $type = 'error';
                $title = 'Please upload only .csv file!';
                $url = Url::toRoute('orders/updatetracking');
            }

            $this->getView()->registerJs("swal.fire({
                position: 'center-center',type: '".$type."',title: '".$title."',showConfirmButton: false,timer: 3500,
            }).then(function(result){window.location = '" . $url . "';});");
        }

        return $this->render('updatetracking', ['model' => $model, 'menu_id' => 132, 'urlExt' => $urlExt]);
    }

    // SHIPMENT PROCESSING - all completed
    public function actionCompleteorders() {

        $searchModel = new OrdersSearch();
        $dataProvider = $searchModel->searchordershipment(Yii::$app->request->queryParams, 'completeorders');
        $paramGet = Yii::$app->request->queryParams;
        unset($paramGet['r']);
        $urlExt = http_build_query($paramGet);

        return $this->render('completeorders', ['searchModel' => $searchModel, 'dataProvider' => $dataProvider, 'menu_id' => 133, 'urlExt' => $urlExt]);
    }

}