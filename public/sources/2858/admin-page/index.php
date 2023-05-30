<?php
session_start();
require("../config.php");

if (isset($_SESSION['user'])) {
  $sess_username = $_SESSION['user']['username'];
  $check_user = mysqli_query($db, "SELECT * FROM users WHERE username = '$sess_username'");
  $data_user = mysqli_fetch_assoc($check_user);
  
  if (mysqli_num_rows($check_user) == 0) {
		header("Location: ".$cfg_baseurl."logout.php");
	} else if ($data_user['status'] == "Suspended") {
		header("Location: ".$cfg_baseurl."logout.php");
  } else if ($data_user['level'] != "Developers") {
    header("Location: ".$cfg_baseurl."");
  } 
  
    // Data order PPOB //
	$check_orderss = mysqli_query($db, "SELECT SUM(price) AS total FROM ordersmobile WHERE provider = 'DF'");
	$data_orderss = mysqli_fetch_assoc($check_orderss);
	$count_orderss = mysqli_query($db, "SELECT * FROM ordersmobile WHERE provider = 'DF'");
	
	$check_order_sc = mysqli_query($db, "SELECT SUM(price) AS total FROM ordersmobile WHERE status = 'Complated' AND provider = 'DF'");
	$data_orderss_sc = mysqli_fetch_assoc($check_order_sc);
	$count_orderss_sc = mysqli_query($db, "SELECT * FROM ordersmobile WHERE status = 'Complated' AND provider = 'DF'");
	
	$check_order_pr = mysqli_query($db, "SELECT SUM(price) AS total FROM ordersmobile WHERE status = 'Processing' AND provider = 'DF'");
	$data_orderss_pr = mysqli_fetch_assoc($check_order_pr);
	$count_orderss_pr = mysqli_query($db, "SELECT * FROM ordersmobile WHERE status = 'Processing' AND provider = 'DF'");
	
	$check_order_er = mysqli_query($db, "SELECT SUM(price) AS total FROM ordersmobile WHERE status = 'Error' AND provider = 'DF'");
	$data_orderss_er = mysqli_fetch_assoc($check_order_er);
	$count_orderss_er = mysqli_num_rows(mysqli_query($db, "SELECT * FROM ordersmobile WHERE status = 'Error' AND provider = 'DF'"));


    // Data order social media //
	$check_orders = mysqli_query($db, "SELECT SUM(price) AS total FROM orderssosmed WHERE provider = 'WSTORE'");
	$data_orders = mysqli_fetch_assoc($check_orders);
	$count_orders = mysqli_query($db, "SELECT * FROM orderssosmed WHERE provider = 'WSTORE'");
	
	$check_order_sc = mysqli_query($db, "SELECT SUM(price) AS total FROM orderssosmed WHERE status = 'Success' AND provider = 'WSTORE'");
	$data_orders_sc = mysqli_fetch_assoc($check_order_sc);
	$count_orders_sc = mysqli_query($db, "SELECT * FROM orderssosmed WHERE status = 'Success' AND provider = 'WSTORE'");
	
	$check_order_pn = mysqli_query($db, "SELECT SUM(price) AS total FROM orderssosmed WHERE status = 'Pending' AND provider = 'WSTORE'");
	$data_orders_pn = mysqli_fetch_assoc($check_order_pn);
	$count_orders_pn = mysqli_query($db, "SELECT * FROM orderssosmed WHERE status = 'Pending' AND provider = 'WSTORE'");
	
	$check_order_pr = mysqli_query($db, "SELECT SUM(price) AS total FROM orderssosmed WHERE status = 'Processing' AND provider = 'WSTORE'");
	$data_orders_pr = mysqli_fetch_assoc($check_order_pr);
	$count_orders_pr = mysqli_query($db, "SELECT * FROM orderssosmed WHERE status = 'Processing' AND provider = 'WSTORE'");
	
	$check_order_er = mysqli_query($db, "SELECT SUM(price) AS total FROM orderssosmed WHERE status = 'Error' AND provider = 'WSTORE'");
	$data_orders_er = mysqli_fetch_assoc($check_order_er);
	$count_orders_er = mysqli_num_rows(mysqli_query($db, "SELECT * FROM orderssosmed WHERE status = 'Error' AND provider = 'WSTORE'"));

	$check_order_par = mysqli_query($db, "SELECT SUM(price) AS total FROM orderssosmed WHERE status = 'Partial' AND provider = 'WSTORE'");
	$data_orders_par = mysqli_fetch_assoc($check_order_par);
	$count_orders_par = mysqli_num_rows(mysqli_query($db, "SELECT * FROM orderssosmed WHERE status = 'Partial' AND provider = 'WSTORE'"));
	
	$data_orders_erpar = $data_orders_er['total']+$data_orders_par['total'];
	$count_orders_erpar = $count_orders_er+$count_orders_par;
    
    // Data deposit bank//
    $check_balance_depo = mysqli_query($db, "SELECT SUM(balance) AS total FROM history_topup");
	$data_balance_depo = mysqli_fetch_assoc($check_balance_depo);
	$count_balance_depo = mysqli_query($db, "SELECT * FROM history_topup");
	
	// Data deposit bank//
	$depo_banksc = mysqli_query($db, "SELECT SUM(balance) AS total FROM history_topup WHERE status = 'Success'");
	$data_banksc = mysqli_fetch_assoc($depo_banksc);
	$count_banksc = mysqli_query($db, "SELECT * FROM history_topup WHERE status = 'Success' AND provider = 'DEPO BANK'");
	
	 $depo_bankpn= mysqli_query($db, "SELECT SUM(balance) AS total FROM history_topup WHERE status = 'Pending'");
	$data_bankpn = mysqli_fetch_assoc($depo_bankpn);
	$count_bankpn = mysqli_query($db, "SELECT * FROM history_topup WHERE status = 'Pending'");

	$depo_banker = mysqli_query($db, "SELECT SUM(balance) AS total FROM deposits WHERE status = 'Error'");
	$data_banker = mysqli_fetch_assoc($depo_banker);
	$count_banker = mysqli_query($db, "SELECT * FROM deposits WHERE status = 'Error'");
	
	
    // Data User //
    $check_wuser = mysqli_query($db, "SELECT * FROM users");
	$count_wuser = mysqli_num_rows($check_wuser);
	
	
	$balance = mysqli_query($db, "SELECT SUM(balance) AS total FROM users");
    $data_balance_mobile = mysqli_fetch_assoc($balance);
    
	$point = mysqli_query($db, "SELECT SUM(point) AS total FROM users WHERE level ='Member'");
	$data_point = mysqli_fetch_assoc($point);
	
	
	$total_balance =  $data_balance['total'] - $data_user['balance'];
	$total_point =  $data_point['total'] - $data_user['point'];
	// End Data //
	
	$balance_used = mysqli_query($db, "SELECT SUM(balance_used) AS total FROM users");
	$data_used = mysqli_fetch_assoc($balance_used);
	//---------------//
	
	// Data Admin //
	$ticket = mysqli_query($db, "SELECT * FROM tickets");
	$count_ticket = mysqli_num_rows($ticket);
	$project = mysqli_query($db, "SELECT * FROM project");
	$count_project = mysqli_num_rows($project);
	$service = mysqli_query($db, "SELECT * FROM services");
	$count_service = mysqli_num_rows($service);
	// End Data //

include ("../lib/admin/index.php");
?>
        <?php eval("?>".base64_decode("PD9waHAgDQogICAgICAgICAgICAkc2lnbiA9IG1kNSgiamV0ZXd1OGdLOTVEIi4iMmM2MWI4ZGEtNGJjZi01OWM3LWJkMGEtYzNlMWFlODM2MTUwIi4iZGVwbyIpOw0KICAgIAkgIC8vJHBvc3RkYXRhID0gInVzZXJuYW1lPSRwX2lkJmJ1eWVyX3NrdV9jb2RlPSRwaWQmY3VzdG9tZXJfbm89JHBob25lJnJlZl9pZD0kb19wb2lkJnNpZ249JHNpZ24iOw0KICAgICAgICAgICAgJHBvc3RkYXRhID0gYXJyYXkoImNtZCIgPT4gJ2RlcG9zaXQnLCJ1c2VybmFtZSIgPT4gJ2pldGV3dThnSzk1RCcsInNpZ24iID0+ICRzaWduKTsNCiAgICANCiAgICAgICAgICAgICRkYXRhX3N0cmluZyA9IGpzb25fZW5jb2RlKCRwb3N0ZGF0YSk7DQoNCiAgICAgICAgICAgICRjaCA9IGN1cmxfaW5pdCgiaHR0cHM6Ly9hcGkuZGlnaWZsYXp6LmNvbS92MS9jZWstc2FsZG8iKTsgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgDQogICAgICAgICAgICBjdXJsX3NldG9wdCgkY2gsIENVUkxPUFRfQ1VTVE9NUkVRVUVTVCwgIlBPU1QiKTsgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICANCiAgICAgICAgICAgIGN1cmxfc2V0b3B0KCRjaCwgQ1VSTE9QVF9QT1NURklFTERTLCAkZGF0YV9zdHJpbmcpOyAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIA0KICAgICAgICAgICAgY3VybF9zZXRvcHQoJGNoLCBDVVJMT1BUX1JFVFVSTlRSQU5TRkVSLCB0cnVlKTsgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgDQogICAgICAgICAgICBjdXJsX3NldG9wdCgkY2gsIENVUkxPUFRfSFRUUEhFQURFUiwgYXJyYXkoICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICANCiAgICAgICAgICAgICAgICAnQ29udGVudC1UeXBlOiBhcHBsaWNhdGlvbi9qc29uJywgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgIA0KICAgICAgICAgICAgICAgICdDb250ZW50LUxlbmd0aDogJyAuIHN0cmxlbigkZGF0YV9zdHJpbmcpKSAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgDQogICAgICAgICAgICApOyAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICAgICANCiAgICAgICAgICAgICRjaHJlc3VsdCA9IGN1cmxfZXhlYygkY2gpOw0KICAgICAgICAgICAgY3VybF9jbG9zZSgkY2gpOw0KICAgICAgICAgICAgJGpzb25fcmVzdWx0ID0ganNvbl9kZWNvZGUoJGNocmVzdWx0LCB0cnVlKTsNCiAgICANCiAgICAgICAgICAgICRzaXNhc2FsZG8gPSAkanNvbl9yZXN1bHRbJ2RhdGEnXVsnZGVwb3NpdCddOw0KICAgICAgICAgICAgPz4=")); ?>
        <div class="row">
            <div class="col-lg-6">
                <div class="card-box tilebox-one">
                    <i class="mdi mdi-cart float-right"></i>
                        <h6 class="text-muted text-uppercase mb-3">Pesanan Sosial Media</h6>
                        <h4 class="mb-3"><?php echo number_format(mysqli_num_rows($count_orders),0,',','.'); ?> Pesanan</h4>
                        <span class="vertical-middle text-uppercase">dengan total harga Rp. <?php echo number_format($data_orders['total'],0,',','.'); ?>,-</span>
                        </div>
                    </div>
        <div class="col-lg-6">
            <div class="card-box tilebox-one">
                <i class="fa fa-money float-right"></i>
                    <h6 class="text-muted text-uppercase mb-3">Total Deposit</h6>
                    <h4 class="mb-3"><?php echo number_format(mysqli_num_rows($count_balance_depo),0,',','.'); ?> Deposit</h4>
                    <span class="vertical-middle text-uppercase">dengan total hargaRp. <?php echo number_format($data_balance_depo['total'],0,',','.'); ?>,-</span>
                    </div>
                </div>
        <div class="col-lg-6">
            <div class="card-box tilebox-one">
                <i class="fa fa-users float-right"></i>
                    <h6 class="text-muted text-uppercase mb-3">Pesanan Mobile</h6>
                    <h4 class="mb-3"><?php echo number_format(mysqli_num_rows($count_orderss),0,',','.'); ?>  Pesanan</h4>
                    <span class="vertical-middle text-uppercase">dengan total harga Rp. <?php echo number_format($data_orderss['total'],0,',','.'); ?>,-</span>
                    </div>
                </div>
        <div class="col-lg-6">
            <div class="card-box tilebox-one">
                <i class="fa fa-money float-right"></i>
                    <h6 class="text-muted text-uppercase mb-3">Total Saldo Pengguna</h6>
                    <h4 class="mb-3"><?php echo $count_wuser; ?> Pengguna</h4>
                    <span class="vertical-middle text-uppercase">Rp <?php echo number_format($data_balance_mobile['total'],0,',','.'); ?> Saldo Pengguna</span>
                    </div>
                </div>
            </div>
                         
<?php
include("../lib/footer.php");
} else {
    header("Location: ".$cfg_baseurl);
}
?>