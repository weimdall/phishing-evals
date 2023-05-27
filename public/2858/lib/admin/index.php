<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title><?php echo $cfg_webname; ?></title>
<meta name="keywords" content="Distributor & H2H Pulsa Termurah, Server Pulsa dan Token PLN Terlengkap" />
<meta name="description" content="<?php echo $data_website['deskripsi']; ?>">
<meta name="author" content="<?php echo $data_website['keyword']; ?>">
<meta name="keywords" content="panel murah,smm panel,panel indo,indo panel,all sosmed, instagram likes,smm panel indp,panel termurah dan cepat,panel instant,best panel,smm resseler panel,instagram like,cara memperbanyak likes,kebutuhan sosial media">
<meta name="keywords" content="best panel,resseler smm panel,panelnesia,best Cheapset Panel,best instagram tools,best price panel,low panel,panel instagram,instagram tools">
<meta name="keywords" content="Panel Google,aplikasi penambah followers, followers gratis,jual beli followers, isntagram followers,kebutuhan social media">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<link rel="shortcut icon" href="<?php echo $cfg_baseurl; ?>assets/images/logo/funcaraw.jpeg">
<link href="<?php echo $cfg_baseurl; ?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $cfg_baseurl; ?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $cfg_baseurl; ?>assets/css/app.min.css" rel="stylesheet" type="text/css" />
<script src='https://www.google.com/recaptcha/api.js'></script>
<script type="text/javascript" src="https://cdn.ywxi.net/js/1.js" async></script>
<script type="text/javascript" src="https://cdn.ywxi.net/js/1.js" async></script>
<style type="text/css">.hide{display:none!important}.show{display:block!important}</style>
<link href="<?php echo $cfg_baseurl; ?>assets/css/preloader.css" rel="stylesheet" type="text/css" />
         <!--  .theme-loader {
            height: 100%;
            width: 100%;
            background: #fff;
            position: fixed;
            z-index: 999999;
            top: 0;
            left: 0;
        }
            .theme-loader .loader-track {
            left: 50%;
            top: 50%;
            position: absolute;
            display: block;
            width: 150px;
            height: 150px;
            margin: -75px 0 0 -75px;
        }
            .theme-loader .loader-track:after,.theme-loader .loader-track:before {
            content: "";
            border-radius: 50%;
            position: absolute;
            z-index: 1;
            border: 4px solid #4099ff;
            border-top-color: transparent;
            border-bottom-color: transparent;
        }
            .theme-loader .loader-track:after,.theme-loader .loader-track:before {
            content: "";
            border-radius: 50%;
            position: absolute;
            z-index: 1;
            border: 4px solid #4099ff;
            border-top-color: transparent;
            border-bottom-color: transparent;
        }

            .theme-loader .loader-track:after {
            width: 100%;
            height: 100%;
            animation: move 1.4s linear infinite;
        }

            .theme-loader .loader-track:before {
            width: 80%;
            height: 80%;
            top: 10%;
            left: 10%;
            animation: move 1.2s linear infinite;
        }

            .theme-loader .loader-track .loader-bar {
            left: 50%;
            top: 50%;
            position: absolute;
            display: block;
            width: 90px;
            height: 90px;
            top: 30px;
            left: 30px;
        }

            .theme-loader .loader-track .loader-bar:after,.theme-loader .loader-track .loader-bar:before {
            content: "";
            border-radius: 50%;
            position: absolute;
            z-index: 1;
            border: 4px solid #4099ff;
            border-top-color: transparent;
            border-bottom-color: transparent;
        }

            .theme-loader .loader-track .loader-bar:after {
            width: 100%;
            height: 100%;
            animation: move 1s linear infinite;
        }

            .theme-loader .loader-track .loader-bar:before {
            width: 60%;
            height: 60%;
            top: 20%;
            left: 20%;
            animation: move .8s linear infinite;
        }

            @keyframes move {
            100% {
                transform: rotate(360deg);
        }
    } -->
         <div id="preloader">
        <div class="loader"></div>
    </div>
