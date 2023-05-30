<?php

//* Released Script 25-09-2019
//* Created Script A.K.A Made With [ Jack Coder ]
//* No Delete Nama Pembuat
//* Hargai Orang Jika Ingin Di Hargai THX
//* 2019 Notif Coder Program Array PHP Native Current Version 7,2
//* Indonesia Mempunyai UU ITE Undang Undang Nomor 28 Tahun 2014 [ https://id.wikipedia.org/wiki/Hak_cipta_di_Indonesia ]
//* Thx Stacklower, Thx W3School, Thx PHP Net

date_default_timezone_set('Asia/Jakarta');
error_reporting(0);

$cfg_mt = 0; // Maintenance? 1 = ya 0 = tidak
if($cfg_mt == 1) {
    include 'maintenance.html';
	exit();
}

$cfg_verif = 'no_handphone'; // Maintenance? 1 = ya 0 = tidak
    if($cfg_verif == ['no_handphone']) {
    include 'main/verif.php';
	exit();
}
// web
$cfg_webname = "Indo Flazz - Distributor & Supplier Pulsa H2H Termurah.";
$cfg_logo_txt = "Indo Flazz";
$cfg_baseurl = "https://www.indoflazz.site/";
$cfg_desc = "Indo Flazz adalah Distributor & Supplier Pulsa H2H Termurah, Tercepat & Berkualitas di indonesia.";
$cfg_author = "Indo Group";
$cfg_about = "Indo Flazz adalah Distributor & Supplier Pulsa H2H Termurah, Tercepat & Berkualitas di indonesia.";

$system = $_SERVER['HTTP_USER_AGENT'];
$host = $_SERVER['HTTP_HOST'];

// fitur staff
$cfg_min_transfer = 1000; // jumlah minimal transfer 

$cfg_member_price = 0; // harga pendaftaran member
$cfg_member_bonus = 0; // bonus saldo member

$cfg_reseller_price = 80000; // harga pendaftaran reseller
$cfg_reseller_bonus = 35000; // bonus saldo reseller

// fitur buy credit
$cfg_credit_seratus = 100; // jumlah credit
$cfg_seratus_harga = 7500; // harga credit

$cfg_credit_limaratus = 500; // jumlah credit
$cfg_limaratus_harga = 20000; // harga credit

$cfg_credit_seribu = 1000; // jumlah credit
$cfg_seribu_harga = 35000; // harga credit


// database
$db_server = "localhost";
$db_user = "allcoder_indo";
$db_password = "allcoder_indo";
$db_name = "allcoder_indo";

// date & time
$bulan = array(
                '01' => 'Januari',
                '02' => 'Februari',
                '03' => 'Maret',
                '04' => 'April',
                '05' => 'Mei',
                '06' => 'Juni',
                '07' => 'Juli',
                '08' => 'Agustus',
                '09' => 'September',
                '10' => 'Oktober',
                '11' => 'November',
                '12' => 'Desember',
        );
$dates = date('d').' '.(strtolower($bulan[date('m')])).' '.date('Y');
$time = date("H:i:s a");
$date = date("Y-m-d");

// Require Script SMM
require("lib/database.php");
require("lib/function.php");