</head>
<body>
        <div id="wrapper">
            <div class="navbar-custom">
                 <ul class="list-unstyled topnav-menu float-right mb-0">
                     <?php if(isset($_SESSION['user'])){ ?>
                    <li class="dropdown notification-list">
                        <a class="nav-link dropdown-toggle nav-user mr-0 waves-effect waves-light" data-toggle="dropdown" href="#" role="button" aria-haspopup="false" aria-expanded="false">
                            <img src="<?php echo $cfg_baseurl; ?>assets/images/users/user1.png" alt="user-image" class="rounded-circle">
                            <span class="pro-user-name ml-1">
                                <font color ='white'><?php echo $sess_username; ?> <i class="mdi mdi-chevron-down"></i></font>
                            </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right profile-dropdown ">
                            <!-- item-->
                            <div class="dropdown-header noti-title">
                                <h6 class="text-overflow m-0">Welcome !</h6>
                            </div>

                            <!-- item-->
                            <a href="<?php echo $cfg_baseurl; ?>main/settings" class="dropdown-item notify-item">
                                <i class="remixicon-account-circle-line"></i>
                                <span>Profil Saya</span>
                            </a>

                            <!-- item-->
                            <a href="<?php echo $cfg_baseurl; ?>main/saldo" class="dropdown-item notify-item">
                                <i class="remixicon-wallet-line"></i>
                                <span>Mutasi Saldo</span>
                            </a>
                            <div class="dropdown-divider"></div>

                            <!-- item-->
                            <a href="<?php echo $cfg_baseurl; ?>auth/logout" class="dropdown-item notify-item">
                                <i class="remixicon-logout-box-line"></i>
                                <span>Logout</span>
                            </a>

                        </div>
                    </li>
                    <?php } ?>
                </ul>
                
                <div class="logo-box">
                    <a href="<?php echo $cfg_baseurl; ?>" class="logo text-center">
                        <span class="logo-lg">
                            <img src="<?php echo $cfg_baseurl; ?>assets/images/logo/arrraw12.png" alt="" height="30">
                        </span>
                        <span class="logo-sm">
                            <img src="<?php echo $cfg_baseurl; ?>assets/images/logo/funcaraw.jpeg" alt="" height="40">
                        </span>
                    </a>
                </div>

                <ul class="list-unstyled topnav-menu topnav-menu-left m-0">
                    <li>
                        <button class="button-menu-mobile waves-effect waves-light">
                            <i class="fe-menu"></i>
                        </button>
                    </li>
                </ul>
            </div>

            <div class="left-side-menu">
                <div class="slimscroll-menu">
                    <div id="sidebar-menu">
                        <ul class="metismenu" id="side-menu">
                            <li class="menu-title">Menu Utama</li>
                            
                            <li><a href="javascript: void(0);" class="waves-effect"><i class="remixicon-dashboard-fill"></i><span> Halaman </span><span class="menu-arrow"></span></a>
                                <ul class="nav-second-level nav" aria-expanded="false">
                                    </li>
                                    <li><a href="<?php echo $cfg_baseurl; ?>admin-page/index">Dashboard Admin</a>
                                    </li>
                                    <li><a href="<?php echo $cfg_baseurl; ?>">Dashboard Utama</a></li>
                                </ul>
                            </li>
                            
                            <li><a href="javascript: void(0);" class="waves-effect"><i class="remixicon-award-line"></i><span> Portable Website </span><span class="menu-arrow"></span></a>
                                <ul class="nav-second-level nav" aria-expanded="false">
                                    <li><a href="<?php echo $cfg_baseurl; ?>admin-page/pengguna/"> Kelola Pengguna</a></li>
                                    <li><a href="<?php echo $cfg_baseurl; ?>admin-page/deposit"> Kelola Deposit</a></li>
									<li><a href="<?php echo $cfg_baseurl; ?>admin-page/deposit_metode"> Kelola Metode Deposit</a></li>
									<li><a href="<?php echo $cfg_baseurl; ?>admin-page/laporan"> Kelola Penghasilan</a></li>
									<li><a href="<?php echo $cfg_baseurl; ?>admin-page/status"> Kelola On/Off Fitur</a></li>
									<li><a href="<?php echo $cfg_baseurl; ?>admin-page/vendor"> Kelola Provider</a></li>
									<li><a href="<?php echo $cfg_baseurl; ?>admin-page/riwayat_transfer"> Kelola Riwayat Transfer</a></li>
									<li><a href="<?php echo $cfg_baseurl; ?>admin-page/riwayat_aktifitas"> Kelola Riwayat Transfer</a></li>
                                </ul>
                            </li>
                            
                            <li><a href="<?php echo $cfg_baseurl; ?>admin-page/pesanan_mobile/"> <i class="remixicon-tablet-line"></i> <span> Manage Mobile</span> </a></li>
                            <li><a href="<?php echo $cfg_baseurl; ?>admin-page/pesanan_sosmed/"> <i class="remixicon-global-line"></i> <span> Manage Sosmed</span> </a></li>
                            <li><a href="<?php echo $cfg_baseurl; ?>admin-page/pesanan_sms/"> <i class="remixicon-mail-open-line"></i> <span> Manage Sms</span> </a></li>
                            
							<li><a href="<?php echo $cfg_baseurl; ?>admin-page/berita"> <i class="remixicon-mail-send-line"></i> <span> Kelola Informasi</span> </a></li>
							<li><a href="<?php echo $cfg_baseurl; ?>admin-page/getlayanan/sosmed/"> <i class="mdi mdi-web"></i> <span> Get Layanan Sosmed</span> </a></li>
							
						    <li><a href="javascript: void(0);" class="waves-effect"><i class="mdi mdi-tablet"></i><span> Get Layanan Mobile</span><span class="menu-arrow"></span></a>
                                <ul class="nav-second-level nav" aria-expanded="false">
                                     <li><a href="<?php echo $cfg_baseurl; ?>admin-page/getlayanan/mobile/pascabayar">Pascabayar</a></li>
                                    <li><a href="<?php echo $cfg_baseurl; ?>admin-page/getlayanan/mobile/pulsa">Pulsa</a></li>
									<li><a href="<?php echo $cfg_baseurl; ?>admin-page/getlayanan/mobile/ewallet">E Wallet</a></li>
									<li><a href="<?php echo $cfg_baseurl; ?>admin-page/getlayanan/mobile/games"> Games</a></li>
									<li><a href="<?php echo $cfg_baseurl; ?>admin-page/getlayanan/mobile/internet"> Internet</a></li>
									<li><a href="<?php echo $cfg_baseurl; ?>admin-page/getlayanan/mobile/smsnelpon">SMS & Nelpon</a></li>
									<li><a href="<?php echo $cfg_baseurl; ?>admin-page/getlayanan/mobile/token"> Token Listrik</a></li>
									<li><a href="<?php echo $cfg_baseurl; ?>admin-page/getlayanan/mobile/voc"> Voucher</a></li>
                                </ul>
                            </li>	
                            <li><a href="javascript: void(0);" class="waves-effect"><i class="mdi mdi-view-list"></i><span> Get Category Mobile</span><span class="menu-arrow"></span></a>
                                <ul class="nav-second-level nav" aria-expanded="false">
                                     <li><a href="<?php echo $cfg_baseurl; ?>admin-page/getlayanan/mobile/pascabayar">Pascabayar</a></li>
                                    <li><a href="<?php echo $cfg_baseurl; ?>admin-page/getcategory/mobile/pulsa">Pulsa</a></li>
									<li><a href="<?php echo $cfg_baseurl; ?>admin-page/getcategory/mobile/ewallet">E Wallet</a></li>
									<li><a href="<?php echo $cfg_baseurl; ?>admin-page/getcategory/mobile/games"> Games</a></li>
									<li><a href="<?php echo $cfg_baseurl; ?>admin-page/getcategory/mobile/internet"> Internet</a></li>
									<li><a href="<?php echo $cfg_baseurl; ?>admin-page/getcategory/mobile/smsnelpon">SMS & Nelpon</a></li>
									<li><a href="<?php echo $cfg_baseurl; ?>admin-page/getcategory/mobile/token"> Token Listrik</a></li>
									<li><a href="<?php echo $cfg_baseurl; ?>admin-page/getcategory/mobile/voc"> Voucher</a></li>
                                </ul>
                            </li>
							<li><a href="javascript: void(0);" class="waves-effect"><i class="remixicon-question-line"></i><span> Lainnya </span><span class="menu-arrow"></span></a>
                                <ul class="nav-second-level nav" aria-expanded="false">
                                    <li><a href="<?php echo $cfg_baseurl; ?>admin-page/riwayat_aktifitas"> Kelola Log Pengguna</a></li>
									<li><a href="<?php echo $cfg_baseurl; ?>admin-page/saldo_pusat"> Kelola Saldo Pusat</a></li>
                                </ul>
                            </li>

				    </ul>

                    </div>

                    <div class="clearfix"></div>

                </div>

            </div>

<div class="content-page">
<div class="content">
 <div class="container-fluid"><br/>
                        
<?php
if (isset($_SESSION['result'])) {
?>
<div class="alert alert-<?php echo $_SESSION['result']['alert'] ?> alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
    <strong>Respon : </strong><?php echo $_SESSION['result']['judul'] ?><br /> <strong>Pesan : </strong> <?php echo $_SESSION['result']['pesan'] ?>
</div>
<?php
unset($_SESSION['result']);
}
?>